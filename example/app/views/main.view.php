<!DOCTYPE html>
<html lang="<?= $app->language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?= $app->generateStylesheets() ?>
    <?= $app->generateTitle() ?>
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"> -->
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
