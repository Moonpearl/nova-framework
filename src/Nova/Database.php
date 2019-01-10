<?php

namespace Nova;

class Database extends Singleton
{
  private $pdo;

  public function __construct() { }

  public static function __callStatic($methodName, $params = null) {
    $pdo = self::getInstance()->pdo;
    if (!method_exists($pdo, $methodName)) throw new \RuntimeException("Unknown method '$methodName' in PDO");
    return call_user_func_array( [$pdo, $methodName], $params);
  }

  public static function queryAsArray($query) {
    return self::getInstance()->pdo->query($query)->fetchAll();
  }

  public static function queryAsObject($query, $className) {
    return self::getInstance()->pdo->query($query)->fetchAll(\PDO::FETCH_CLASS, $className);
  }

  public static function createHandler($data) {
    $charset = 'utf8';

    $host = $data['host'];
    $db = $data['db'];

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
      \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
      \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
      \PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
      $pdo = new \PDO($dsn, $data['user'], $data['pass'], $options);
    } catch (\PDOException $e) {
      throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    self::getInstance()->pdo = $pdo;
  }
}
