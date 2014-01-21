<?php
require_once ('base.php');

class AuthenticateTest extends BaseTestCase {

    public function disabledTestAuthenticateSuccess() {
        $token = $this->_authenticate();
        $this->assertNotEmpty($token);
    }

    public function testAuthenticateFailure(){
        $token = $this->_authenticate('fake');
        $this->assertEmpty($token);
    }
}