<!-- start new article template -->
    <h1><?= $buttonCaption ?> Article</h1>
    <form id="new-article" method="post" action="<?= isset($article) ? $app->generateRoute('update_article', ['id' => $article->getId()]) : $app->generateRoute('add_article') ?>">
      <label for="title">Title</label>
      <input name="title" type="text" placeholder="Title" value="<?= isset($article) ? $article->getTitle() : '' ?>" />
      <label for="content">Content</label>
      <textarea name="content" form="new-article" placeholder="Content" rows="6" cols="30"><?= isset($article) ? $article->getContent() : '' ?></textarea>
      <input type="submit" value="<?= $buttonCaption ?>"/>
    </form>
    <?php if (isset($article)): ?>
    <form method="post" action="<?= $app->generateRoute('delete_article', ['id' => $article->getId()]) ?>">
      <input type="submit" value="Delete" />
    </form>
    <?php endif; ?>

<!-- end new article template -->
