<?php

namespace Nova;

class Path extends Singleton
{
  protected $path;
  protected $ext;

  protected function __construct() {}

  public static function setData($path, $ext) {
    $obj = self::getInstance();
    $obj->path = $path;
    $obj->ext = $ext;
  }

  public static function __callStatic($methodName, $params) {
    $obj = self::getInstance();
    list($filename) = $params;
    $returnAsURL = false;
    if (preg_match('/^(\w+)_url$/', $methodName, $match)) {
      $methodName = $match[1];
      $returnAsURL = true;
    }
    if (isset($obj->path[$methodName])) {
      if (isset($obj->ext[$methodName])) $filename .= $obj->ext[$methodName];
      if ($returnAsURL) {
        return self::url( self::join($obj->path[$methodName], $filename) );
      }
      return self::file( self::join($obj->path[$methodName], $filename) );
    } else {
      throw new \RuntimeException("Unkown path '$methodName' in Path::__callStatic");
    }
  }

  public static function join() {
    $args = func_get_args();
    return join('/', $args);
  }

  public static function file($filename) {
    if (!file_exists($filename)) {
      return false;
    }
    return $filename;
  }

  public static function is_full_url($url) {
    return preg_match('/https?\:\/\//', $url);
  }

  public static function url($url) {
      return self::join($_SERVER['BASE_URI'], $url);
  }

  public static function current_url() {
      return str_replace($_SERVER['BASE_URI'], '', $_SERVER['REQUEST_URI']);
  }

  public static function base_page($url) {
      return explode('/', $url)[1];
  }
}
