<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['is_admin'])) {
    session_unset();
    header('Location: login.php');
    exit;
}

$userName = (string) $_SESSION['user_name'];
$isAdmin = (bool) $_SESSION['is_admin'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace utilisateur</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Georgia, serif;
            background: linear-gradient(135deg, #f4f7f8 0%, #e7ecf2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border: 1px solid #d9dee7;
            border-radius: 10px;
            padding: 28px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
        h1 {
            margin: 0 0 16px;
            font-size: 1.5rem;
            color: #1f2937;
        }
        .muted {
            color: #64748b;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        .logout {
            width: 100%;
            border: 0;
            border-radius: 6px;
            padding: 11px;
            background: #111827;
            color: #fff;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .success {
            margin-bottom: 16px;
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #166534;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<div class="card">
    <h1>Espace utilisateur</h1>
    <p class="muted">Connexion reussie.</p>
    <p class="success">
        Connecte en tant que <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
        (<?= $isAdmin ? 'admin' : 'utilisateur' ?>).
    </p>
    <a class="logout" href="logout.php">Se deconnecter</a>
</div>
</body>
</html>
