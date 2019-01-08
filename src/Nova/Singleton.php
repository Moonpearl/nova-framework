<?php

namespace Nova;

abstract class Singleton
{
  static protected $instance;

  static public function getInstance() {
    $className = get_called_class();
    if (!isset(self::$instance)) {
      self::$instance = new $className();
    }
    return self::$instance;
  }

  abstract protected function __construct();
}
