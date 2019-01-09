<?php

class ArticleController extends Nova\Controller
{
  public function article($params) {
    $article = Article::fetchById($params['id']);
    return [
      'pageTitle' => $article->getTitle(),
      'article' => $article,
      'articles' => Article::fetchMostRecent()
    ];
  }

  public function all_articles($params) {
    return [
      'pageTitle' => 'Nova Blog Homepage',
      'articles' => Article::fetchMostRecent()
    ];
  }

  public function new_article() {
    return [
      'pageTitle' => 'Nove Blog – New Article',
      'buttonCaption' => 'Post',
      'articles' => Article::fetchMostRecent()
    ];
  }

  public function add_article() {
    $id = Article::add($_POST);
    $this->redirect('article', ['id' => $id]);
  }

  public function modify_article($params) {
    return [
      'pageTitle' => 'Nove Blog – Modify Article',
      'buttonCaption' => 'Update',
      'article' => Article::fetchById($params['id']),
      'articles' => Article::fetchMostRecent()
    ];
  }

  public function update_article($params) {
    $_POST['date'] = date('Y-m-d H:i:s', time());
    Article::update($params['id'], $_POST);
    $this->redirect('article', ['id' => $params['id']]);
  }

  public function delete_article($params) {
    Article::delete($params['id']);
    $this->redirect('all_articles');
  }
}
