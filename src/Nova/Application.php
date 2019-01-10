<?php

namespace Nova;

class Application extends Singleton
{
  const PROJECT_FILE = 'project.ini';

  private $controller;
  private $data;

  protected function __construct() {
    // Read project config file
    $this->readProject();
    // Setup paths
    Path::setData($this->data['path'], $this->data['extension']);
    // Setup database handler
    Database::createHandler($this->readConfig($this->data['config']['database']));
    // Setup router
    $this->setupRouter();
    // Setup classes autoload
    spl_autoload_register(function ($className) {
      // Try and load target class
      $success = $this->loadClass($className);
      // If loader returned failure, interrupt and display error message
      if (!$success) {
        throw new \RuntimeException("Class '$className' could not be found.");
      }
    });
  }

  static public function run() {
    self::getInstance()->launch();
  }

  private function readProject() {
    $this->data = $this->readConfig(self::PROJECT_FILE);
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
    $directories = ['model', 'controller', 'local'];
    foreach ($directories as $directory) {
      // If class was found, load it and return success
      if ($filename = Path::__callStatic($directory, [$className])) {
        require_once $filename;
        return true;
      }
    }
    // If class was not found in any directories, return failure
    return false;
  }

  private function setupRouter() {
    Router::setBasePath($_SERVER['BASE_URI']);

    // Read routes config file
    $routes = $this->readConfig($this->data['config']['routes']);
    // Setup routes
    foreach ($routes as $name => $route) {
      if (!isset($route['view'])) $route['view'] = null;
      if (!isset($route['template'])) $route['template'] = null;
      Router::map(
        $route['verb'],
        $route['url'],
        [$route['controller'], $name, $route['view'], $route['template']],
        $name
      );
    }
  }

  public function launch() {
    $match = Router::match();

    if ($match) {
      list($controllerName, $methodName, $viewName, $templateName) = $match['target'];
      $params = $match['params'];
    } else {
      $controllerName = 'Nova\Controller';
      $methodName = 'page404';
      $viewName = 'main';
      $templateName = '404';
      $params = [];
    }

    if (!isset($params['pageTitle'])) $params['pageTitle'] = 'pouet';

    // $this->readViewConfig($viewName);
    $this->controller = new $controllerName($params);
    $this->controller->$methodName();
    $this->controller->show($viewName, $templateName);
  }

  public function data($varName) {
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
          throw new \RuntimeException("Unknown property '$name' in section '$section' in Application#get");
        }
      } else {
        throw new \RuntimeException("Unknown section '$section' in Application#get");
      }
    } else {
      if (isset($this->data[$name])) {
        return $this->data[$name];
      } else {
        throw new \RuntimeException("Unknown property '$name' in Application#get");
      }
    }
  }

}
