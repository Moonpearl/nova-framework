<!-- start main template -->
    <h1><?= $article->getTitle() ?></h1>
    <p class="author">Posted by <?= $author->getName() ?> on <?= $article->getDate() ?></p>
    <p><?= $article->getContent() ?></p>
    <a href="<?= Nova\Router::generate('all_articles') ?>">
      Back
    </a>
    <div>
      <a href="<?= Nova\Router::generate('edit_article', ['id' => $article->getId()]) ?>">
        Modify
      </a>
    </div>
<!-- end main template -->
