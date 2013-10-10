<?php
session_start();
	// report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// load the SureDone library
require_once ('includes/SureDone_Startup.php');

// if Authentication option is selected
// start - Authentication

        echo "---- Testing Search----<br>";

		$token =  isset($_SESSION['token'])?$_SESSION['token']:'';

        try {

			if ( empty($_REQUEST['brand']) ) {
            $params = array();
			} else {
            $params = array('brand' => $_REQUEST['brand']);
			}
            $response = SureDone_Store::search('items', $params, $token, isset($_SESSION['username'])?$_SESSION['username']:'');

            echo $response;

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
?>