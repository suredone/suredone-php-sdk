<?php
require_once ('includes/SureDone_Startup.php');

class BaseTestCase extends PHPUnit_Framework_TestCase {

    protected function _authenticate($user = 'demo', $password = 'hello123') {
        $rbody = SureDone_Store::authenticate($user, $password);
        $responseObj = json_decode($rbody);
        if (isset($responseObj->token)) {
            return $responseObj->token;
        }
    }

    protected function _authenticate_token($user = 'demo', $APIToken = '43C46141EF0010D1E76D9AE2AC3440BFB82F22D2F5A6FE40024C28967A354E591BB9FA21B784BF006M4UFZO8IKITYTCTTHJW0AVE6UEKBTIKJ55ZNKCQU2VYYH11XKULTCQWVQKEXEVMIQ2ANFB9SW6RVQLU22BOBX1JBOD0H') {
        $rbody = SureDone_Store::authenticate($user, Null, $APIToken);
        $responseObj = json_decode($rbody);
        if (isset($responseObj->token)) {
            return $responseObj->token;
        }
    }

}

?>
