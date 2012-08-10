<?php
/**
* Example static validation library for DataValidator class
*
* @author Mark Rolich <mark.rolich@gmail.com>
*/
class Email
{
    /**
    * Stub function to demonstrate library embedding
    *
    */
    public static function isValid($email)
    {
        return ($email == 'www@www.www') ? 1 : 0;
    }
}
?>