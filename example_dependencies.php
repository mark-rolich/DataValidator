<?php
include 'lib/DataValidatorLib.php';
include 'lib/DataValidator.php';

$data = array(
    'field1' => '',
    'field2' => '',
    'field3' => '',
    'field4' => '',
    'field5' => '',
    'field6' => '',
    'field7' => ''
);

$ruleset = array(
    'field1.isEmpty' => 'Please enter field1',
    'field2.isEmpty' => array(
        'msg' => 'Please enter field2',
        'dpnd' => 'field1'
    ),
    'field3.isEmpty' => array(
        'msg' => 'Please enter field3',
        'dpnd' => 'field2'
    ),
    'field4.isEmpty' => array(
        'msg' => 'Please enter field4',
        'dpnd' => 'field3'
    ),
    'field5.isEmpty' => 'Please enter field5',
    'field6.isEmpty' => array(
        'msg' => 'Please enter field6',
        'dpnd' => 'field5'
    ),
    'field7.isEmpty' => 'Please enter field7'
);

$validator = new DataValidator();

$validator->setData($data);
$validator->setRuleset($ruleset);
$validator->setOrder(1);

$validator->validate();

$errors = $validator->getErrors();

echo '<pre>';
print_r($errors);
echo '</pre>';
?>