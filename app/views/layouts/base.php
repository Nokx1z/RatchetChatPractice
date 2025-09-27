<?php
$title = $title ?? 'App';
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
  <header class="app-header">
    <div class="container">
      <h1>Ratchet Chat</h1>
      <nav>
        <?php if (!empty($_SESSION['user'])): ?>
          <form method="post" action="/logout">
            <button type="submit" class="btn">Salir</button>
          </form>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <main class="container">
    <?= $content ?? '' ?>
  </main>
  <script>window.AUTH_USER = <?= json_encode($_SESSION['user'] ?? null) ?>;</script>
  <script src="/assets/js/chat.js"></script>
</body>
</html>
