<?php
session_start();
	// report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// load the SureDone library
require_once ('includes/SureDone_Startup.php');

// if Authentication option is selected
// start - Authentication

		$token =  isset($_SESSION['token'])?$_SESSION['token']:'';


        echo "---- Testing Forgot Pass ----<br>";

		$params = array('user' => 'demo', ' recaptcha_challenge_field' => 'field1', 'recaptcha_response_field' => 'field2');
        $response = SureDone_Store::forgot($params, $token, isset($_SESSION['username'])?$_SESSION['username']:'');

        echo "response :<br>";
        print_r($response);

?>