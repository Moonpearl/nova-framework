<?php

namespace Nova;

class Path
{
  public static function join() {
    $args = func_get_args();
    return join('/', $args);
  }

  public static function file($filename) {
    if (!file_exists($filename)) {
      throw new \RuntimeException("File not found: $filename");
    }
    return $filename;
  }

  public static function view($name) {
    $app = Application::getInstance();
    return self::file( self::join($app->path_views, $name . $app->extension_view) );
  }

  public static function viewConfig($name) {
    $app = Application::getInstance();
    return self::file( self::join($app->path_views, $name . $app->extension_config) );
  }

  public static function template($name) {
    $app = Application::getInstance();
    return self::file( self::join($app->path_templates, $name . $app->extension_template) );
  }

  public static function url($url) {
      return self::join($_SERVER['BASE_URI'], $url);
  }

  public static function css_url($url) {
    $app = Application::getInstance();
    return self::url( self::join($app->path_css, $url . '.css') );
    // return self::join($app->path_css, $url . '.css');
  }

  public static function current_url() {
      return str_replace($_SERVER['BASE_URI'], '', $_SERVER['REQUEST_URI']);
  }

  public static function base_page($url) {
      return explode('/', $url)[1];
  }
}
