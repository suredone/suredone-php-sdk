<?php
/**
* Synchronize orders to NetSuite
*
* Usage: php netsuite.php --sd-username=demo --sd-token=xyz
* sd-username - SureDone username
* sd-token - SureDone token
**/

require_once(dirname(__FILE__).'/NetSuite.class.php');

if (strpos($_SERVER["SCRIPT_FILENAME"], 'NetSuiteSync.php') !== false) {
    $required = array(
        'sd-username',
        'sd-token',
    );
    $longopts  = array();
    foreach($required as $field) {
        $longopts[] = $field . ':';
    }
    $options = getopt('', $longopts);
    $kill = false;
    foreach ($required as $field) {
        if (empty($options[$field])) {
            echo 'Option ' . $field . " is required! \n\r";
            $kill = true;
        }
    }
    if ($kill){
        die();
    }

    $suite = new NetSuite($options['sd-username'],$options['sd-token']);
    $suite->sync_orders();
}
?>