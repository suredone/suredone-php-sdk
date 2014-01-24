<?php
function check_shipping($options) {
    if (empty($options['engine'])) {
        throw new Exception('Engine is required');
    }
    $engine = $options['engine'];

    if (empty($options['token']) && (empty($options['username']) || empty($options['password']))) {
        throw new Exception('Token or credentials is required');
    }

    require_once(dirname(__FILE__) . '/includes/SureDone/Shipping/' . $engine . '.php');
    $s = new $engine();
    if (!empty($options['use_class'])) {
        $s = $options['use_class'];
    }
    if (!empty($options['token'])) {
        $s->authenticate_by_token($options['token']);
    } else {
        $s->authenticate($options['username'], $options['password']);
    }
    $s->run();
}


if (strpos($_SERVER["SCRIPT_FILENAME"], 'check_shipping') !== false) {
    $longopts  = array(
        "engine:",
        "username::",
        "password::",
        "token::",
    );
    $options = getopt('', $longopts);
    check_shipping($options);
}
?>
