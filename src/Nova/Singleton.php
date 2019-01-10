<?php

namespace Nova;

abstract class Singleton
{
  static protected $instance = [];

  static public function getInstance() {
    $className = get_called_class();
    if (!isset(self::$instance[$className])) {
      self::$instance[$className] = new $className();
    }
    return self::$instance[$className];
  }

  abstract protected function __construct();
}
