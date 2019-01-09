<!-- start main template -->
    <h1><?= $article->getTitle() ?></h1>
    <p><?= $article->getContent() ?></p>
    <a href="<?= $app->generateRoute('all_articles') ?>">
      Back
    </a>
    <div>
      <a href="<?= $app->generateRoute('modify_article', ['id' => $article->getId()]) ?>">
        Modify
      </a>
    </div>
<!-- end main template -->
