<?php

namespace Nova;

class Application extends Singleton
{
  const PROJECT_FILE = 'project.ini';

  private $router;
  private $controller;
  private $data;
  private $pdo;

  protected function __construct() {
    // Read project config file
    $this->readProject();
    // Setup classes autoload
    spl_autoload_register(function ($className) {
      // Try and load target class
      $success = $this->loadClass($className);
      // If loader returned failure, interrupt and display error message
      if (!$success) {
        throw new \RuntimeException("Class '$className' could not be found.");
      }
    });
    // Setup database dba_handler
    $this->createPDO();
    // Setup router
    $this->setupRouter();
  }

  private function readProject() {
    $this->data = $this->readConfig(self::PROJECT_FILE);
  }

  private function readViewConfig($name) {
    $this->data = array_merge($this->data, $this->readConfig(Path::viewConfig($name)));
  }

  private function readConfig($filename) {
    return parse_ini_file($filename, true);
  }

  public function loadClass($className) {
    // If class was already loaded, stop and return success
    if (class_exists($className)) {
      return true;
    }
    // Search each directory for target class
    $directories = array_merge([$this->path_controllers, $this->path_models], $this->path_autoload);
    foreach ($directories as $directory) {
      $filename = Path::join($directory, $className . $this->extension_class);
      // If class was found, load it and return success
      if (file_exists($filename)) {
        require_once $filename;
        return true;
      }
    }
    // If class was not found in any directories, return failure
    return false;
  }

  private function setupRouter() {
    $this->router = new \AltoRouter;
    $this->router->setBasePath($_SERVER['BASE_URI']);

    // Read routes config file
    $routes = $this->readConfig($this->data['config']['routes']);
    // Setup routes
    foreach ($routes as $name => $route) {
      $this->router->map(
        $route['verb'],
        $route['url'],
        $route['controller'] . '#' . $route['method'],
        $name
      );
    }
  }

  public function run() {
    $match = $this->router->match();

    if ($match) {
      list($controllerName, $methodName) = explode('#', $match['target']);
    } else {
      $controllerName = 'Controller';
      $methodName = 'page404';
    }

    $this->readViewConfig($methodName);
    $this->controller = new $controllerName;
    $this->controller->$methodName($match['params']);
  }

  public function generateStylesheets() {
    $result = [];
    foreach ($this->stylesheets as $stylesheet) {
      array_push($result, HTML::generateCSSLink(Path::css_url($stylesheet)));
    }
    return join(PHP_EOL, $result);
  }

  public function generateTitle() {
    return HTML::generateTag('title', 'Dummy Title');
  }

  public function __get($varName) {
    $varName = explode('_', $varName);
    switch (sizeof($varName)) {
      case 1:
        $name = $varName[0];
        break;

      case 2:
        list($section, $name) = $varName;
        break;

      default:
        $name = join('_', array_slice($name, 1, -1));
        throw new \RuntimeException("Underscore character not supported in property name '$name' in Project#get");
    }
    if (isset($section)) {
      if (isset($this->data[$section])) {
        if (isset($this->data[$section][$name])) {
          return $this->data[$section][$name];
        } else {
          throw new \RuntimeException("Unknown property '$name' in section '$section' in Project#get");
        }
      } else {
        throw new \RuntimeException("Unknown section '$section' in Project#get");
      }
    } else {
      if (isset($this->data[$name])) {
        return $this->data[$name];
      } else {
        throw new \RuntimeException("Unknown property '$name' in Project#get");
      }
    }
  }

  private function createPDO() {
    $charset = 'utf8';

    $data = $this->readConfig($this->data['config']['database']);

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
    $this->pdo = $pdo;
  }

  public function queryAsArray($query) {
    return self::query($query)->fetchAll();
  }

  public function queryAsObject($query, $className) {
    return self::query($query)->fetchAll(\PDO::FETCH_OBJECT, $className);
  }

  public function query($query) {
    $stmt = $this->$pdo->query($query);
    return $stmt;
  }

}
