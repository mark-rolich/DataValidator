<?php
include 'lib/DataValidatorLib.php';
include 'lib/User.php';
include 'lib/Email.php';

include 'lib/DataValidator.php';

$data = array(
    'email' => '',
    'fname' => '',
    'lname' => '',
    'pwd' => '',
    'confirm_pwd' => '',
    'sex' => '',
    'options' => array()
);

$ruleset = array(
    'email.isEmpty'          => 'Please enter email address',
    'email.!isEmail'         => 'Please enter correct email address',
    'email.(Email:!isValid)' => 'Please enter valid email address',
    'email.(User:isExist)'   => 'Someone already has that username',
    'fname.isEmpty'          => 'Please enter firstname',
    'lname.isEmpty'          => 'Please enter lastname',
    'pwd.isEmpty'            => 'Please enter password',
    'pwd.isStrlen'      => array(
        'bindParams' => array(
            'type' => 'lte',
            'len' => 6
        ),
        'msg' => 'Password is too short, use at least 7 characters'
    ),
    'confirm_pwd.isEmpty' => array(
        'msg' => 'Please confirm password',
        'dpnd' => 'pwd'
    ),
    'confirm_pwd.isStrlen' => array(
        'bindParams' => array(
            'type' => 'lte',
            'len' => 6
        ),
        'msg' => 'Password is too short, use at least 7 characters',
        'dpnd' => 'pwd'
    ),
    'confirm_pwd.!isEqual' => array(
        'bindValues' => array('pwd'),
        'msg' => 'Passwords do not match',
        'dpnd' => 'pwd'
    ),
    'sex.!inRange' => array(
        'bindParams' => array(
            'range' => array('Male', 'Female')
        ),
        'msg' => 'Please select correct sex'
    ),
    'options.!inRange' => array(
        'bindParams' => array(
            'range' => array(0,1,2)
        ),
        'msg' => 'Please select correct option'
    )
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