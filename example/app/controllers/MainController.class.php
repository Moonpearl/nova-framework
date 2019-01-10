<?php

use Nova\Application;
use Nova\HTML;
use Nova\Path;
use Nova\Router;

class MainController extends Nova\Controller
{
  public function home() {
    $this->pageTitle = 'Nova Framework Example Homepage';
  }
}
