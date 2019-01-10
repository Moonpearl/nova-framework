<?php

namespace Nova;

class Router extends Singleton
{
  public $altorouter;

  public function __construct() {
    $this->altorouter = new \AltoRouter;
  }

  public static function __callStatic($methodName, $params = null) {
    $router = self::getInstance()->altorouter;
    if (!method_exists($router, $methodName)) throw new \RuntimeException("Unknown method '$methodName' in AltoRouter");
    return call_user_func_array( [$router, $methodName], $params);
  }
}
