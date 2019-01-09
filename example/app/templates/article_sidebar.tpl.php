<!-- start article sidebar template -->
    <?php foreach($articles as $article): ?>
    <article>
      <a href="<?= $app->generateRoute('article', ['id' => $article->getId()]) ?>">
        <p class="title"><?= $article->getTitle() ?></p>
      </a>
    </article>
    <?php endforeach; ?>
<!-- end article sidebar template -->
