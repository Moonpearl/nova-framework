<!-- start header template -->
    <ul>
      <?php foreach ([
        'home' => 'Home',
        'all_articles' => 'Blog'
      ] as $routeName => $caption): ?>
      <li>
        <a href="<?= Nova\Router::generate($routeName) ?>"><?= $caption ?></a>
      </li>
      <?php endforeach; ?>
    </ul>
<!-- end header template -->
