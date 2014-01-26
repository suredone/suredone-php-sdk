<?php
/**
 * The class facilitates the API calls by doing the following
 * - encoding and decoding of the request objects
 * - validating the request
 * - error handling
 * @package    suredone
 * @author     jason nadaf (jason@suredone.com
 * @version    1.0
 */
class SureDone_ApiRequestor {

    public $authToken;

    public $user;

    public function __construct($authToken = null, $user = null) {
        $this->_authToken = $authToken;
        $this->_user = $user;
    }

    public static function apiUrl($url = '') {
        $apiBase = SureDone::$apiBase;
        return "$apiBase$url";
    }

    public static function utf8($value) {
        if (is_string($value) && mb_detect_encoding($value, "UTF-8", TRUE) != "UTF-8")
            return utf8_encode($value);
        else
            return $value;
    }

    private static function _encodeObjects($d) {
        if ($d instanceof SureDone_ApiResource) {
            return self::utf8($d->id);
        } else if ($d === true) {
            return 'true';
        } else if ($d === false) {
            return 'false';
        } else if (is_array($d)) {
            $res = array();
            foreach ($d as $k => $v)
                $res[$k] = self::_encodeObjects($v);
            return $res;
        } else {
            return self::utf8($d);
        }
    }

    public static function encode($arr, $prefix = null) {
        if (!is_array($arr))
            return $arr;

        $r = array();
        foreach ($arr as $k => $v) {
            if (is_null($v))
                continue;

            if ($prefix && $k && !is_int($k))
                $k = $prefix . "[" . $k . "]";
            else if ($prefix)
                $k = $prefix . "[]";

            if (is_array($v)) {
                $r[] = self::encode($v, $k, true);
            } else {
                $r[] = urlencode($k) . "=" . urlencode($v);
            }
        }

        return implode("&", $r);
    }

    public function request($meth, $url, $params = null, $json_encode = false) {
        if (!$params)
            $params = array();

		list($rbody, $rcode, $myApiKey) = $this->_requestRaw($meth, $url, $params);

    	$resp = $this->_interpretResponse($rbody, $rcode);
		//$resultArray = json_decode($rbody);
		//return $resultArray;
		return $rbody;



    }


  public function handleApiError($rbody, $rcode, $resp)
  {
    if (!is_array($resp) || !isset($resp['error']))
      throw new SureDone_ApiError("Invalid response object from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody, $resp);
    $error = $resp['error'];
    switch ($rcode) {
    case 400:
    case 404:
      throw new SureDone_InvalidRequestError(isset($error['message']) ? $error['message'] : null,
                                           isset($error['param']) ? $error['param'] : null,
                                           $rcode, $rbody, $resp);
    case 401:
      throw new SureDone_AuthenticationError(isset($error['message']) ? $error['message'] : null, $rcode, $rbody, $resp);
    default:
      throw new SureDone_ApiError(isset($error['message']) ? $error['message'] : null, $rcode, $rbody, $resp);
    }
  }

    private function _requestRaw2($meth, $url, $params, $json_encode = false) {

		$myauthToken = "";
		// token is not needed for auth request
		if ( !self::endsWith ($url,"auth") ) {
			$myauthToken = $this->_authToken;
			if (!$myauthToken)
				$myauthToken = SureDone::$authToken;
			if (!$myauthToken)
				throw new Exception('No Auth Token provided.');
		}

        $absUrl = $this->apiUrl($url);
        $params = self::_encodeObjects($params);
        $langVersion = phpversion();
        $uname = php_uname();
        $ua = array('bindings_version' => SureDone::VERSION,
            'lang' => 'php',
            'lang_version' => $langVersion,
            'publisher' => 'SureDone',
            'uname' => $uname);
		if ( !self::endsWith ($url,"auth") ) {
        $headers =
                    'Content-Type: multipart/form-data' . PHP_EOL .
                    'X-Auth-User: ' . $this->_user  . ' ' . PHP_EOL .
                    'X-Auth-Token: ' . $myauthToken . '' ;
		} else {
        $headers =
                    'Content-Type:application/x-www-form-urlencoded';
		}

		if ( $json_encode ) {
		$request_content = json_encode($params);
		} else {
		$request_content = http_build_query($params);
		}

		$options = array(
			'http' => array(
				'header'  => $headers,
				'method'  => $meth,
				'content' => $request_content,
			),
		);
		$context  = stream_context_create($options);

		try {
				$result = file_get_contents($absUrl, false, $context);
		}
		catch (ErrorException $e) {
			throw new SureDone_ApiConnectionError($e->getMessage());
		}
		$resultArray = json_decode($result);
		return $resultArray;
    }


  private function _requestRaw($meth, $url, $params)
  {

		$myauthToken = "";
		// token is not needed for auth request
		if ( !self::endsWith ($url,"auth") ) {
			$myauthToken = $this->_authToken;
			if (!$myauthToken)
				$myauthToken = SureDone::$authToken;
			if (!$myauthToken)
				throw new Exception('No Auth Token provided.');
		}

    $absUrl = $this->apiUrl($url);
    $params = self::_encodeObjects($params);
    $langVersion = phpversion();
    $uname = php_uname();
    $ua = array('bindings_version' => SureDone::VERSION,
		'lang' => 'php',
		'lang_version' => $langVersion,
		'publisher' => 'stripe',
		'uname' => $uname);


	if ( !self::endsWith ($url,"auth") ) {
	$headers =
				array('Content-Type: multipart/form-data',
				'X-Auth-User: ' . $this->_user  . ' ',
				'X-Auth-Token: ' . $myauthToken . '');
	} else {
	$headers =
				array('Content-Type:application/x-www-form-urlencoded');
	}

    list($rbody, $rcode) = $this->_curlRequest($meth, $absUrl, $headers, $params);


	return array($rbody, $rcode, $myauthToken);
  }

	private function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}

  private function _interpretResponse($rbody, $rcode)
  {
    try {
      $resp = json_decode($rbody, true);
    } catch (Exception $e) {
      throw new SureDone_ApiError("Invalid response body from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody);
    }

    if ($rcode < 200 || $rcode >= 300) {
      $this->handleApiError($rbody, $rcode, $resp);
    }
    return $resp;
  }

  private function _curlRequest($meth, $absUrl, $headers, $params)
  {
    $curl = curl_init();
    $meth = strtolower($meth);
    $opts = array();
    if ($meth == 'get') {
      $opts[CURLOPT_HTTPGET] = 1;
      if (count($params) > 0) {
	$encoded = self::encode($params);
	$absUrl = "$absUrl?$encoded";
      }
    } else if ($meth == 'post') {
      $opts[CURLOPT_POST] = 1;
      $opts[CURLOPT_POSTFIELDS] = self::encode($params);
    } else if ($meth == 'post-raw') {
      $opts[CURLOPT_POST] = 1;
      $opts[CURLOPT_POSTFIELDS] = $params;
    } else if ($meth == 'delete')  {
      $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
      if (count($params) > 0) {
	$encoded = self::encode($params);
	$absUrl = "$absUrl?$encoded";
      }
    } else {
      throw new SureDone_ApiError("Unrecognized method $meth");
    }

    $absUrl = self::utf8($absUrl);
    $opts[CURLOPT_URL] = $absUrl;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_CONNECTTIMEOUT] = 30;
    $opts[CURLOPT_TIMEOUT] = 80;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_HTTPHEADER] = $headers;

    $opts[CURLOPT_SSL_VERIFYPEER] = false;

    curl_setopt_array($curl, $opts);
    $rbody = curl_exec($curl);

    $errno = curl_errno($curl);
    if ($errno == CURLE_SSL_CACERT ||
	$errno == CURLE_SSL_PEER_CERTIFICATE ||
	$errno == 77 // CURLE_SSL_CACERT_BADFILE (constant not defined in PHP though)
	) {
      array_push($headers, 'X-SureDone-Client-Info: {"ca":"using SureDone-supplied CA bundle"}');
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl, CURLOPT_CAINFO,
                  dirname(__FILE__) . '/../data/ca-certificates.crt');
      $rbody = curl_exec($curl);
    }

    if ($rbody === false) {
      $errno = curl_errno($curl);
      $message = curl_error($curl);
      curl_close($curl);
      $this->handleCurlError($errno, $message);
    }

    $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return array($rbody, $rcode);
  }

  public function handleCurlError($errno, $message)
  {
    $apiBase = SureDone::$apiBase;
    switch ($errno) {
    case CURLE_COULDNT_CONNECT:
    case CURLE_COULDNT_RESOLVE_HOST:
    case CURLE_OPERATION_TIMEOUTED:
      $msg = "Could not connect to SureDone ($apiBase).  Please check your internet connection and try again.  If this problem persists, you should check SureDone's service status at https://twitter.com/stripestatus, or let us know at support@stripe.com.";
      break;
    case CURLE_SSL_CACERT:
    case CURLE_SSL_PEER_CERTIFICATE:
      $msg = "Could not verify SureDone's SSL certificate.  Please make sure that your network is not intercepting certificates.  (Try going to $apiBase in your browser.)  If this problem persists, let us know at support@stripe.com.";
      break;
    default:
      $msg = "Unexpected error communicating with SureDone.  If this problem persists, let us know at support@stripe.com.";
    }

    $msg .= "\n\n(Network error [errno $errno]: $message)";
    throw new SureDone_ApiConnectionError($msg);
  }
}
