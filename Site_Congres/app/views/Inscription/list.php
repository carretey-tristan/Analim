<?php
if (!isset($conferences)) {
    $conferences = [];
}

?>

<h2>Liste des conférences</h2>

<?php
if (is_array($conferences) && count($conferences) > 0): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Salle</th>
            <th>Places</th>
            <th>Intervenant</th>
            <th>Horaire</th>
            <th>Séminaire</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($conferences as $conf): ?>
            <tr>
                <td><?= htmlspecialchars($conf['id'] ?? $conf['conference_id'] ?? '') ?></td>
                <td><?= htmlspecialchars($conf['description'] ?? '') ?></td>
                <td><?= htmlspecialchars($conf['salle'] ?? '') ?></td>
                <td><?= htmlspecialchars($conf['nbplaces'] ?? '') ?></td>
                <td>
                    <?php
                    if (isset($conf['intervenant']) && is_array($conf['intervenant'])) {
                        echo htmlspecialchars($conf['intervenant']['nom'] . ' ' . $conf['intervenant']['prenom']);
                    } elseif (isset($conf['intervenant_nom'])) {
                        echo htmlspecialchars($conf['intervenant_nom'] . ' ' . ($conf['intervenant_prenom'] ?? ''));
                    }
                    ?>
                </td>
                <td><?= htmlspecialchars($conf['horaire'] ?? $conf['heure'] ?? '') ?></td>
                <td>
                    <?php
                    if (isset($conf['seminaire']) && is_array($conf['seminaire'])) {
                        echo htmlspecialchars($conf['seminaire']['intitule'] ?? $conf['seminaire']['libelle'] ?? '');
                    } elseif (isset($conf['seminaire_intitule'])) {
                        echo htmlspecialchars($conf['seminaire_intitule']);
                    }
                    ?>
                </td>
                <td>
                    <form method="POST" action="index.php?c=Inscription&a=desinscrire">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                        <input type="hidden" name="conference_id" value="<?= htmlspecialchars($conf['id'] ?? $conf['conference_id'] ?? '') ?>">
                        <button type="submit">désinscrire</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>        
<?php else: ?>
    <p>Aucune conférence trouvée.</p>
<?php endif; ?>
