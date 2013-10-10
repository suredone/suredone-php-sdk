<?php

// ensure that json_decode is enabled, which is used to parse the response (JSON encoded) and decoded as array 
if (!function_exists('json_decode')) {
    throw new Exception('SureDone needs the JSON PHP extension.');
}

// include files related to API
require(dirname(__FILE__) . '/SureDone/SureDone.php');
require(dirname(__FILE__) . '/SureDone/ApiRequestor.php');
require(dirname(__FILE__) . '/SureDone/Store.php');

// Errors
require(dirname(__FILE__) . '/SureDone/Error.php');
require(dirname(__FILE__) . '/SureDone/ApiError.php');
require(dirname(__FILE__) . '/SureDone/ApiConnectionError.php');
require(dirname(__FILE__) . '/SureDone/AuthenticationError.php');
require(dirname(__FILE__) . '/SureDone/InvalidRequestError.php');
