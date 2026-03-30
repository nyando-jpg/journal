<?php

declare(strict_types=1);

require __DIR__ . '/../config/db.php';

$search = trim((string) ($_GET['q'] ?? ''));
$selectedCategoryId = max(0, (int) ($_GET['category'] ?? 0));
$currentPage = max(1, (int) ($_GET['page'] ?? 1));

$categories = [];
$articles = [];
$dbError = '';

function normalize_front_image_src(string $src): string
{
    $src = trim($src);
    if ($src === '') {
        return '';
    }

    // Already a correct absolute path
    if (strpos($src, '/uploads/') === 0) {
        return $src;
    }
    // Legacy path with /simple-php-login/ prefix - remove it
    if (strpos($src, '/simple-php-login/uploads/') === 0) {
        return str_replace('/simple-php-login/uploads/', '/uploads/', $src);
    }
    // Relative paths - convert to absolute /uploads/
    if (strpos($src, '../../uploads/') === 0) {
        return str_replace('../../uploads/', '/uploads/', $src);
    }
    if (strpos($src, '../uploads/') === 0) {
        return str_replace('../uploads/', '/uploads/', $src);
    }
    if (strpos($src, './uploads/') === 0) {
        return str_replace('./uploads/', '/uploads/', $src);
    }
    if (strpos($src, 'uploads/') === 0) {
        return '/' . $src;
    }

    return $src;
}

function slugify_public_title(string $title): string
{
    $slug = trim($title);
    if ($slug === '') {
        return 'article';
    }

    $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);
    if ($transliterated !== false) {
        $slug = $transliterated;
    }

    $slug = strtolower($slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
    $slug = trim($slug, '-');

    return $slug !== '' ? $slug : 'article';
}

function build_public_article_url(array $article): string
{
    $title = (string) ($article['titre'] ?? 'article');
    return '/Iran/article/' . rawurlencode(slugify_public_title($title)) . '.html';
}

try {
    $pdo = db_connect();

    $catStmt = $pdo->query('SELECT id_categorie, nom_categorie FROM journal_categories ORDER BY nom_categorie ASC');
    $categories = $catStmt->fetchAll();

    $sql = 'SELECT ji.*, ju.nom AS admin_nom, jc.nom_categorie
            FROM journal_info ji
            LEFT JOIN journal_user ju ON ji.id_admin = ju.id_user
            LEFT JOIN journal_categories jc ON ji.id_categorie = jc.id_categorie';

    $where = [];
    if ($search !== '') {
        $where[] = '(ji.titre LIKE :search OR ji.details LIKE :search OR ju.nom LIKE :search OR jc.nom_categorie LIKE :search OR ji.date LIKE :search OR CAST(ji.id AS CHAR) LIKE :search)';
    }
    if ($selectedCategoryId > 0) {
        $where[] = 'ji.id_categorie = :categoryId';
    }
    if (!empty($where)) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY ji.date DESC';

    $stmt = $pdo->prepare($sql);
    if ($search !== '') {
        $stmt->bindValue(':search', '%' . $search . '%');
    }
    if ($selectedCategoryId > 0) {
        $stmt->bindValue(':categoryId', $selectedCategoryId, PDO::PARAM_INT);
    }

    $stmt->execute();
    $articles = $stmt->fetchAll();
} catch (Throwable $e) {
    $dbError = 'Erreur de connexion a la base de donnees.';
}

$articlesWithImage = [];
$articlesWithoutImage = [];

foreach ($articles as $article) {
    $firstImage = null;
    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', (string) ($article['details'] ?? ''), $match) === 1) {
        $firstImage = normalize_front_image_src((string) $match[1]);
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
$totalImagePages = max(1, (int) ceil(count($articlesWithImage) / $imagesPerPage));
$totalNoImagePages = max(1, (int) ceil(count($articlesWithoutImage) / $noImagePerPage));
$totalPages = max($totalImagePages, $totalNoImagePages);
$activePage = min(max(1, $currentPage), $totalPages);

$imageOffset = ($activePage - 1) * $imagesPerPage;
$noImageOffset = ($activePage - 1) * $noImagePerPage;
$pagedArticlesWithImage = array_slice($articlesWithImage, $imageOffset, $imagesPerPage);
$pagedArticlesWithoutImage = array_slice($articlesWithoutImage, $noImageOffset, $noImagePerPage);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualites sur la Guerre en Iran - Journal d'Information</title>
    <meta name="description" content="Suivez les dernieres actualites et analyses sur la situation en Iran.">
    <link rel="canonical" href="http://localhost:8000/simple-php-login/Iran/actualites.html">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Merriweather", Georgia, serif; line-height: 1.8; color: #333; background-color: #f5f5f5; }
        .site-header { background-color: #0f172a; color: #f8fafc; border-bottom: 1px solid rgba(148, 163, 184, 0.35); }
        .header-top { background: #0b1220; border-bottom: 1px solid rgba(148, 163, 184, 0.25); font-size: 0.86rem; }
        .header-top-inner { max-width: 1200px; margin: 0 auto; padding: 8px 20px; display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; color: #e2e8f0; }
        .header-main-inner { max-width: 1200px; margin: 0 auto; padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
        .brand-link { color: #ffffff; text-decoration: none; font-size: 2rem; line-height: 1.1; font-family: "Playfair Display", "Times New Roman", serif; }
        .brand-tagline { margin: 4px 0 0; font-size: 0.95rem; color: #e2e8f0; }
        .header-categories { display: flex; gap: 16px; flex-wrap: wrap; padding-top: 12px; border-top: 1px solid rgba(148, 163, 184, 0.25); }
        .category-link { display: inline-block; color: #ffffff; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
        .category-link:hover, .category-link.active { color: #60a5fa; }
        main { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        main h1 { font-size: 1.8rem; color: #1f2937; margin-bottom: 20px; font-family: "Playfair Display", "Times New Roman", serif; }
        .search-bar { display: flex; gap: 10px; margin: 10px 0 20px 0; align-items: center; flex-wrap: wrap; }
        .search-input { flex: 1; min-width: 260px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { display: inline-block; padding: 8px 16px; margin: 2px; text-decoration: none; border-radius: 4px; cursor: pointer; border: none; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn:hover { opacity: 0.85; }
        .articles-layout { display: grid; grid-template-columns: 2.4fr 0.8fr; gap: 16px; margin-top: 16px; }
        .articles-with-image { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .articles-without-image { display: grid; grid-template-columns: 1fr; gap: 12px; align-content: start; }
        .article-card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; background: #fff; cursor: pointer; transition: box-shadow 0.2s ease, transform 0.2s ease; }
        .article-card:hover { box-shadow: 0 4px 18px rgba(0, 0, 0, 0.12); transform: translateY(-2px); }
        .article-title { margin: 0 0 10px 0; font-size: 1.1rem; color: #1f2937; }
        .article-thumb { width: 100%; height: 180px; object-fit: cover; border-radius: 6px; margin-bottom: 12px; border: 1px solid #ececec; background: #f5f5f5; }
        .article-meta { margin: 4px 0; color: #374151; font-size: 0.93rem; }
        .content-preview { margin-top: 10px; min-height: 60px; max-height: 100px; overflow: hidden; text-overflow: ellipsis; color: #1f2937; }
        .article-card.no-image { border-left: 4px solid #334155; }
        .side-title { margin: 0 0 8px 0; font-size: 1rem; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; }
        .card-read { margin-top: 12px; display: inline-block; color: #1d4ed8; text-decoration: underline; font-weight: 600; min-height: 44px; line-height: 44px; }
        .card-read:hover { text-decoration: none; color: #1e40af; }
        .pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 20px; flex-wrap: wrap; }
        .pagination-link { display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 10px; border: 1px solid #cbd5e1; border-radius: 6px; color: #1e293b; text-decoration: none; background: #fff; font-weight: 600; }
        .pagination-link.active { background: #0f3b8a; border-color: #0f3b8a; color: #fff; }
        .pagination-link.disabled { color: #94a3b8; border-color: #e2e8f0; background: #f8fafc; pointer-events: none; }
        .pagination-dots { color: #64748b; font-weight: 700; padding: 0 2px; }
        .empty-state { text-align: center; padding: 30px; background: white; border-radius: 8px; }
        footer { background-color: #121629; color: #e5e7eb; padding: 38px 20px 20px; margin-top: 50px; }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1.4fr 1fr 1fr; gap: 24px; }
        .footer-title { margin: 0 0 10px 0; font-size: 1rem; color: #ffffff; letter-spacing: 0.04em; text-transform: uppercase; }
        .footer-text { margin: 0; color: #e2e8f0; font-size: 0.95rem; line-height: 1.7; }
        .footer-list { margin: 0; padding: 0; list-style: none; display: grid; gap: 7px; }
        .footer-list a { color: #e2e8f0; text-decoration: underline; font-size: 0.95rem; }
        .footer-bottom { max-width: 1200px; margin: 18px auto 0; padding-top: 16px; border-top: 1px solid rgba(148, 163, 184, 0.35); color: #cbd5e1; font-size: 0.9rem; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        @media (max-width: 1100px) { .articles-layout { grid-template-columns: 1fr; } .articles-without-image { grid-template-columns: repeat(2, minmax(0, 1fr)); } .footer-inner { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 760px) { .articles-with-image, .articles-without-image { grid-template-columns: 1fr; } .footer-inner { grid-template-columns: 1fr; } .footer-bottom { flex-direction: column; align-items: flex-start; } }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <div class="header-top-inner">
                <span>Edition du <?= date('d/m/Y') ?> - Mise a jour continue</span>
                <span>Contact redaction: redaction@journalinfo.fr</span>
            </div>
        </div>
        <div class="header-main-inner">
            <div>
                <a href="/Iran/actualites.html" class="brand-link">Journal d'Information</a>
                <p class="brand-tagline">Analyses, terrain et decryptage geopolitique</p>
            </div>
            <div class="header-categories">
                <?php foreach ($categories as $headerCategory): ?>
                    <a href="/Iran/actualites.html?category=<?= (int) $headerCategory['id_categorie'] ?>" class="category-link <?= $selectedCategoryId === (int) $headerCategory['id_categorie'] ? 'active' : '' ?>">
                        <?= htmlspecialchars((string) $headerCategory['nom_categorie'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <main>
        <h1>Actualites sur la Guerre en Iran</h1>
        <section aria-label="Liste des articles">
            <?php if ($dbError !== ''): ?>
                <div class="empty-state"><p><?= htmlspecialchars($dbError, ENT_QUOTES, 'UTF-8') ?></p></div>
            <?php endif; ?>

            <form action="/Iran/actualites.html" method="GET" class="search-bar">
                <input type="text" name="q" class="search-input" placeholder="Rechercher par titre, contenu, auteur, date ou ID" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
                <select name="category" class="search-input" style="max-width: 260px;">
                    <option value="0">Toutes les categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['id_categorie'] ?>" <?= $selectedCategoryId === (int) $category['id_categorie'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string) $category['nom_categorie'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">Rechercher</button>
                <?php if ($search !== '' || $selectedCategoryId > 0): ?>
                    <a href="/Iran/actualites.html" class="btn">Effacer</a>
                <?php endif; ?>
            </form>

            <?php if (empty($articles)): ?>
                <div class="empty-state">
                    <?php if ($search !== ''): ?>
                        <p>Aucun article trouve pour la recherche "<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>".</p>
                    <?php else: ?>
                        <p>Aucun article publie pour le moment.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="articles-layout">
                    <div>
                        <h3 class="side-title">Articles avec image</h3>
                        <div class="articles-with-image">
                            <?php foreach ($pagedArticlesWithImage as $article): ?>
                                <?php $articleUrl = build_public_article_url($article); ?>
                                <article class="article-card" onclick="window.location.href='<?= htmlspecialchars($articleUrl, ENT_QUOTES, 'UTF-8') ?>'">
                                    <img src="<?= htmlspecialchars((string) $article['first_image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) $article['titre'], ENT_QUOTES, 'UTF-8') ?>" class="article-thumb" loading="lazy" decoding="async" width="400" height="180">
                                    <h2 class="article-title"><?= htmlspecialchars((string) $article['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
                                    <p class="article-meta"><strong>Date:</strong> <?= htmlspecialchars((string) $article['date'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="article-meta"><strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe'), ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="article-meta"><strong>Auteur:</strong> <?= htmlspecialchars((string) ($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])), ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="content-preview"><?= htmlspecialchars(mb_substr(strip_tags((string) $article['details']), 0, 160), ENT_QUOTES, 'UTF-8') ?>...</p>
                                    <a href="<?= htmlspecialchars($articleUrl, ENT_QUOTES, 'UTF-8') ?>" class="card-read" onclick="event.stopPropagation()">Lire la suite</a>
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
                                if ($search !== '') { $prevParams['q'] = $search; }
                                if ($selectedCategoryId > 0) { $prevParams['category'] = $selectedCategoryId; }
                                $prevUrl = '/Iran/actualites.html?' . http_build_query($prevParams);
                                ?>
                                <a href="<?= htmlspecialchars($prevUrl, ENT_QUOTES, 'UTF-8') ?>" class="pagination-link<?= $activePage === 1 ? ' disabled' : '' ?>">&lsaquo;</a>

                                <?php $previousPrinted = null; ?>
                                <?php foreach ($pageSet as $pageNumber): ?>
                                    <?php if ($previousPrinted !== null && $pageNumber > $previousPrinted + 1): ?>
                                        <span class="pagination-dots">...</span>
                                    <?php endif; ?>
                                    <?php
                                    $queryParams = ['page' => $pageNumber];
                                    if ($search !== '') { $queryParams['q'] = $search; }
                                    if ($selectedCategoryId > 0) { $queryParams['category'] = $selectedCategoryId; }
                                    $pageUrl = '/Iran/actualites.html?' . http_build_query($queryParams);
                                    ?>
                                    <a href="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>" class="pagination-link<?= $pageNumber === $activePage ? ' active' : '' ?>" <?= $pageNumber === $activePage ? 'aria-current="page"' : '' ?>><?= $pageNumber ?></a>
                                    <?php $previousPrinted = $pageNumber; ?>
                                <?php endforeach; ?>

                                <?php
                                $nextParams = ['page' => $nextPage];
                                if ($search !== '') { $nextParams['q'] = $search; }
                                if ($selectedCategoryId > 0) { $nextParams['category'] = $selectedCategoryId; }
                                $nextUrl = '/Iran/actualites.html?' . http_build_query($nextParams);
                                ?>
                                <a href="<?= htmlspecialchars($nextUrl, ENT_QUOTES, 'UTF-8') ?>" class="pagination-link<?= $activePage === $totalPages ? ' disabled' : '' ?>">&rsaquo;</a>
                            </nav>
                        <?php endif; ?>
                    </div>

                    <aside>
                        <h3 class="side-title">Articles sans image</h3>
                        <div class="articles-without-image">
                            <?php foreach ($pagedArticlesWithoutImage as $article): ?>
                                <?php $articleUrl = build_public_article_url($article); ?>
                                <article class="article-card no-image" onclick="window.location.href='<?= htmlspecialchars($articleUrl, ENT_QUOTES, 'UTF-8') ?>'">
                                    <h2 class="article-title"><?= htmlspecialchars((string) $article['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
                                    <p class="article-meta"><strong>Date:</strong> <?= htmlspecialchars((string) $article['date'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="article-meta"><strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe'), ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="article-meta"><strong>Auteur:</strong> <?= htmlspecialchars((string) ($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])), ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="content-preview"><?= htmlspecialchars(mb_substr(strip_tags((string) $article['details']), 0, 160), ENT_QUOTES, 'UTF-8') ?>...</p>
                                    <a href="<?= htmlspecialchars($articleUrl, ENT_QUOTES, 'UTF-8') ?>" class="card-read" onclick="event.stopPropagation()">Lire la suite</a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </aside>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-header">
        <div class="footer-inner">
            <div>
                <h3 class="footer-title">Journal d'Information</h3>
                <p class="footer-text">Analyses approfondies et couverture complete de l'actualite geopolitique. Decryptage des enjeux majeurs et perspectives strategiques.</p>
            </div>
            <div>
                <h3 class="footer-title">Categories</h3>
                <ul class="footer-list">
                    <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
                        <li><a href="/Iran/actualites.html?category=<?= (int) $cat['id_categorie'] ?>"><?= htmlspecialchars((string) $cat['nom_categorie'], ENT_QUOTES, 'UTF-8') ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3 class="footer-title">Contact</h3>
                <p class="footer-text"><strong>Email :</strong> redaction@journalinfo.fr</p>
                <p class="footer-text"><strong>Tel :</strong> +33 (0)1 XX XX XX XX</p>
                <p class="footer-text"><strong>Adresse :</strong> Paris, France</p>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> Journal d'Information. Tous droits reserves.</span>
            <div>
                <a href="#" style="color: #e2e8f0; text-decoration: underline; margin-left: 15px;">Mentions legales</a>
                <a href="#" style="color: #e2e8f0; text-decoration: underline; margin-left: 15px;">Politique de confidentialite</a>
            </div>
        </div>
    </footer>
</body>
</html>
