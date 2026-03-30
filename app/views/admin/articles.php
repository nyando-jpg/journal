<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles - Admin</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: "Merriweather", Georgia, serif;
            line-height: 1.8;
            color: #333;
            background-color: #f5f5f5;
        }
        .site-header {
            background-color: #0f172a;
            color: #f8fafc;
            border-bottom: 1px solid rgba(148, 163, 184, 0.35);
        }
        .header-top {
            background: #0b1220;
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
            font-size: 0.86rem;
        }
        .header-top-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 8px 20px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            color: #cbd5e1;
        }
        .header-main-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .brand-link {
            color: #ffffff;
            text-decoration: none;
            font-size: 2rem;
            line-height: 1.1;
            font-family: "Playfair Display", "Times New Roman", serif;
        }
        .brand-tagline {
            margin: 4px 0 0;
            font-size: 0.95rem;
            color: #cbd5e1;
        }
        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        .header-chip {
            border: 1px solid rgba(148, 163, 184, 0.55);
            color: #e2e8f0;
            text-decoration: none;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 0.88rem;
        }
        .header-chip:hover {
            background: rgba(148, 163, 184, 0.2);
        }
        .header-chip-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff !important;
            border: none;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            transition: all 0.3s ease;
        }
        .header-chip-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.6);
            transform: translateY(-2px);
        }
        .header-categories {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 12px;
            border-top: 1px solid rgba(148, 163, 184, 0.25);
            width: 100%;
        }
        .category-link {
            display: inline-block;
            padding: 0;
            background: none;
            color: #ffffff;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border: none;
            transition: color 0.2s ease;
        }
        .category-link:hover,
        .category-link.active {
            color: #60a5fa;
        }
        main {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .page-title {
            margin: 0 0 20px 0;
            font-size: 1.8rem;
            color: #1f2937;
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
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <div class="header-top-inner">
                <span>Espace Administration</span>
                <span>Gestion des contenus et editeurs</span>
            </div>
        </div>

        <div class="header-main-inner">
            <div>
                <a href="/admin/articles?q=&category=0" class="brand-link">Journal d'Information</a>
                <p class="brand-tagline">Espace d'administration</p>
            </div>
            <div class="header-actions">
                <a href="/admin/articles?q=&category=0" class="header-chip">Tous les articles</a>
                <a href="/admin/articles/create" class="header-chip header-chip-primary">➕ Nouvel Article</a>
            </div>
            <div class="header-categories">
                <?php foreach (($categories ?? []) as $headerCategory): ?>
                    <a
                        href="/admin/articles?q=<?= urlencode((string) ($search ?? '')) ?>&category=<?= (int) $headerCategory['id_categorie'] ?>"
                        class="category-link <?= (int) ($selectedCategoryId ?? 0) === (int) $headerCategory['id_categorie'] ? 'active' : '' ?>"
                    >
                        <?= htmlspecialchars((string) $headerCategory['nom_categorie']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <main>
                        <br><br><br><br><br><br><br>

        <h2 class="page-title">Gestion des Articles</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                switch ($_GET['success']) {
                    case 'created':
                        echo 'Article cree avec succes !';
                        break;
                    case 'updated':
                        echo 'Article modifie avec succes !';
                        break;
                    case 'deleted':
                        echo 'Article supprime avec succes !';
                        break;
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Article non trouve.
            </div>
        <?php endif; ?>

        <form action="/admin/articles" method="GET" class="search-bar">
            <input
                type="text"
                name="q"
                class="search-input"
                placeholder="Rechercher par titre, contenu, auteur, date ou ID"
                value="<?= htmlspecialchars((string) ($search ?? '')) ?>"
            >
            <select name="category" class="search-input" style="max-width: 260px;">
                <option value="0">Toutes les categories</option>
                <?php foreach (($categories ?? []) as $category): ?>
                    <option value="<?= (int) $category['id_categorie'] ?>" <?= (int) ($selectedCategoryId ?? 0) === (int) $category['id_categorie'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars((string) $category['nom_categorie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Rechercher</button>
            <?php if (!empty($search) || (int) ($selectedCategoryId ?? 0) > 0): ?>
                <a href="/admin/articles?q=&category=0" class="btn">Effacer</a>
            <?php endif; ?>
        </form>

        <?php if (empty($articles)): ?>
            <?php if (!empty($search)): ?>
                <p>Aucun article trouve pour la recherche "<?= htmlspecialchars((string) $search) ?>".</p>
            <?php else: ?>
                <p>Aucun article pour le moment.</p>
            <?php endif; ?>
        <?php else: ?>
            <?php
            $articlesWithImage = [];
            $articlesWithoutImage = [];

            foreach ($articles as $article) {
                $firstImage = null;
                if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', (string) ($article['details'] ?? ''), $match) === 1) {
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
                                <img src="<?= htmlspecialchars((string) $article['first_image']) ?>" alt="Apercu image de l'article" class="article-thumb">

                                <h2 class="article-title"><?= htmlspecialchars((string) $article['titre']) ?></h2>
                                <p class="article-meta"><strong>Date:</strong> <?= htmlspecialchars((string) $article['date']) ?></p>
                                <p class="article-meta"><strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe')) ?></p>
                                <p class="article-meta"><strong>Auteur:</strong> <?= htmlspecialchars((string) ($article['nom_auteur'] ?? $article['admin_nom'] ?? ('Admin #' . $article['id_admin']))) ?></p>
                                <p class="content-preview">
                                    <?= mb_substr(strip_tags((string) $article['details']), 0, 160) ?>...
                                </p>

                                <div class="card-actions" onclick="event.stopPropagation()">
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
                        <?php endforeach; ?>
                    </div>
                </div>

                <aside>
                    <h3 class="side-title">Articles sans image</h3>
                    <div class="articles-without-image">
                        <?php foreach ($articlesWithoutImage as $article): ?>
                            <article class="article-card no-image" onclick="window.location.href='/admin/articles/view/<?= (int) $article['id'] ?>'">
                                <h2 class="article-title"><?= htmlspecialchars((string) $article['titre']) ?></h2>
                                <p class="article-meta"><strong>Date:</strong> <?= htmlspecialchars((string) $article['date']) ?></p>
                                <p class="article-meta"><strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe')) ?></p>
                                <p class="article-meta"><strong>Auteur:</strong> <?= htmlspecialchars((string) ($article['nom_auteur'] ?? $article['admin_nom'] ?? ('Admin #' . $article['id_admin']))) ?></p>
                                <p class="content-preview">
                                    <?= mb_substr(strip_tags((string) $article['details']), 0, 160) ?>...
                                </p>

                                <div class="card-actions" onclick="event.stopPropagation()">
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
                        <?php endforeach; ?>
                    </div>
                </aside>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
