<?php

namespace Nova;

class Controller
{
  protected $data;

  public function __construct($data = []) {
    $this->setData($data);
  }

  protected function redirect($routeName, $params = []) {
    $url = Router::generate($routeName, $params);
    header('Location: ' . $url);
    exit();
  }

  protected function modelOperation($className, $action, $redirectOnId = null, $redirectOnNull = null) {
    switch ($action) {
      case 'save':
        $object = new $className($_POST);
        $id = $object->save();
        if (!is_null($redirectOnId)) $this->redirect($redirectOnId, ['id' => $id]);
        return $id;

      case 'delete':
        $className::delete($this->id);
        if (!is_null($redirectOnNull)) $this->redirect($redirectOnNull);
        return null;
    }
  }

  public function page404() {
    $this->pageTitle = 'Page Not Found';
  }

  public function show($viewName, $templateName) {
    $app = Application::getInstance();
    $header = 'header';
    $footer = 'footer';

    // Load all variables in parameters
    foreach ($this->data as $varName => $value) {
      $$varName = $value;
    }

    // Display view
    include Path::view($viewName);
  }

  public function setData($data) {
    $this->data = $data;
    return $this;
  }

  protected function propertyExists($name) {
    return isset($this->data[$name]);
  }

  protected function setProperty($name, $value) {
    $returnValue = isset($this->data[$name]);
    $this->data[$name] = $value;
    return $returnValue;
  }

  protected function getProperty($name) {
    return $this->data[$name];
  }

  public function __get($name) {
    if (isset($this->data[$name])) return $this->data[$name];
    throw new \RuntimeException("Unknown property '$name' in Controller#__get");
  }

  public function __set($name, $value) {
    $returnValue = isset($this->data[$name]);
    $this->data[$name] = $value;
    return $returnValue;
  }
}
