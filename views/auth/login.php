<h1>Connexion</h1>
<?php if (!empty($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<form method="post" action="index.php?c=auth&a=login">
    <label>Email: <input name="email" type="email" required></label><br>
    <label>Mot de passe: <input name="password" type="password" required></label><br>
    <button>Connexion</button>
</form>
