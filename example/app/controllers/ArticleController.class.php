<?php

class ArticleController extends Nova\Controller
{
  const MODEL_NAME = 'Article';

  public function getArticles() {
    $this->articles = Article::fetchMostRecent();
  }

  public function article() {
    $this->getArticles();
    $this->article = Article::fetchById($this->id);
    $this->pageTitle = $this->article->getTitle();
  }

  public function all_articles() {
    $this->getArticles();
    $this->pageTitle = 'Nova Blog Homepage';
    $this->amount = Article::amount();
  }

  public function edit_article() {
    $this->getArticles();
    $set = $this->propertyExists('id');
    if ($set) $this->article = Article::fetchById($this->id);
    $this->pageTitle = 'Nova Blog – Edit Article';
    $this->buttonCaption = $set ? 'Update' : 'Post';
  }

  public function article_operation() {
    $this->modelOperation('Article', $this->action, 'article', 'all_articles');
  }

}
