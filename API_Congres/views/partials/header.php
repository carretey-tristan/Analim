<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav>
  <?php if (!isset($_SESSION['email'])): ?>
    <a href="index.php?c=auth&a=login">Se connecter</a>
    <a href="index.php?c=auth&a=register">S'inscrire</a>
  <?php else: ?>
    <a href="index.php?c=auth&a=logout">Se déconnecter</a>
      <?php if (!empty($_SESSION['is_agent'])): ?>
        <a href="index.php?c=admin">Admin</a>
      <?php endif; ?>
    <a href="index.php?c=account&a=monespace">Bonjour, <?= htmlspecialchars($_SESSION['email']) ?></a>
  <?php endif; ?>
</nav>
