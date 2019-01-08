<?php

class MainController extends Nova\Controller
{
  public function home() {
    $this->show('main', 'home');
  }

  public function article($params) {
    // If an ID was caught, display that article only
    if (isset($params['id'])) {
      // Retrieve only desired article from database
      $params['article'] = Article::fetchById($params['id']);
      // Render page
      $this->show('main', 'article', $params);
    // Otherwise, display a page with all articles
    } else {
      // Retrieve all articles from database
      $params['articles'] = Article::fetchAll();
      // Render page
      $this->show('main', 'all_articles', $params);
    }
  }
}
