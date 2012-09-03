<?php
/**
* Performs data validation based on rules
* Supports following validation orders: sequence, all-at-once
* and mix of them (using dependencies model)
* Can embed different user-defined validation rules libraries
* (default built-in library is provided)
*
* @author Mark Rolich <mark.rolich@gmail.com>
*/
class DataValidator
{
    /**
    * @var mixed - array of validation rules
    * (for format reference please see documentation.html)
    */
    private $ruleset;

    /**
    * @var mixed - data array to validate
    */
    private $data;

    /**
    * @var int - validation order
    *
    * 0 - sequence
    * 1 - all-at-once (default)
    */
    private $order = 1;

    /**
    * @var mixed - validation results queue
    */
    private $queue;

    /**
    * @var mixed - array of user-defined embedded validation libraries
    */
    private $libs;

    /**
    * @var string - default validation rules library
    */
    const DEFAULT_LIB = 'DataValidatorLib';

    /**
    * Ruleset setter
    *
    * @param $ruleset mixed - array of validation rules
    */
    public function setRuleset($ruleset)
    {
        $this->ruleset = $ruleset;
    }

    /**
    * Data setter
    *
    * @param $data mixed - data array to validate
    */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
    * Validation order setter
    *
    * @param $order int - validation order
    */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
    * Parses condition call part,
    * extracts and returns library class name
    * as a string if method is static
    * or initialized object otherwise,
    * and method name
    *
    * @param $call string - validation rule condition call part
    * @return mixed - array of library class name (or object) and method name
    */
    private function locateLib($call)
    {
        if (strpos($call, '(') === false) {
            $lib = self::DEFAULT_LIB;
        } else {
            list($libName, $call) = explode(':', trim($call, '()'));

            if (!isset($this->libs[$libName])) {
                $method = new ReflectionMethod($libName . '::' . trim($call, '!'));

                $this->libs[$libName] = $method->isStatic()
                                        ? $libName
                                        : new $libName;
            }

            $lib = $this->libs[$libName];
        }

        return array($lib, $call);
    }

    /**
    * Validates data against provided ruleset,
    * resolves dependencies between data items,
    * fills queue with non valid data items
    *
    */
    public function validate()
    {
        $conds = array_keys($this->ruleset);

        foreach ($conds as $cond) {
            list($dId) = explode('.', $cond);
            $dIds[$cond] = $dId;
        }

        foreach ($this->ruleset as $condition => $rule) {
            $params = array();
            $values = array();

            list($id, $call) = explode('.', $condition);

            if ($this->order == 0 && !empty($this->queue)) {
                break;
            } elseif ($this->order == 1) {

                if (isset($rule['dpnd'])) {
                   $dpnd = $rule['dpnd'];

                    while ($dpnd != null) {
                        $cond = array_search($dpnd, $dIds);

                        if ($cond !== false) {
                            if (isset($this->queue['array'][$dpnd])) {
                                continue 2;
                            }

                            $dpnd = (isset($this->ruleset[$cond]['dpnd']))
                                    ? $this->ruleset[$cond]['dpnd']
                                    : null;
                        } else {
                            $dpnd = null;
                        }
                    }
                }

                if (isset($this->queue['array'][$id])) {
                    continue;
                }
            }

            if (isset($this->data[$id])) {
                list($lib, $call) = $this->locateLib($call);

                if (is_array($rule)) {
                    if (isset($rule['bindValues'])) {
                        foreach ($rule['bindValues'] as $dataId) {
                            $values[] = $this->data[$dataId];
                        }
                    }

                    if (isset($rule['bindParams'])) {
                        $params = $rule['bindParams'];
                    }
                }

                $args = array_merge(array($this->data[$id]), $values, $params);

                $result = call_user_func_array(array($lib, trim($call, '!')), $args);

                if ($call[0] == '!') {
                    $result = (int)!$result;
                }

                if ($result == 1) {
                    $this->queue['hash'][$condition] =
                    $this->queue['array'][$id][$call] = $result;
                }
            }
        }
    }

    /**
    * Composes array of errors from queue
    * with data IDs as keys and error messages as values
    *
    * @return mixed - array of errors
    */
    public function getErrors()
    {
        $errors = array();

        if ($this->queue['hash'] != null) {
            foreach ($this->queue['hash'] as $condition => $result) {
                list($id) = explode('.', $condition);

                if (is_array($this->ruleset[$condition])) {
                    $errors[$id] = $this->ruleset[$condition]['msg'];
                } else {
                    $errors[$id] = $this->ruleset[$condition];
                }

            }
        }

        return $errors;
    }
}
?>