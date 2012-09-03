<?php
/**
* Default validation library for DataValidator class
*
* @author Mark Rolich <mark.rolich@gmail.com>
*/
class DataValidatorLib
{
    /**
    * Check if the string is empty or not
    *
    * @param $value string - string to check
    * @return int
    */
    public static function isEmpty($value)
    {
        return ($value == '') ? 1 : 0;
    }

    /**
    * Check how the string compares with provided length
    *
    * @param $value string - string to check
    * @param $type string - type of comparison (eq|neq|gt|gte|lt|lte)
    * @param $len int - length to compare
    * @return int
    */
    public static function isStrlen($value, $type, $len)
    {
        $result = 0;

        switch ($type) {
			case 'eq':
				$result = (strlen($value) == $len) ? 1 : 0;
				break;
			case 'neq':
                $result = (strlen($value) != $len) ? 1 : 0;
				break;
			case 'gt':
                $result = (strlen($value) > $len) ? 1 : 0;
				break;
			case 'gte':
                $result = (strlen($value) >= $len) ? 1 : 0;
				break;
			case 'lt':
                $result = (strlen($value) < $len) ? 1 : 0;
				break;
			case 'lte':
                $result = (strlen($value) <= $len) ? 1 : 0;
				break;
			default:
                $result = (strlen($value) == $len) ? 1 : 0;
        }

        return $result;
    }

    /**
    * Check if the string length is between provided low and high values
    *
    * @param $value string - string to check
    * @param $min int - low value
    * @param $max int - high value
    * @return int
    */
    public static function isStrlenbtw($value, $min, $max) {
        return (strlen($value) >= $min && strlen($value) <= $max) ? 1 : 0;
    }

    /**
    * Check if the string equals to another
    *
    * @param $value1 string
    * @param $value2 string
    * @return int
    */
    public static function isEqual($value1, $value2) {
        return ($value1 == $value2) ? 1 : 0;
    }

    /**
    * Check if the string corresponds to email address format
    *
    * @param $value string - string to check
    * @return int
    */
    public static function isEmail($value)
    {
        $emailPattern = '/^[a-zA-Z]+([_\.-]?[a-zA-Z0-9]+)*@[a-zA-Z0-9]+([\.-]?[a-zA-Z0-9]+)*(\.[a-zA-Z]{2,4})+$/';
        return (preg_match($emailPattern, $value) != 0) ? 1 : 0;
    }

    /**
    * Check if the value is contained in the provided values range
    *
    * @param $value mixed - value (or array of values) to check
    * @param $range mixed - array of values to match
    * @return int
    */
    public static function inRange($value, $range)
    {
        $result = 0;

        if (is_array($value)) {
            $diff = array_diff($value, $range);
            $result = empty($diff) ? 1 : 0;
        } else {
            $result = in_array($value, $range) ? 1 : 0;
        }

        return $result;
    }
}
?>