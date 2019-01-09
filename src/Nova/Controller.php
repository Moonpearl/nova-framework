<?php

namespace Nova;

class Controller
{
  protected function redirect($routeName, $params = []) {
    $url = Application::getInstance()->generateRoute($routeName, $params);
    header('Location: ' . $url);
    exit();
  }

  public function page404() {
    return [
      'pageTitle' => 'Page Not Found'
    ];
  }

  public function show($viewName, $templateName, $params = []) {
    $app = Application::getInstance();
    $header = 'header';
    $footer = 'footer';

    // Load all variables in parameters
    foreach ($params as $varName => $value) {
      $$varName = $value;
    }

    // Display view
    include Path::view($viewName);
  }
}
