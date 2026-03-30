<?php

declare(strict_types=1);

session_start();

if (isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['is_admin'])) {
    header('Location: index.php');
    exit;
}

require __DIR__ . '/../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $password === '') {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        try {
            $pdo = db_connect();
            $stmt = $pdo->prepare('SELECT id_user, nom, mdp, is_admin FROM journal_user WHERE nom = :nom LIMIT 1');
            $stmt->execute(['nom' => $name]);
            $user = $stmt->fetch();

            if ($user && (string) $user['mdp'] === $password) {
                $_SESSION['user_id'] = (int) $user['id_user'];
                $_SESSION['user_name'] = (string) $user['nom'];
                $_SESSION['is_admin'] = (int) $user['is_admin'] === 1;

                header('Location: index.php');
                exit;
            }

            $error = 'Identifiants invalides.';
        } catch (Throwable $e) {
            $error = 'Erreur de connexion a la base de donnees.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Journal Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Merriweather", Georgia, serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .site-header { background-color: #0f172a; color: #f8fafc; }
        .header-top {
            background: #0b1220;
            border-bottom: 1px solid rgba(148,163,184,0.25);
            padding: 8px 20px;
            font-size: 0.82rem;
            color: #cbd5e1;
            display: flex;
            justify-content: space-between;
        }
        .header-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 18px 20px 14px;
        }
        .brand-name {
            font-family: "Playfair Display", "Times New Roman", serif;
            font-size: 1.9rem;
            color: #fff;
            font-weight: 400;
            text-decoration: none;
            display: block;
        }
        .brand-tagline { font-size: 0.88rem; color: #94a3b8; margin-top: 3px; }

        .login-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 20px;
        }
        .login-card {
            background: #fff;
            border: 1px solid #dde1e7;
            border-radius: 10px;
            padding: 36px 40px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 2px 12px rgba(15,23,42,0.08);
        }
        .login-badge {
            display: inline-block;
            background: #0f172a;
            color: #cbd5e1;
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 4px;
            margin-bottom: 14px;
        }
        .login-title {
            font-family: "Playfair Display", "Times New Roman", serif;
            font-size: 1.45rem;
            font-weight: 400;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .login-subtitle { font-size: 0.88rem; color: #64748b; margin-bottom: 28px; }

        .error-msg {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 0.88rem;
            margin-bottom: 18px;
        }
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .form-input {
            width: 100%;
            padding: 10px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.95rem;
            font-family: Georgia, serif;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .form-input:focus {
            border-color: #0f3b8a;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(15,59,138,0.10);
        }
        .form-divider { height: 1px; background: #e2e8f0; margin: 24px 0; }
        .btn-login {
            width: 100%;
            padding: 11px;
            background: #0f172a;
            color: #f8fafc;
            border: none;
            border-radius: 6px;
            font-size: 0.97rem;
            font-family: Georgia, serif;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.03em;
            transition: background 0.2s;
        }
        .btn-login:hover { background: #1e3a5f; }
        .login-footer-note {
            margin-top: 20px;
            text-align: center;
            font-size: 0.82rem;
            color: #94a3b8;
        }

        footer {
            background: #121629;
            color: #94a3b8;
            text-align: center;
            padding: 14px 20px;
            font-size: 0.82rem;
            border-top: 1px solid rgba(148,163,184,0.2);
        }
    </style>
</head>
<body>

    <header class="site-header">
        <div class="header-top">
            <span>Edition du <?= date('d/m/Y') ?> - Mise a jour continue</span>
            <span>Contact redaction : redaction@journalinfo.fr</span>
        </div>
        <div class="header-main">
            <a href="login.php" class="brand-name">Journal d'Information</a>
            <p class="brand-tagline">Analyses, terrain et decryptage geopolitique</p>
        </div>
    </header>

    <div class="login-body">
        <div class="login-card">
            <span class="login-badge">Administration</span>
            <h1 class="login-title">Connexion a la redaction</h1>
            <p class="login-subtitle">Acces reserve aux membres de l'equipe editoriale.</p>

            <?php if ($error !== ''): ?>
                <div class="error-msg">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label class="form-label" for="name">Nom d'utilisateur</label>
                    <input type="text" name="name" id="name" class="form-input"
                           value="Admin" placeholder="Votre identifiant" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-input"
                           value="adminpass" placeholder="********" required>
                </div>

                <div class="form-divider"></div>

                <button type="submit" class="btn-login">Se connecter</button>
            </form>

            <p class="login-footer-note">Acces securise - Journaux d'acces actives</p>
        </div>
    </div>

    <footer>
        &copy; <?= date('Y') ?> Journal d'Information. Tous droits reserves. &nbsp;|&nbsp; Mentions legales
    </footer>

</body>
</html>
