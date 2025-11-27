<h2>Factures</h2>
<div style="margin-bottom:8px">
        Filtrer :
        <a href="index.php?c=admin&statut=">Toutes</a> |
        <a href="index.php?c=admin&statut=reglee">Réglées</a> |
        <a href="index.php?c=admin&statut=non_reglee">Non réglées</a>
</div>
<?php if (!empty($factures)): ?>
    <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
    <?php if (!empty($_SESSION['admin_error'])): ?>
        <div style="color:#a00;margin-bottom:8px;"><?= htmlspecialchars($_SESSION['admin_error']) ?></div>
        <?php unset($_SESSION['admin_error']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['admin_success'])): ?>
        <div style="color:green;margin-bottom:8px;"><?= htmlspecialchars($_SESSION['admin_success']) ?></div>
        <?php unset($_SESSION['admin_success']); ?>
    <?php endif; ?>
    
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Client</th>
                <th>Montant net</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php
    ?>

        <?php foreach ($factures as $f): ?>
            <?php
                if (is_array($f)) {
                    $idVal = $f['id_facture'] ?? '';
                    $dateVal = $f['date_facture'] ?? '';
                    $clientNom = $f['nom_congressiste'] ?? ($f['congressiste']['nom'] ?? ($f['nom'] ?? ''));
                    $clientPrenom = $f['prenom_congressiste'] ?? ($f['congressiste']['prenom'] ?? ($f['prenom'] ?? ''));
                    $cout_hotel = (float)($f['cout_hotel'] ?? ($f['congressiste']['hotel']['prix'] ?? 0.0));
                    $total_sessions = (float)($f['total_sessions'] ?? 0.0);
                    $total_activites = (float)($f['total_activites'] ?? 0.0);
                    $total_brut = (float)($f['total_brut'] ?? ($cout_hotel + $total_sessions + $total_activites));
                    $montant_acompte = (float)($f['montant_acompte'] ?? (($f['congressiste']['acompte'] ?? 0) ? 100.0 : 0.0));
                    $total_regle = (float)($f['total_regle'] ?? 0.0);
                    $net = (float)($f['net_a_payer'] ?? max(0.0, $total_brut - $montant_acompte - $total_regle));
                    $statut = !empty($f['statut_reglement']) ? 'Réglée' : 'Non réglée';
                } elseif (is_object($f)) {
                    $idVal = isset($f->id_facture) ? $f->id_facture : '';
                    $dateVal = isset($f->date_facture) ? $f->date_facture : '';
                    if (isset($f->nom_congressiste)) {
                        $clientNom = $f->nom_congressiste;
                    } elseif (isset($f->congressiste)) {
                        $c = $f->congressiste;
                        if (is_array($c)) $clientNom = $c['nom'] ?? '';
                        else $clientNom = $c->nom ?? '';
                    } else {
                        $clientNom = $f->nom ?? '';
                    }
                    if (isset($f->prenom_congressiste)) {
                        $clientPrenom = $f->prenom_congressiste;
                    } elseif (isset($f->congressiste)) {
                        $c = $f->congressiste;
                        if (is_array($c)) $clientPrenom = $c['prenom'] ?? '';
                        else $clientPrenom = $c->prenom ?? '';
                    } else {
                        $clientPrenom = $f->prenom ?? '';
                    }
                    if (isset($f->cout_hotel)) {
                        $cout_hotel = (float)$f->cout_hotel;
                    } elseif (isset($f->congressiste)) {
                        $c = $f->congressiste;
                        if (is_array($c)) $cout_hotel = (float)($c['hotel']['prix'] ?? 0.0);
                        else $cout_hotel = (float)($c->hotel->prix ?? 0.0);
                    } else {
                        $cout_hotel = 0.0;
                    }
                    $total_sessions = (float)($f->total_sessions ?? 0.0);
                    $total_activites = (float)($f->total_activites ?? 0.0);
                    $total_brut = (float)($f->total_brut ?? ($cout_hotel + $total_sessions + $total_activites));
                    if (isset($f->montant_acompte)) {
                        $montant_acompte = (float)$f->montant_acompte;
                    } else {
                        $montant_acompte = 0.0;
                        if (isset($f->congressiste)) {
                            $c = $f->congressiste;
                            $ac = is_array($c) ? ($c['acompte'] ?? 0) : ($c->acompte ?? 0);
                            $montant_acompte = $ac ? 100.0 : 0.0;
                        }
                    }
                    $total_regle = (float)($f->total_regle ?? 0.0);
                    $net = (float)($f->net_a_payer ?? max(0.0, $total_brut - $montant_acompte - $total_regle));
                    $statut = !empty($f->statut_reglement) ? 'Réglée' : 'Non réglée';
                } else {
                    $idVal = '';
                    $dateVal = '';
                    $clientNom = '';
                    $clientPrenom = '';
                    $cout_hotel = 0.0;
                    $total_sessions = 0.0;
                    $total_activites = 0.0;
                    $total_brut = 0.0;
                    $montant_acompte = 0.0;
                    $total_regle = 0.0;
                    $net = 0.0;
                    $statut = 'Non réglée';
                }
                $id = htmlspecialchars((string)$idVal);
                $date = htmlspecialchars((string)$dateVal);
                $client = trim((string)$clientNom . ' ' . (string)$clientPrenom);
            ?>
            <tr>
                <td><?= $id ?></td>
                <td><?= $date ?></td>
                <td><?= htmlspecialchars($client) ?></td>
                <td class="right"><?= number_format((float)$net, 2, ',', ' ') ?> €</td>
                <td><?= htmlspecialchars($statut) ?></td>
                <td>
                    <?php $s = isset($_GET['statut']) ? '&statut=' . urlencode($_GET['statut']) : '';?>
                    <a href="index.php?c=facture&a=pdf&id=<?= urlencode($idVal) ?><?= $s ?>" target="_blank">Voir PDF</a>
                    <?php if ($statut === 'Non réglée'): ?>
                        <form method="post" action="index.php?c=admin&a=delete&id=<?= urlencode($idVal) ?>" style="display:inline;margin-left:8px;">
                            <button type="submit" onclick="return confirm('Supprimer la facture #<?= htmlspecialchars($id) ?> ?')">Supprimer</button>
                        </form>
                    <?php else: ?>
                        <span style="color:#777;margin-left:8px;">(déjà réglée)</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucune facture trouvée.</p>
<?php endif; ?>

<h2>Congressistes sans facture</h2>
<?php if (!empty($noInvoicePreviews)): ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Total brut</th><th>Acompte</th><th>Net à payer</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php foreach ($noInvoicePreviews as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p->congressiste->id_congressiste) ?></td>
                <td><?= htmlspecialchars($p->congressiste->nom) ?></td>
                <td><?= htmlspecialchars($p->congressiste->prenom) ?></td>
                <td><?= number_format($p->total_brut, 2, ',', ' ') ?> €</td>
                <td><?= number_format($p->montant_acompte, 2, ',', ' ') ?> €</td>
                <td><?= number_format($p->net_a_payer, 2, ',', ' ') ?> €</td>
                <td>
                    <form method="post" action="index.php?c=admin&a=create&id=<?= urlencode($p->congressiste->id_congressiste) ?>" style="margin:0;">
                        <button type="submit">Créer facture</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tous les congressistes ont une facture.</p>
<?php endif; ?>
