<!-- article view -->
<!DOCTYPE html>
<html lang="<?= $app->language ?>">
<head>
  <?php include Nova\Path::template('head') ?>
  <?= Nova\HTML::generateStylesheet('article') ?>
</head>
<body>
<div class="wrapper">
  <header>
    <?php include Nova\Path::template('header') ?>
  </header>

  <main>
    <?php include Nova\Path::template($templateName) ?>
  </main>

  <aside>
    <?php include Nova\Path::template('article_sidebar') ?>
  </aside>

  <footer>
    <?php include Nova\Path::template('footer') ?>
  </footer>
</div>
</body>
</html>
