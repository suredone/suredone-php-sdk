<?php
require_once(dirname(__FILE__) . '/base.php');
require_once(dirname(__FILE__) . '/../includes/SureDone/Shipping/ExampleShipping.php');

class ExampleShippingTest extends BaseTestCase {

    public function testAuthenticateByToken() {
        $s = new ExampleShipping();
        $s->authenticate_by_token('foo');

        $property = new ReflectionProperty('ExampleShipping', 'token');
        $property->setAccessible(true);
        $this->assertEquals($property->getValue($s), 'foo');
    }

    public function testAuthenticate() {
        $observer = $this->getMock('SureDone_Store', array('authenticate'));
        $observer::staticExpects($this->any())
             ->method('authenticate')
             ->will($this->returnValue('{"token": "fake-token"}'));

        $ref = new ReflectionMethod('SureDone_Store', 'authenticate');

        $s = new ExampleShipping();
        $s->api = $observer;
        $s->authenticate('foo', 'bar');

        $property = new ReflectionProperty('ExampleShipping', 'token');
        $property->setAccessible(true);
        $this->assertEquals($property->getValue($s), 'fake-token');
    }

    public function testRun() {
        $observer = $this->getMock('SureDone_Store', array('get_awaiting_orders', 'update_order'));
        $observer::staticExpects($this->at(0))
             ->method('get_awaiting_orders')
             ->will($this->returnValue(file_get_contents(dirname(__FILE__) . '/test_data/get_awaiting_orders_1.json')));

        $observer::staticExpects($this->at(1))
             ->method('get_awaiting_orders')
             ->will($this->returnValue(file_get_contents(dirname(__FILE__) . '/test_data/get_awaiting_orders_2.json')));

        $observer::staticExpects($this->exactly(2))
             ->method('update_order');

        $s = new ExampleShipping();
        $s->api = $observer;
        $s->authenticate_by_token('foo');
        $s->run();
    }

    public function testReportWrongCredentials() {
        $observer = $this->getMock('SureDone_Store', array('get_profile'));

        $observer::staticExpects($this->any())
             ->method('get_profile')
             ->will($this->returnValue(file_get_contents(dirname(__FILE__) . '/test_data/profile.json')));

        $s = $this->getMock('ExampleShipping', array('mail'));
        $s->Expects($this->exactly(1))
             ->method('mail')
             ->with($this->stringContains('demo@suredone.com'),
                    $this->stringContains('SureDone wrong credentials'),
                    $this->anything());
        $s->api = $observer;
        $s->authenticate_by_token('foo');
        $s->example_report_wrong_credentials();
    }

    public function testReportError() {
        $observer = $this->getMock('SureDone_Store', array('authenticate'));

        $observer = $this->getMock('SureDone_Store', array('authenticate'));
        $observer::staticExpects($this->any())
             ->method('authenticate')
             ->will($this->returnValue('{"token": ""}'));

        $s = $this->getMock('ExampleShipping', array('mail'));
        $s->Expects($this->exactly(1))
             ->method('mail')
             ->with($this->stringContains($s->admin_email),
                    $this->stringContains('SureDone Shipstation integration error'),
                    $this->stringContains('Can not authenticate'));
        $s->api = $observer;
        $s->authenticate('foo', 'foo');
    }

    public function testReportErrorOnRun() {
        $observer = $this->getMock('SureDone_Store', array('authenticate'));

        $observer = $this->getMock('SureDone_Store', array('authenticate'));
        $observer::staticExpects($this->any())
             ->method('authenticate')
             ->will($this->returnValue('{"token": ""}'));

        $s = $this->getMock('ExampleShipping', array('mail'));
        $s->Expects($this->exactly(1))
             ->method('mail')
             ->with($this->stringContains($s->admin_email),
                    $this->stringContains('SureDone Shipstation integration error'),
                    $this->stringContains('Not authenticated'));
        $s->api = $observer;
        $s->run();
    }
}

?>
