<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Facture <?= htmlspecialchars($factureLocal->id_facture) ?></title>
  <style>
    :root{
      --brand:#2c3e50;
      --accent:#e67e22;
      --muted:#7f8c8d;
      --bg:#f6f8fa;
      --paper:#ffffff;
    }
    @page { margin: 20mm; }
    body{
      font-family: "DejaVu Sans", Arial, Helvetica, sans-serif;
      font-size:13px;
      color:#222;
      background:var(--bg);
      margin:0;
      padding:0;
    }
    .paper{
      max-width:800px;
      margin:0 auto;
      background:var(--paper);
      padding:24px;
      box-shadow:0 2px 6px rgba(0,0,0,0.06);
    }
    header{display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:18px}
    .brand{color:var(--brand)}
    .brand h1{margin:0;font-size:20px;letter-spacing:0.5px}
    .meta{font-size:0.85em;color:var(--muted)}
    .company{max-width:50%}
    .invoice-box{background:linear-gradient(90deg,rgba(230,126,34,0.06),transparent); padding:10px 14px; border-radius:6px; text-align:right}
    .invoice-box h2{margin:0;color:var(--brand);font-size:16px}
    .invoice-meta{font-size:0.85em;color:var(--muted)}
    section.client{margin-bottom:14px}
    table{width:100%; border-collapse:collapse; margin-top:10px}
    thead th{background:var(--brand); color:#fff; padding:10px; text-align:left; font-weight:600; font-size:0.95em}
    tbody td{padding:10px; border-bottom:1px solid #eef2f5}
    tfoot td{padding:8px; border-top:2px solid #e6e9ed}
    .right{ text-align:right }
    .totals td{background:transparent; border:none; padding:6px}
    .totals .label{font-weight:600; color:var(--muted)}
    .totals .value{font-weight:700; color:var(--brand)}
    .small{font-size:0.85em;color:var(--muted)}
    .payee{font-weight:600;color:var(--accent)}
    footer{margin-top:22px; font-size:11px; color:var(--muted); text-align:center}
    .logo{
      display:inline-block;
      width: 150px;;height:70px;
      background:var(--accent);
      color:#fff;
      font-weight:700;
      font-size:18px;
      display:flex; align-items:center; justify-content:center;
      border-radius:4px;
    }
  </style>
</head>
<body>
  <div class="paper">
    <header>
      <div class="company">
        <div class="brand">
          <div style="display:flex; align-items:center; gap:12px;">
            <div class="logo">
              <h1>‎ ‎ ‎ Congrés</h1>
              </div>
              <div class="meta">Adresse société • téléphone • email</div>
            </div>
          </div>
        </div>
        <div style="margin-top:10px" class="small">
          <?= nl2br(htmlspecialchars($factureLocal->congressiste->adresse ?? '')) ?>
        </div>
      </div>

      <div class="invoice-box">
        <h2>FACTURE</h2>
        <div class="invoice-meta small">
          <div>Réf: <?= htmlspecialchars($factureLocal->id_facture) ?></div>
          <div>Date: <?= htmlspecialchars($factureLocal->date_facture ?? date('Y-m-d')) ?></div>
        </div>
      </div>
    </header>

    <section class="client">
      <strong>Titulaire du dossier</strong><br>
      <?= htmlspecialchars($factureLocal->congressiste->nom ?? '') ?> <?= htmlspecialchars($factureLocal->congressiste->prenom ?? '') ?><br>
      <div class="small"><?= nl2br(htmlspecialchars($factureLocal->congressiste->adresse ?? '')) ?></div>
      <div class="small"><?= htmlspecialchars($factureLocal->congressiste->email ?? '') ?></div>
    </section>

    <?php
    $payerIsOrganisme = !empty($factureLocal->organisme_payeur);
    $payerName = $payerIsOrganisme
        ? ($factureLocal->organisme_payeur->nom_organisme ?? 'Organisme payeur')
        : (($factureLocal->congressiste->nom ?? '') . ' ' . ($factureLocal->congressiste->prenom ?? ''));
    $payerAddress = $payerIsOrganisme ? ($factureLocal->organisme_payeur->adresse_organisme ?? '') : ($factureLocal->congressiste->adresse ?? '');
    $payerEmail = $payerIsOrganisme ? ($factureLocal->organisme_payeur->email_organisme ?? '') : ($factureLocal->congressiste->email ?? '');

    $total_brut = $factureLocal->total_brut ?? (
        (float)($factureLocal->cout_hotel ?? 0.0) +
        (float)($factureLocal->total_sessions ?? 0.0) +
        (float)($factureLocal->total_activites ?? 0.0)
    );
    $montant_acompte = $factureLocal->montant_acompte ?? 0.0;
    $total_regle = $factureLocal->total_regle ?? 0.0;
    $net_a_payer = $factureLocal->net_a_payer ?? max(0.0, $total_brut - $montant_acompte - $total_regle);
    ?>

    <section>
      <strong>Payeur</strong><br>
      <div class="payee"><?= htmlspecialchars($payerName) ?></div>
      <div class="small"><?= nl2br(htmlspecialchars($payerAddress)) ?></div>
      <div class="small"><?= htmlspecialchars($payerEmail) ?></div>
      <?php if ($payerIsOrganisme): ?>
        <div class="small" style="margin-top:6px;color:var(--muted)">(Cette facture est à la charge de l'organisme payeur)</div>
      <?php endif; ?>
    </section>

    <table>
      <thead>
        <tr><th>Désignation</th><th class="right">Montant</th></tr>
      </thead>
      <tbody>
        <?php if (!empty($factureLocal->cout_hotel)): ?>
          <tr><td>Hôtel</td><td class="right"><?= number_format((float)$factureLocal->cout_hotel,2,',',' ') ?> €</td></tr>
        <?php endif; ?>
        <tr><td>Sessions (total)</td><td class="right"><?= number_format((float)($factureLocal->total_sessions ?? 0.0),2,',',' ') ?> €</td></tr>
        <tr><td>Activités (total)</td><td class="right"><?= number_format((float)($factureLocal->total_activites ?? 0.0),2,',',' ') ?> €</td></tr>
      </tbody>
      <tfoot>
        <tr class="totals"><td class="label">Montant brut</td><td class="right value"><?= number_format($total_brut,2,',',' ') ?> €</td></tr>
        <tr class="totals"><td class="label">Acompte</td><td class="right"><?= number_format($montant_acompte,2,',',' ') ?> €</td></tr>
        <tr class="totals"><td class="label big">Net à payer</td><td class="right value big"><?= number_format($net_a_payer,2,',',' ') ?> €</td></tr>
        <tr class="totals"><td class="label">Responsable du paiement</td><td class="right"><?= htmlspecialchars($payerName) ?></td></tr>
      </tfoot>
    </table>

    <footer>
      Merci pour votre confiance
    </footer>
  </div>
</body>
</html>