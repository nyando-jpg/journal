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
        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-icon svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }
        .article-actions {
            margin-top: 14px;
            display: flex;
            justify-content: flex-end;
            gap: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($article['titre']) ?></h1>

        <nav style="margin-bottom: 20px;">
            <a href="/admin/articles" class="btn">← Retour a la liste</a>
        </nav>

        <article class="article-card">
            <div class="article-meta">
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
            <div class="article-actions">
                <a href="/admin/articles/edit/<?= (int) $article['id'] ?>" class="btn btn-warning btn-icon" title="Modifier" aria-label="Modifier">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18.71-11.04a1.004 1.004 0 0 0 0-1.42l-2.5-2.5a1.004 1.004 0 0 0-1.42 0L15.13 4.95l3.75 3.75 2.83-2.49z"/></svg>
                    <span class="sr-only">Modifier</span>
                </a>
                <a href="/admin/articles/delete/<?= (int) $article['id'] ?>"
                   class="btn btn-danger btn-icon"
                   title="Supprimer"
                   aria-label="Supprimer"
                   onclick="return confirm('Etes-vous sur de vouloir supprimer cet article ?')">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 7h12l-1 14H7L6 7zm3-3h6l1 2h4v2H4V6h4l1-2z"/></svg>
                    <span class="sr-only">Supprimer</span>
                </a>
            </div>
        </article>
    </div>
</body>
</html>
