<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['is_admin'])) {
    session_unset();
    header('Location: /admin/login');
    exit;
}

require __DIR__ . '/../config/db.php';

$articleId = max(0, (int) ($_GET['id'] ?? 0));
if ($articleId <= 0) {
    header('Location: /admin?error=notfound');
    exit;
}

try {
    $pdo = db_connect();

    $deleteStmt = $pdo->prepare('DELETE FROM journal_info WHERE id = :id');
    $deleteStmt->bindValue(':id', $articleId, PDO::PARAM_INT);
    $deleteStmt->execute();

    if ($deleteStmt->rowCount() === 0) {
        header('Location: /admin?error=notfound');
        exit;
    }

    header('Location: /admin?success=deleted');
    exit;
} catch (Throwable $e) {
    header('Location: /admin?error=notfound');
    exit;
}
