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

echo "---- Testing Get Single Element (By SKU) ----<br>";

$result = SureDone_Store::get_editor_single_object_by_sku('items', $_REQUEST['object_sku'], $token, isset($_SESSION['username'])?$_SESSION['username']:'');

print_r($result);

?>