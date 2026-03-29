<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles - Admin</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .articles-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .articles-table th, .articles-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .articles-table th {
            background-color: #333;
            color: white;
        }
        .articles-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .articles-table tbody tr {
            cursor: pointer;
        }
        .articles-table tbody tr:hover {
            background-color: #eef6ff;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 2px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            border: none;
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
            opacity: 0.8;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .content-preview {
            max-width: 400px;
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Articles</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                switch ($_GET['success']) {
                    case 'created':
                        echo 'Article créé avec succès !';
                        break;
                    case 'updated':
                        echo 'Article modifié avec succès !';
                        break;
                    case 'deleted':
                        echo 'Article supprimé avec succès !';
                        break;
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Article non trouvé.
            </div>
        <?php endif; ?>

        <nav style="margin-bottom: 20px;">
            <a href="/pageAdmin" class="btn">← Retour au Dashboard</a>
            <a href="/admin/articles/create" class="btn btn-primary">+ Nouvel Article</a>
        </nav>

        <?php if (empty($articles)): ?>
            <p>Aucun article pour le moment.</p>
        <?php else: ?>
            <table class="articles-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Auteur</th>
                        <th>Aperçu</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr onclick="window.location.href='/admin/articles/view/<?= (int) $article['id'] ?>'">
                            <td><?= htmlspecialchars($article['id']) ?></td>
                            <td><?= htmlspecialchars($article['titre']) ?></td>
                            <td><?= htmlspecialchars($article['date']) ?></td>
                            <td><?= htmlspecialchars($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])) ?></td>
                            <td class="content-preview">
                                <?= mb_substr(strip_tags($article['details']), 0, 100) ?>...
                            </td>
                            <td onclick="event.stopPropagation()">
                                <a href="/admin/articles/edit/<?= $article['id'] ?>" class="btn btn-warning">Modifier</a>
                                <a href="/admin/articles/delete/<?= $article['id'] ?>"
                                   class="btn btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
