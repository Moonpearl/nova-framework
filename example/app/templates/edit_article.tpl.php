<!-- start new article template -->
    <h1><?= $buttonCaption ?> Article</h1>
    <form id="new-article" method="post" action="<?=
        $app->generateRoute('article_operation', [
          'action' => isset($article) ? 'update' : 'add',
          'id' => isset($article) ? $article->getId() : null
        ])
        ?>">
      <label for="title">Title</label>
      <input name="title" type="text" placeholder="Title" value="<?= isset($article) ? $article->getTitle() : '' ?>" />
      <label for="content">Content</label>
      <textarea name="content" form="new-article" placeholder="Content" rows="6" cols="30"><?= isset($article) ? $article->getContent() : '' ?></textarea>
      <input type="submit" value="<?= $buttonCaption ?>"/>
    </form>
    <?php if (isset($article)): ?>
    <form method="post" action="<?=
      $app->generateRoute('article_operation', [
        'action' => 'delete',
        'id' => isset($article) ? $article->getId() : null
      ])
    ?>">
      <input type="submit" value="Delete" />
    </form>
    <?php endif; ?>

<!-- end new article template -->