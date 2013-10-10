<?php
session_start();
	// report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// load the SureDone library
require_once ('includes/SureDone_Startup.php');

$token =  isset($_SESSION['token'])?$_SESSION['token']:'';

echo "---- Testing Get Shipped Orders ----<br>";


$params = array();
$response = SureDone_Store::get_shipped_orders($_REQUEST['page_no'], 'shipcarrier', $token, isset($_SESSION['username'])?$_SESSION['username']:'');

print_r($response);		

?>