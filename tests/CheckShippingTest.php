<?php
require_once(dirname(__FILE__) . '/base.php');
require_once(dirname(__FILE__) . '/../check_shipping.php');

class CheckShippingTest extends BaseTestCase {

     /**
     * @expectedException     Exception
     * @expectedExceptionMessage Engine is required
     */
    public function testNoEngine() {
        check_shipping(array());
    }

    /**
     * @expectedException     Exception
     * @expectedExceptionMessage Token or credentials is required
     */
    public function testNoToken() {
        check_shipping(array('engine' => 'ExampleShipping'));
    }

    public function testToken() {
        $s = $this->getMock('ExampleShipping', array('run'));
        $s->Expects($this->exactly(1))
             ->method('run');
        check_shipping(array('engine' => 'ExampleShipping', 'token' => 'foo', 'use_class' => $s));
    }

    public function testAuthenticate() {
        $s = $this->getMock('ExampleShipping', array('run', 'authenticate'));
        $s->Expects($this->exactly(1))
             ->method('run');
        $s->Expects($this->exactly(1))
             ->method('authenticate');
        check_shipping(array('engine' => 'ExampleShipping', 'username' => 'foo', 'password' => 'bar', 'use_class' => $s));
    }

}

?>
