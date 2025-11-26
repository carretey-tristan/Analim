<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Séminaire</title>
  <link rel="stylesheet" href="app/css/style.css">
</head>
<body>
<nav>
  <a href="index.php?c=conference&a=list">Voir les conférences</a> 
  <?php if (!isset($_SESSION['email'])): ?>
    <a href="index.php?c=auth&a=login">Se connecter</a> 
    <a href="index.php?c=auth&a=register">S'inscrire</a> 
  
  <?php else: ?>
    <a href="index.php?c=auth&a=logout">Se déconnecter</a> 
    <a href="index.php?c=Inscription&a=list">Bonjour, <?= $_SESSION['email'] ?></a>
  <?php endif; ?>
</nav>
</nav>
<div class="container">
    <?php
      $controller = $_GET['c'] ?? 'home';
      $action = $_GET['a'] ?? 'index';
      $viewFile = __DIR__ . "/$controller/$action.php";
      if (file_exists($viewFile)) include $viewFile;
    ?>
</div>
</body>
</html>