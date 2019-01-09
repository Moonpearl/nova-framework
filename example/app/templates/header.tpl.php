<!-- start header template -->
    <ul>
      <?php foreach ([
        'home' => 'Home',
        'all_articles' => 'Blog'
      ] as $routeName => $caption): ?>
      <li>
        <a href="<?= $app->generateRoute($routeName) ?>"><?= $caption ?></a>
      </li>
      <?php endforeach; ?>
    </ul>
<!-- end header template -->
