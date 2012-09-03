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
    private $dbh = 1;

    public function isExist($email)
    {
        return ($email == 'www@www.www' && $this->dbh == 1) ? 1 : 0;
    }
}
?>