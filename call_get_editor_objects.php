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

		$params = array();

    	$response = SureDone_Store::editor_objects('items', 2, "date_", $token, isset($_SESSION['username'])?$_SESSION['username']:'');
	

            echo $response;

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
?>