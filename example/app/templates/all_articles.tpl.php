<!-- start main template -->
    <h1>All Articles</h1>
    <p>Displays all articles</p>
    <a href="<?= $app->generateRoute('new_article') ?>">
      Post new article
    </a>
    <section>
    <?php foreach($articles as $article): ?>
    <article>
      <a href="<?= $app->generateRoute('article', ['id' => $article->getId()]) ?>">
        <h2><?= $article->getTItle() ?></h2>
      </a>
      <p><?= $article->getContent() ?></p>
    </article>
    <?php endforeach; ?>
    </section>
<!-- end main template -->
