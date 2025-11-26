<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Congrés</title>
  <link rel="stylesheet" href="app/css/style.css">
</head>
<body>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php require __DIR__ . '/partials/header.php'; ?>
<div class="container">
    <?= $content ?? '' ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
