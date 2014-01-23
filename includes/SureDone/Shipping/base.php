<?php

require_once(dirname(__FILE__) . '/../../SureDone_Startup.php');

/**
* Base Shipping integration
*/
abstract class BaseShipping
{
    public $api = 'SureDone_Store';
    protected $token = null;
    public $admin_email = 'team@ydtechnology.com';

    abstract protected function ship($order);

    public function authenticate_by_token($token) {
        $this->token = $token;
    }

    public function authenticate($username, $password) {
        $api = $this->api;
        $rbody = $api::authenticate($username, $password);
        $responseObj = json_decode($rbody);
        if (empty($responseObj->token)) {
            $this->report_error('Can not authenticate');
            return;
        }
        $this->token = $responseObj->token;
    }

    public function run() {
        if (empty($this->token)) {
            $this->report_error('Not authenticated');
            return;
        }

        $api = $this->api;
        $i = 1;
        while (true) {
            $response = $api::get_awaiting_orders($i, 'shipcarrier', $this->token);
            $i++;
            $responseObj = json_decode($response);
            if (!$responseObj) {
                break;
            }
            foreach ($responseObj as $order) {
                $this->ship($order);
            }
        }
    }

    protected function update($order) {
        $api = $this->api;
        $api::update_order($order, $this->token);
    }

    protected function report_wrong_credentials() {
        $api = $this->api;
        $response = $api::get_profile($this->token);
        $responseObj = json_decode($response);
        $message = 'Provided credentials for ' . get_class($this) . ' are invalid. Please udate.';
        $this->mail($responseObj->email, 'SureDone wrong credentials', $message);
    }

    protected function report_error($message) {
        $api = $this->api;
        $this->mail($this->admin_email, 'SureDone Shipstation integration error', $message);
    }

    protected function mail($to, $subject, $body) {
        mail($to, $subject, $body);
    }
}
?>
