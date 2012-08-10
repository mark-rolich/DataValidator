<?php
/**
* Example dynamic validation library for DataValidator class
*
* @author Mark Rolich <mark.rolich@gmail.com>
*/
class User
{
    /**
    * Stub function to demonstrate library embedding
    *
    */
    public function isExist($email)
    {
        return ($email == 'aaa@aaa.aaa') ? 1 : 0;
    }
}
?>