<?php
require_once(dirname(__FILE__) . '/base.php');

class AuthenticateTest extends BaseTestCase {

    public function disabletTestAuthenticateSuccess() {
        $token = $this->_authenticate();
        $this->assertNotEmpty($token);
    }

    public function testTokenAuthenticateSuccess() {
        $token = $this->_authenticate_token();
        $this->assertNotEmpty($token);
    }

    public function disabledTestAuthenticateFailure(){
        $token = $this->_authenticate('fake');
        $this->assertEmpty($token);
    }

    public function testPlaceholder(){
        $this->assertTrue(true);
    }
}
