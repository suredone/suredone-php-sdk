<?php

/**
 * The class serves as a wrapper for SureDone.com APIS. It allows the developers to connect to SureDone server and execute available API calls.
 * The use of this class results in seamless integration of the SureDone functionality into your store.
 * @package    suredone
 * @author     jason nadaf (jason@suredone.com
 * @version    1.0
 */
class SureDone_Store {
    /*
     * Authenticate
     *
     * The function validate the user based on the credentials (user & pass) specified
     *
     * @param String $user - username of the registered SureDone user
     * @param String $pass - password of the registered SureDone user
     * @param String $APIToken - Api token of the registered SureDone user
     * @return array decoded from JSON format response
     */

    public static function authenticate($user = NULL, $pass = NULL, $APIToken = NULL) {

        // array of parameters
        $params = array('user' => $user, 'pass' => $pass, 'token' => $APIToken);
        // call the validatio method
        self::validateCall('authenticate', $params, null);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array
        $requestor = new SureDone_ApiRequestor(null);
        // URL of the API call
        $url = 'v1/auth';
        // call the POST method for the API call
        return $requestor->request('POST', $url, $params);
    }

    /*
     * get_profile
     *
     * The function return the profile object of the currently logged in user
     *
     * @param String $authToken - token returned as a result of 'authentication'
     * @return array - profile of the currently logged in user
     */

    public static function get_profile($authToken = null, $user = null) {
        // blank parameter as no parameter is needed to get the currently logged in user's profile.
        $params = null;
        // call the validatio method
        self::validateCall('get_profile', $params, $authToken);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
        // URL of the API call
        $url = 'v1/profile';
        // call the GET method for the API call
        return $requestor->request('GET', $url, $params);
    }

    /*
     * update_profile
     *
     * The function return the profile object of the currently logged in user
     *
     * @param String $authToken - token returned as a result of 'authentication'
     * @return array - profile of the currently logged in user
     */

    public static function update_profile($params = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
        if (!$params || !is_array($params)) {
            throw new Exception('parameter object cannot be null');
        }


        // call the validatio method
        self::validateCall('update_profile', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
        // URL of the API call
        $url = 'v1/profile/update';
        // call the POST method for the API call
        return $requestor->request('POST', $url, $params);
    }


    public static function forgot($params = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
        if (!$params || !is_array($params)) {
            throw new Exception('parameter object cannot be null');
        }


        // call the validatio method
        self::validateCall('forgot', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
        // URL of the API call
        $url = 'v1/auth/forgot';
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }


    public static function assist($params = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
        if (!$params || !is_array($params)) {
            throw new Exception('parameter object cannot be null');
        }


        // call the validatio method
        self::validateCall('assist', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
        // URL of the API call
        $url = 'v1/auth/assist';
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }



    public static function search($type = null, $params = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
//        if (!$type || !$params || !is_array($params)) {
//            throw new Exception('type and parameter object cannot be null');
//        }


        // call the validatio method
        self::validateCall('seach', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
		if ( count($params) ) {
		$queryStr =  '/' . str_replace('=',':=',http_build_query ($params));
		} else {
		$queryStr = "";
		}

		$url = 'v1/search/'. $type . $queryStr;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }

    public static function editor_objects($type = null, $page = null, $sort = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
//        if (!$type || !$params || !is_array($params)) {
//            throw new Exception('type and parameter object cannot be null');
//        }

		$params = array();

        // call the validatio method
        self::validateCall('editor', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/editor/'. $type . '?page=' . $page . '&sort=' . $sort;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }

    public static function get_editor_single_object_by_id($type = null, $key = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
//        if (!$type || !$params || !is_array($params)) {
//            throw new Exception('type and parameter object cannot be null');
//        }

		$params = array();

        // call the validatio method
        self::validateCall('get_editor_single_object', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/editor/' . $type . '/edit/' . $key ;

        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }

    public static function get_editor_single_object_by_sku($type = null, $key = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
//        if (!$type || !$params || !is_array($params)) {
//            throw new Exception('type and parameter object cannot be null');
//        }

		$params = array();

        // call the validatio method
        self::validateCall('get_editor_single_object', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/editor/' . $type . '/edit?sku=' . $key ;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }

    public static function post_editor_data($type = null, $action = null,  $params = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
//        if (!$type || !$params || !is_array($params)) {
//            throw new Exception('type and parameter object cannot be null');
//        }


        // call the validatio method
        self::validateCall('post_editor_data', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

		$params = array('json' => json_encode($params));

        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/editor/' . $type . '/' . $action  ;
        // call the POST method for the API call
        return $requestor->request('POST-RAW', $url, $params);
    }


    public static function get_order_invoice($order = null, $authToken = null, $user = null) {


//		$params = array();
        // call the validatio method
//        self::validateCall('get_all_options', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array
		$params = array();
        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/orders/invoice/' . $order;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }

    public static function get_all_options($authToken = null, $user = null) {


		$params = array();
        // call the validatio method
//        self::validateCall('get_all_options', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/options/all' ;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }



    public static function get_option($option_name = null, $authToken = null, $user = null) {


//		$params = array();
        // call the validatio method
//        self::validateCall('get_all_options', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array
		$params = array();
        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/options/' . $option_name ;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }


    public static function get_single_order($order = null, $authToken = null, $user = null) {


//		$params = array();
        // call the validatio method
//        self::validateCall('get_all_options', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array
		$params = array();
        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/orders/edit/' . $order ;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }


    public static function get_all_orders($page = null, $sort = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
//        if (!$type || !$params || !is_array($params)) {
//            throw new Exception('type and parameter object cannot be null');
//        }

		$params = array();

        // call the validatio method
        self::validateCall('editor', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/orders/all?page=' . $page . '&sort=' . $sort;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }


    public static function get_shipped_orders($page = null, $sort = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
//        if (!$type || !$params || !is_array($params)) {
//            throw new Exception('type and parameter object cannot be null');
//        }

		$params = array();

        // call the validatio method
        self::validateCall('editor', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/orders/shipped?page=' . $page . '&sort=' . $sort;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }


    public static function get_awaiting_orders($page = null, $sort = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
//        if (!$type || !$params || !is_array($params)) {
//            throw new Exception('type and parameter object cannot be null');
//        }

		$params = array();

        // call the validatio method
        self::validateCall('editor', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/orders/awaiting?page=' . $page . '&sort=' . $sort;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }

    public static function update_order($order = null, $authToken = null, $user = null) {
        // call the validation method
        self::validateCall('editor', $order, $authToken, $user);

        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array
        $requestor = new SureDone_ApiRequestor($authToken, $user);
        $url = 'v1/orders/edit/' . $order['order'];
        $params = array('json' => json_encode($order));

        return $requestor->request('POST-RAW', $url, $params);
    }

    public static function get_packing_orders($page = null, $sort = null, $authToken = null, $user = null) {

        /**  this validation code should be moved to to update_profile * */
//        if (!$type || !$params || !is_array($params)) {
//            throw new Exception('type and parameter object cannot be null');
//        }

		$params = array();

        // call the validatio method
        self::validateCall('editor', $params, $authToken, $user);
        // instantiate the APIRequirestor which calls the API method, receives the JSON response and decodes response to create result array

        $requestor = new SureDone_ApiRequestor($authToken, $user);
		$url = 'v1/orders/packing?page=' . $page . '&sort=' . $sort;
        // call the POST method for the API call
        return $requestor->request('GET', $url, $params);
    }

    /*
     * validateCall
     *
     * The is used to do basic validation of the API call
     *
     * @param String $method - HTTP method (POST or GET) used in the API call
     * @param String $param - array of parameters used as input of the API call
     * @param String $authToken - token returned as a result of 'authentication'
     */

    // @todo - add validation call specific to "authenticate", "get_profile" and "update_profile" in the following method.
    private static function validateCall($method, $params = null, $authToken = null) {
        if ($params && !is_array($params))
            throw new Exception("param object is invalid. It should either be null or should be array of values. \")");
        if ($authToken && !is_string($authToken))
            throw new Exception('authToken is invalid. It should either be null or should be of type String.');
    }

}
