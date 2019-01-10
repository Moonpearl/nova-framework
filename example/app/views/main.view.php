<!-- main view -->
<!DOCTYPE html>
<html lang="<?= $app->language ?>">
<head>
  <?php include Nova\Path::template('head') ?>
  <?= Nova\HTML::generateStylesheet('main') ?>
</head>
<body>
<div class="wrapper">
  <header>
    <?php include Nova\Path::template('header') ?>
  </header>

  <main>
    <?php include Nova\Path::template($templateName) ?>
  </main>

  <footer>
    <?php include Nova\Path::template('footer') ?>
  </footer>
</div>
</body>
</html>
