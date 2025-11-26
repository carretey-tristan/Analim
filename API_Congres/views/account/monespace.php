<h1>Mon espace</h1>
<p>Bonjour <?= htmlspecialchars($user->prenom . ' ' . $user->nom) ?></p>

<?php if (empty($factures)) : ?>
    <p>Aucune inscription/facture</p>
<?php else: ?>
    <ul>
    <?php foreach ($factures as $f): ?>
        <li>Facture #<?= htmlspecialchars($f->id_facture) ?> - <?= htmlspecialchars($f->date_facture) ?></li>
        <?php $s = isset($_GET['statut']) ? '&statut=' . urlencode($_GET['statut']) : '';?>
        <a href="index.php?c=facture&a=pdf&id=<?= urlencode($f->id_facture) ?><?= $s ?>" target="_blank">Voir PDF</a>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p><a href="logout.php">Déconnexion</a></p>
