<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article #<?= htmlspecialchars($article['id']) ?> - Admin</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .article-card {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
        }
        .article-meta {
            margin-bottom: 20px;
            color: #666;
        }
        .article-content {
            line-height: 1.7;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            margin: 3px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-size: 15px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-warning {
            background-color: #ffc107;
            color: black;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn:hover {
            opacity: 0.85;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($article['titre']) ?></h1>

        <nav style="margin-bottom: 20px;">
            <a href="/admin/articles" class="btn">← Retour a la liste</a>
            <a href="/admin/articles/edit/<?= (int) $article['id'] ?>" class="btn btn-warning">Modifier</a>
            <a href="/admin/articles/delete/<?= (int) $article['id'] ?>"
               class="btn btn-danger"
               onclick="return confirm('Etes-vous sur de vouloir supprimer cet article ?')">
                Supprimer
            </a>
        </nav>

        <article class="article-card">
            <div class="article-meta">
                <strong>ID:</strong> <?= htmlspecialchars($article['id']) ?><br>
                <strong>ID Admin:</strong> <?= htmlspecialchars($article['id_admin']) ?><br>
                <strong>Auteur:</strong> <?= htmlspecialchars($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])) ?><br>
                <strong>Date:</strong> <?= htmlspecialchars($article['date']) ?>
            </div>
            <div class="article-content">
                <?= preg_replace_callback('/src="([^"]+)"/i', function($m) {
                    $src = $m[1];
                    if (strpos($src, '../../uploads/') === 0) {
                        return 'src="' . str_replace('../../uploads/', '/uploads/', $src) . '"';
                    }
                    return $m[0];
                }, $article['details']) ?>
            </div>
        </article>
    </div>
</body>
</html>
