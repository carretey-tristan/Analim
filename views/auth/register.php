<h1>Inscription</h1>
<?php if (!empty($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<form method="post" action="index.php?c=auth&a=register">
    <label>Nom: <input name="nom" required></label><br>
    <label>Prénom: <input name="prenom" required></label><br>
    <label>Adresse: <input name="adresse"></label><br>
    <label>Email: <input name="email" type="email" required></label><br>
    <label>Mot de passe: <input name="password" type="password" required></label><br>
    <button>Inscription</button>
</form>
