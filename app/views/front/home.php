<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    <title>Actualités sur la Guerre en Iran - Journal d'Information</title>
    <meta name="description" content="Suivez les dernières actualités et analyses sur la situation en Iran. Articles d'information, reportages et analyses géopolitiques.">
    <meta name="keywords" content="Iran, guerre, actualités, géopolitique, Moyen-Orient, information">
    <meta name="author" content="Journal Info">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Actualités sur la Guerre en Iran">
    <meta property="og:description" content="Suivez les dernières actualités et analyses sur la situation en Iran.">
    <meta property="og:locale" content="fr_FR">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Actualités sur la Guerre en Iran">
    <meta name="twitter:description" content="Suivez les dernières actualités et analyses sur la situation en Iran.">

    <!-- Canonical URL -->
    <link rel="canonical" href="http://localhost:8000/actualites">

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
        header {
            background-color: #1a1a2e;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        nav.main-nav {
            background-color: #16213e;
            padding: 15px 0;
            text-align: center;
        }
        nav.main-nav a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-size: 1rem;
        }
        nav.main-nav a:hover {
            text-decoration: underline;
        }
        main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
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
        .btn:hover {
            opacity: 0.85;
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
        .side-title {
            margin: 0 0 8px 0;
            font-size: 1rem;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .card-read {
            margin-top: 12px;
            display: inline-block;
            color: #0f3b8a;
            text-decoration: none;
            font-weight: 600;
        }
        .card-read:hover {
            text-decoration: underline;
        }
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .pagination-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            color: #1e293b;
            text-decoration: none;
            background: #fff;
            font-weight: 600;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
        }
        .pagination-link:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }
        .pagination-link.active {
            background: #0f3b8a;
            border-color: #0f3b8a;
            color: #fff;
        }
        .pagination-link.disabled {
            color: #94a3b8;
            border-color: #e2e8f0;
            background: #f8fafc;
            pointer-events: none;
            box-shadow: none;
        }
        .pagination-dots {
            color: #64748b;
            font-weight: 700;
            letter-spacing: 0.08em;
            padding: 0 2px;
        }
        .empty-state {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 8px;
        }
        footer {
            background-color: #1a1a2e;
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: 50px;
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
    <header>
        <h1>Journal d'Information</h1>
        <p>Actualités sur la situation en Iran</p>
    </header>

    <nav class="main-nav" role="navigation" aria-label="Navigation principale">
        <a href="/actualites">Accueil</a>
        <a href="/loginAdmin">Administration</a>
    </nav>

    <main>
        <section aria-label="Liste des articles">
            <h2 style="margin-bottom: 30px; font-size: 1.8rem;">Dernières Actualités</h2>

            <form action="/actualites" method="GET" class="search-bar">
                <input
                    type="text"
                    name="q"
                    class="search-input"
                    placeholder="Rechercher par titre, contenu, auteur, date ou ID"
                    value="<?= htmlspecialchars($search ?? '') ?>"
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
                    <a href="/actualites" class="btn">Effacer</a>
                <?php endif; ?>
            </form>

            <?php if (empty($articles)): ?>
                <div class="empty-state">
                    <?php if (!empty($search)): ?>
                        <p>Aucun article trouve pour la recherche "<?= htmlspecialchars($search) ?>".</p>
                    <?php else: ?>
                        <p>Aucun article publie pour le moment.</p>
                    <?php endif; ?>
                </div>
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

                $imagesPerPage = 8;
                $noImagePerPage = 8;

                $totalImageArticles = count($articlesWithImage);
                $totalNoImageArticles = count($articlesWithoutImage);

                $totalImagePages = max(1, (int) ceil($totalImageArticles / $imagesPerPage));
                $totalNoImagePages = max(1, (int) ceil($totalNoImageArticles / $noImagePerPage));
                $totalPages = max($totalImagePages, $totalNoImagePages);

                $activePage = max(1, (int) ($currentPage ?? 1));
                if ($activePage > $totalPages) {
                    $activePage = $totalPages;
                }

                $imageOffset = ($activePage - 1) * $imagesPerPage;
                $noImageOffset = ($activePage - 1) * $noImagePerPage;

                $pagedArticlesWithImage = array_slice($articlesWithImage, $imageOffset, $imagesPerPage);
                $pagedArticlesWithoutImage = array_slice($articlesWithoutImage, $noImageOffset, $noImagePerPage);
                ?>

                <div class="articles-layout">
                    <div>
                        <h3 class="side-title">Articles avec image</h3>
                        <div class="articles-with-image">
                            <?php foreach ($pagedArticlesWithImage as $article): ?>
                                <article class="article-card" onclick="window.location.href='/article/<?= (int) $article['id'] ?>'">
                                    <img src="<?= htmlspecialchars($article['first_image']) ?>" alt="Apercu image de l'article" class="article-thumb">

                                    <h2 class="article-title"><?= htmlspecialchars($article['titre']) ?></h2>
                                    <p class="article-meta"><strong>Date:</strong> <?= htmlspecialchars($article['date']) ?></p>
                                    <p class="article-meta"><strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe')) ?></p>
                                    <p class="article-meta"><strong>Auteur:</strong> <?= htmlspecialchars($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])) ?></p>
                                    <p class="content-preview">
                                        <?= mb_substr(strip_tags($article['details']), 0, 160) ?>...
                                    </p>
                                    <a href="/article/<?= (int) $article['id'] ?>" class="card-read">Lire la suite</a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($totalPages > 1): ?>
                            <nav class="pagination" aria-label="Pagination des articles avec image">
                                <?php
                                $pageSet = [1, $totalPages];
                                for ($i = $activePage - 1; $i <= $activePage + 1; $i++) {
                                    if ($i >= 1 && $i <= $totalPages) {
                                        $pageSet[] = $i;
                                    }
                                }
                                $pageSet = array_values(array_unique($pageSet));
                                sort($pageSet);

                                $prevPage = max(1, $activePage - 1);
                                $nextPage = min($totalPages, $activePage + 1);

                                $prevParams = ['page' => $prevPage];
                                if (!empty($search)) {
                                    $prevParams['q'] = $search;
                                }
                                if ((int) ($selectedCategoryId ?? 0) > 0) {
                                    $prevParams['category'] = (int) $selectedCategoryId;
                                }
                                $prevUrl = '/actualites?' . http_build_query($prevParams);
                                ?>
                                <a
                                    href="<?= htmlspecialchars($prevUrl) ?>"
                                    class="pagination-link<?= $activePage === 1 ? ' disabled' : '' ?>"
                                    aria-label="Page precedente"
                                >
                                    &lsaquo;
                                </a>

                                <?php $previousPrinted = null; ?>
                                <?php foreach ($pageSet as $pageNumber): ?>
                                    <?php if ($previousPrinted !== null && $pageNumber > $previousPrinted + 1): ?>
                                        <span class="pagination-dots" aria-hidden="true">...</span>
                                    <?php endif; ?>
                                    <?php
                                    $queryParams = ['page' => $pageNumber];
                                    if (!empty($search)) {
                                        $queryParams['q'] = $search;
                                    }
                                    if ((int) ($selectedCategoryId ?? 0) > 0) {
                                        $queryParams['category'] = (int) $selectedCategoryId;
                                    }
                                    $pageUrl = '/actualites?' . http_build_query($queryParams);
                                    ?>
                                    <a
                                        href="<?= htmlspecialchars($pageUrl) ?>"
                                        class="pagination-link<?= $pageNumber === $activePage ? ' active' : '' ?>"
                                        <?php if ($pageNumber === $activePage): ?>aria-current="page"<?php endif; ?>
                                    >
                                        <?= $pageNumber ?>
                                    </a>
                                    <?php $previousPrinted = $pageNumber; ?>
                                <?php endforeach; ?>

                                <?php
                                $nextParams = ['page' => $nextPage];
                                if (!empty($search)) {
                                    $nextParams['q'] = $search;
                                }
                                if ((int) ($selectedCategoryId ?? 0) > 0) {
                                    $nextParams['category'] = (int) $selectedCategoryId;
                                }
                                $nextUrl = '/actualites?' . http_build_query($nextParams);
                                ?>
                                <a
                                    href="<?= htmlspecialchars($nextUrl) ?>"
                                    class="pagination-link<?= $activePage === $totalPages ? ' disabled' : '' ?>"
                                    aria-label="Page suivante"
                                >
                                    &rsaquo;
                                </a>
                            </nav>
                        <?php endif; ?>
                    </div>

                    <aside>
                        <h3 class="side-title">Articles sans image</h3>
                        <div class="articles-without-image">
                            <?php foreach ($pagedArticlesWithoutImage as $article): ?>
                                <article class="article-card no-image" onclick="window.location.href='/article/<?= (int) $article['id'] ?>'">
                                    <h2 class="article-title"><?= htmlspecialchars($article['titre']) ?></h2>
                                    <p class="article-meta"><strong>Date:</strong> <?= htmlspecialchars($article['date']) ?></p>
                                    <p class="article-meta"><strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe')) ?></p>
                                    <p class="article-meta"><strong>Auteur:</strong> <?= htmlspecialchars($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])) ?></p>
                                    <p class="content-preview">
                                        <?= mb_substr(strip_tags($article['details']), 0, 160) ?>...
                                    </p>
                                    <a href="/article/<?= (int) $article['id'] ?>" class="card-read">Lire la suite</a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </aside>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Journal d'Information - Tous droits réservés</p>
    </footer>
</body>
</html>
