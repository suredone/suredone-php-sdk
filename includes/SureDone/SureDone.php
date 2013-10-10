<?php

/**
 * The class is abstract (cannot be instantiated) to contain the static objects (token, API base and API version)
 * @package    suredone
 * @author     jason nadaf (jason@suredone.com
 * @version    1.0
 */
 
abstract class SureDone
{
  public static $authToken;
  public static $apiBase = 'https://api.suredone.com/';
  public static $apiVersion = null;

  const VERSION = '1';

  public static function getAuthToken()
  {
    return self::$authToken;
  }

  public static function setAuthToken($authToken)
  {
    self::$authToken = $authToken;
  }

  public static function getApiVersion()
  {
    return self::$apiVersion;
  }

  public static function setApiVersion($apiVersion)
  {
    self::$apiVersion = $apiVersion;
  }


}
