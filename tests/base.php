<?php
require_once ('includes/SureDone_Startup.php');

class BaseTestCase extends PHPUnit_Framework_TestCase {

    protected function _authenticate($user = 'demo', $password = 'test123') {
        $rbody = SureDone_Store::authenticate('demo', 'test123');
        $responseObj = json_decode($rbody);
        if (isset($responseObj->token)) {
            return $responseObj->token;
        }
    }

}

?>
