<?php
// TODO: Complétez la vue d'inscription
?>

<h2>Inscription</h2>
<form method="POST" action="index.php?c=auth&a=register">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required>
    <br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required>
    <br>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>
    <br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>
    <br>

    <label for="profession">Profession :</label>
    <input type="text" id="profession" name="profession">
    <br>

    <label for="ville">Ville :</label>
    <input type="text" id="ville" name="ville">
    <br>

    <button type="submit">S'inscrire</button>
</form>
<?php if (isset($error)): ?>
    <div style="color:red"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

