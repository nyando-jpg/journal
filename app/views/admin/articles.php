<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles - Admin</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .articles-layout {
            display: grid;
            grid-template-columns: 2.4fr 0.8fr;
            gap: 16px;
            margin-top: 16px;
        }
        .articles-with-image {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .articles-without-image {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            align-content: start;
        }
        .article-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            background: #fff;
            cursor: pointer;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .article-card:hover {
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }
        .article-title {
            margin: 0 0 10px 0;
            font-size: 1.1rem;
            color: #1f2937;
        }
        .article-thumb {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 12px;
            border: 1px solid #ececec;
            background: #f5f5f5;
        }
        .article-meta {
            margin: 4px 0;
            color: #555;
            font-size: 0.93rem;
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
            margin-top: 10px;
            min-height: 60px;
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #333;
        }
        .article-card.no-image {
            border-left: 4px solid #334155;
        }
        .card-actions {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
            gap: 6px;
        }
        .side-title {
            margin: 0 0 8px 0;
            font-size: 1rem;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        @media (max-width: 1100px) {
            .articles-layout {
                grid-template-columns: 1fr;
            }
            .articles-without-image {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 760px) {
            .articles-with-image,
            .articles-without-image {
                grid-template-columns: 1fr;
            }
        }
        .search-bar {
            display: flex;
            gap: 10px;
            margin: 10px 0 20px 0;
            align-items: center;
            flex-wrap: wrap;
        }
        .search-input {
            flex: 1;
            min-width: 260px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
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

        <form action="/admin/articles" method="GET" class="search-bar">
            <input
                type="text"
                name="q"
                class="search-input"
                placeholder="Rechercher par titre, contenu, auteur, date ou ID"
                value="<?= htmlspecialchars($search ?? '') ?>"
            >
            <button type="submit" class="btn btn-primary">Rechercher</button>
            <?php if (!empty($search)): ?>
                <a href="/admin/articles" class="btn">Effacer</a>
            <?php endif; ?>
        </form>

        <?php if (empty($articles)): ?>
            <?php if (!empty($search)): ?>
                <p>Aucun article trouvé pour la recherche "<?= htmlspecialchars($search) ?>".</p>
            <?php else: ?>
                <p>Aucun article pour le moment.</p>
            <?php endif; ?>
        <?php else: ?>
            <?php
            $articlesWithImage = [];
            $articlesWithoutImage = [];

            foreach ($articles as $article) {
                $firstImage = null;
                if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $article['details'], $match) === 1) {
                    $firstImage = $match[1];
                    if (strpos($firstImage, '../../uploads/') === 0) {
                        $firstImage = str_replace('../../uploads/', '/uploads/', $firstImage);
                    }
                }

                if (!empty($firstImage)) {
                    $article['first_image'] = $firstImage;
                    $articlesWithImage[] = $article;
                } else {
                    $articlesWithoutImage[] = $article;
                }
            }
            ?>

            <div class="articles-layout">
                <div>
                    <h3 class="side-title">Articles avec image</h3>
                    <div class="articles-with-image">
                        <?php foreach ($articlesWithImage as $article): ?>
                            <article class="article-card" onclick="window.location.href='/admin/articles/view/<?= (int) $article['id'] ?>'">
                                <img src="<?= htmlspecialchars($article['first_image']) ?>" alt="Aperçu image de l'article" class="article-thumb">

                                <h2 class="article-title"><?= htmlspecialchars($article['titre']) ?></h2>
                                <p class="article-meta"><strong>Date:</strong> <?= htmlspecialchars($article['date']) ?></p>
                                <p class="article-meta"><strong>Auteur:</strong> <?= htmlspecialchars($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])) ?></p>
                                <p class="content-preview">
                                    <?= mb_substr(strip_tags($article['details']), 0, 160) ?>...
                                </p>

                                <div class="card-actions" onclick="event.stopPropagation()">
                                    <a href="/admin/articles/edit/<?= $article['id'] ?>" class="btn btn-warning btn-icon" title="Modifier" aria-label="Modifier">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18.71-11.04a1.004 1.004 0 0 0 0-1.42l-2.5-2.5a1.004 1.004 0 0 0-1.42 0L15.13 4.95l3.75 3.75 2.83-2.49z"/></svg>
                                        <span class="sr-only">Modifier</span>
                                    </a>
                                    <a href="/admin/articles/delete/<?= $article['id'] ?>"
                                       class="btn btn-danger btn-icon"
                                       title="Supprimer"
                                       aria-label="Supprimer"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 7h12l-1 14H7L6 7zm3-3h6l1 2h4v2H4V6h4l1-2z"/></svg>
                                        <span class="sr-only">Supprimer</span>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <aside>
                    <h3 class="side-title">Articles sans image</h3>
                    <div class="articles-without-image">
                        <?php foreach ($articlesWithoutImage as $article): ?>
                            <article class="article-card no-image" onclick="window.location.href='/admin/articles/view/<?= (int) $article['id'] ?>'">
                                <h2 class="article-title"><?= htmlspecialchars($article['titre']) ?></h2>
                                <p class="article-meta"><strong>Date:</strong> <?= htmlspecialchars($article['date']) ?></p>
                                <p class="article-meta"><strong>Auteur:</strong> <?= htmlspecialchars($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])) ?></p>
                                <p class="content-preview">
                                    <?= mb_substr(strip_tags($article['details']), 0, 160) ?>...
                                </p>

                                <div class="card-actions" onclick="event.stopPropagation()">
                                    <a href="/admin/articles/edit/<?= $article['id'] ?>" class="btn btn-warning btn-icon" title="Modifier" aria-label="Modifier">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18.71-11.04a1.004 1.004 0 0 0 0-1.42l-2.5-2.5a1.004 1.004 0 0 0-1.42 0L15.13 4.95l3.75 3.75 2.83-2.49z"/></svg>
                                        <span class="sr-only">Modifier</span>
                                    </a>
                                    <a href="/admin/articles/delete/<?= $article['id'] ?>"
                                       class="btn btn-danger btn-icon"
                                       title="Supprimer"
                                       aria-label="Supprimer"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 7h12l-1 14H7L6 7zm3-3h6l1 2h4v2H4V6h4l1-2z"/></svg>
                                        <span class="sr-only">Supprimer</span>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </aside>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
