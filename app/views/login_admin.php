<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <title>Connexion - Journal Admin</title>
</head>
<body>
    <div class="container">
        <h1>Connexion Administration - Journal</h1>
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
        <form action="login_admin" method="POST">
            <label for="name">Nom d'utilisateur:</label>
            <input type="text" name="name" id="name" value="Admin" required>
            
            <label for="password">Mot de passe:</label>
            <input type="password" name="password" id="password" value="adminpass" required>
            
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
