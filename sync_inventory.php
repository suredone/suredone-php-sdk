<?php
/**
* Synchronize inventory from csv file
* Usage: php sync_inventory.php --username=demo --token=foo --ftp-host=example.com --ftp-user=me --ftp-password=bar
**/

require_once(dirname(__FILE__) . '/includes/Suredone/SyncInventory.php');

if (strpos($_SERVER["SCRIPT_FILENAME"], 'sync_inventory') !== false) {
    $required = array(
        'username',
        'token',
        'ftp-host',
        'ftp-user',
        'ftp-password',
    );
    $longopts  = array(
        "ftp-port::",
        "directory::",
        "file::",
    );
    foreach($required as $field) {
        $longopts[] = $field . ':';
    }
    $options = getopt('', $longopts);
    foreach ($required as $field) {
        if (empty($options[$field])) {
            echo 'Option ' . $field . ' is required!';
            die(1);
        }
    }

    $s = new SyncInventory($options['username'], $options['token']);
    $ftp_port = !empty($options['ftp-port']) ? $options['ftp-port'] : null;
    $file = !empty($options['file']) ? $options['file'] : null;
    $directory = !empty($options['directory']) ? $options['directory'] : null;
    $s->from_ftp($options['ftp-host'], $ftp_port, $options['ftp-user'], $options['ftp-password'], $directory, $file);
}

?>
