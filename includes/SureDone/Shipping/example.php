<?php
require_once(dirname(__FILE__) . '/base.php');

/**
* ShipStation integration
*/
class ExampleShipping extends BaseShipping
{
    protected function ship($order) {
        // Do some external action and update order
        $this->update($order);
    }

    public function example_report_wrong_credentials() {
        $this->report_wrong_credentials();
    }
}
?>
