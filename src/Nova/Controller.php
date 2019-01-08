<?php

namespace Nova;

class Controller
{
  public function page404() {
    $this->show('main', '404');
  }

  protected function show($viewName, $templateName, $params = []) {
    $app = Application::getInstance();

    // Load all variables in parameters
    foreach ($params as $varName => $value) {
      $$varName = $value;
    }

    // Display view
    include Path::view($viewName);
  }
}
