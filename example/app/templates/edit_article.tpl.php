<!-- start new article template -->
    <h1><?= $buttonCaption ?> Article</h1>
    <form id="new-article" method="post" action="<?=
        Nova\Router::generate('article_operation', [
          'action' => 'save',
          'id' => isset($article) ? $article->getId() : null
        ])
        ?>">
      <label for="title">Title</label>
      <input name="title" type="text" placeholder="Title" value="<?= isset($article) ? $article->getTitle() : '' ?>" />
      <label for="content">Content</label>
      <textarea name="content" form="new-article" placeholder="Content" rows="6" cols="30"><?= isset($article) ? $article->getContent() : '' ?></textarea>
      <input type="submit" value="<?= $buttonCaption ?>"/>
      <?php if (isset($article)): ?>
      <input name="id" type="hidden" value="<?= $article->getId() ?>" />
      <?php endif; ?>
    </form>
    <?php if (isset($article)): ?>
    <form method="post" action="<?=
      Nova\Router::generate('article_operation', [
        'action' => 'delete',
        'id' => $article->getId()
      ])
    ?>">
      <input type="submit" value="Delete" />
    </form>
    <?php endif; ?>
<!-- end new article template -->
