<?php
session_start();
	// report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// load the SureDone library
require_once ('includes/SureDone_Startup.php');

// if Authentication option is selected
// start - Authentication

        echo "---- Testing Get All Options  ----<br>";

		$token =  isset($_SESSION['token'])?$_SESSION['token']:'';

        try {

            $response = SureDone_Store::get_all_orders($_REQUEST['page_no'], 'shiptracking', $token, isset($_SESSION['username'])?$_SESSION['username']:'');

            print_r($response);

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
?>
