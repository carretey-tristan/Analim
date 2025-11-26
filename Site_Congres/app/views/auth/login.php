<?php
if (!isset($error)) {
    $error = "";
}
?>
<h2>Connexion</h2>
<form method="POST" action="index.php?c=auth&a=login">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>
    <br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>
    <br>

    <button type="submit">Se connecter</button>
</form>
