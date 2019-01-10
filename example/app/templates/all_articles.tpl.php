<!-- start main template -->
    <h1>All Articles</h1>
    <p>Displays all articles</p>
    <p><?= $amount ?> articles currently published.</p>
    <a href="<?= Nova\Router::generate('edit_article') ?>">
      Post new article
    </a>
    <section>
    <?php foreach($articles as $article): ?>
    <article>
      <a href="<?= Nova\Router::generate('article', ['id' => $article->getId()]) ?>">
        <h2><?= $article->getTitle() ?></h2>
      </a>
      <p><?= $article->getContent() ?></p>
    </article>
    <?php endforeach; ?>
    </section>
<!-- end main template -->
