<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['is_admin'])) {
    session_unset();
    header('Location: /admin/login');
    exit;
}

require __DIR__ . '/../config/db.php';

$search = trim((string) ($_GET['q'] ?? ''));
$selectedCategoryId = max(0, (int) ($_GET['category'] ?? 0));

$categories = [];
$articles = [];
$dbError = '';

try {
    $pdo = db_connect();

    $catStmt = $pdo->query('SELECT id_categorie, nom_categorie FROM journal_categories ORDER BY nom_categorie ASC');
    $categories = $catStmt->fetchAll();

    $sql = 'SELECT ji.*, ju.nom AS admin_nom, jc.nom_categorie
            FROM journal_info ji
            LEFT JOIN journal_user ju ON ji.id_admin = ju.id_user
            LEFT JOIN journal_categories jc ON ji.id_categorie = jc.id_categorie';

    $where = [];
    $params = [];

    if ($search !== '') {
        $where[] = '(ji.titre LIKE :search OR ji.details LIKE :search OR ju.nom LIKE :search OR jc.nom_categorie LIKE :search OR ji.date LIKE :search OR CAST(ji.id AS CHAR) LIKE :search)';
        $params['search'] = '%' . $search . '%';
    }

    if ($selectedCategoryId > 0) {
        $where[] = 'ji.id_categorie = :categoryId';
        $params['categoryId'] = $selectedCategoryId;
    }

    if (!empty($where)) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= ' ORDER BY ji.date DESC';

    $stmt = $pdo->prepare($sql);
    if (isset($params['search'])) {
        $stmt->bindValue(':search', $params['search']);
    }
    if (isset($params['categoryId'])) {
        $stmt->bindValue(':categoryId', (int) $params['categoryId'], PDO::PARAM_INT);
    }

    $stmt->execute();
    $articles = $stmt->fetchAll();
} catch (Throwable $e) {
    $dbError = 'Erreur de connexion a la base de donnees.';
}

$articlesWithImage = [];
$articlesWithoutImage = [];

function normalize_image_src_for_simple_php(string $src): string
{
    $src = trim($src);
    if ($src === '') {
        return '';
    }

    // Nettoyer les chemins vers /uploads/
    if (strpos($src, '/simple-php-login/uploads/') === 0) {
        return str_replace('/simple-php-login/uploads/', '/uploads/', $src);
    }
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

function slugify_admin_title(string $title): string
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

function build_admin_article_url(array $article): string
{
    $title = (string) ($article['titre'] ?? 'article');
    return '/admin/article/' . rawurlencode(slugify_admin_title($title));
}

foreach ($articles as $article) {
    $firstImage = null;
    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', (string) ($article['details'] ?? ''), $match) === 1) {
        $firstImage = normalize_image_src_for_simple_php((string) $match[1]);
    }

    if (!empty($firstImage)) {
        $article['first_image'] = $firstImage;
        $articlesWithImage[] = $article;
    } else {
        $articlesWithoutImage[] = $article;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles - Admin</title>
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
        .logout-link {
            margin-left: 8px;
            color: #cbd5e1;
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
                <span>
                    Gestion des contenus et editeurs
                    <a class="logout-link" href="/admin/logout">Deconnexion</a>
                </span>
            </div>
        </div>

        <div class="header-main-inner">
            <div>
                <a href="/admin" class="brand-link">Journal d'Information</a>
                <p class="brand-tagline">Espace d'administration</p>
            </div>
            <div class="header-actions">
                <a href="/admin" class="header-chip">Tous les articles</a>
                <a href="/admin/create" class="header-chip header-chip-primary">+ Nouvel Article</a>
            </div>
            <div class="header-categories">
                <?php foreach ($categories as $headerCategory): ?>
                    <a
                        href="/admin?q=<?= urlencode($search) ?>&category=<?= (int) $headerCategory['id_categorie'] ?>"
                        class="category-link <?= $selectedCategoryId === (int) $headerCategory['id_categorie'] ? 'active' : '' ?>"
                    >
                        <?= htmlspecialchars((string) $headerCategory['nom_categorie'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <main>
        <h2 class="page-title">Gestion des Articles</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                switch ((string) $_GET['success']) {
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

        <?php if ($dbError !== ''): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($dbError, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form action="/admin" method="GET" class="search-bar">
            <input
                type="text"
                name="q"
                class="search-input"
                placeholder="Rechercher par titre, contenu, auteur, date ou ID"
                value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
            >
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
                <a href="/admin" class="btn">Effacer</a>
            <?php endif; ?>
        </form>

        <?php if (empty($articles)): ?>
            <?php if ($search !== ''): ?>
                <p>Aucun article trouve pour la recherche "<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>".</p>
            <?php else: ?>
                <p>Aucun article pour le moment.</p>
            <?php endif; ?>
        <?php else: ?>
            <div class="articles-layout">
                <div>
                    <h3 class="side-title">Articles avec image</h3>
                    <div class="articles-with-image">
                        <?php foreach ($articlesWithImage as $article): ?>
                            <?php $articleUrl = build_admin_article_url($article); ?>
                            <article class="article-card" onclick="window.location.href='<?= htmlspecialchars($articleUrl, ENT_QUOTES, 'UTF-8') ?>'">
                                <img src="<?= htmlspecialchars((string) $article['first_image'], ENT_QUOTES, 'UTF-8') ?>" alt="Apercu image de l'article" class="article-thumb">

                                <h2 class="article-title"><?= htmlspecialchars((string) $article['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
                                <p class="article-meta"><strong>Date:</strong> <?= htmlspecialchars((string) $article['date'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="article-meta"><strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe'), ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="article-meta"><strong>Auteur:</strong> <?= htmlspecialchars((string) ($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])), ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="content-preview">
                                    <?= htmlspecialchars(mb_substr(strip_tags((string) $article['details']), 0, 160), ENT_QUOTES, 'UTF-8') ?>...
                                </p>

                                <div class="card-actions" onclick="event.stopPropagation()">
                                    <a href="/admin/edit/<?= (int) $article['id'] ?>" class="btn btn-warning btn-icon" title="Modifier" aria-label="Modifier">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18.71-11.04a1.004 1.004 0 0 0 0-1.42l-2.5-2.5a1.004 1.004 0 0 0-1.42 0L15.13 4.95l3.75 3.75 2.83-2.49z"/></svg>
                                        <span class="sr-only">Modifier</span>
                                    </a>
                                    <a href="/admin/delete/<?= (int) $article['id'] ?>" class="btn btn-danger btn-icon" title="Supprimer" aria-label="Supprimer" onclick="return confirm('Etes-vous sur de vouloir supprimer cet article ?')">
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
                            <?php $articleUrl = build_admin_article_url($article); ?>
                            <article class="article-card no-image" onclick="window.location.href='<?= htmlspecialchars($articleUrl, ENT_QUOTES, 'UTF-8') ?>'">
                                <h2 class="article-title"><?= htmlspecialchars((string) $article['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
                                <p class="article-meta"><strong>Date:</strong> <?= htmlspecialchars((string) $article['date'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="article-meta"><strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe'), ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="article-meta"><strong>Auteur:</strong> <?= htmlspecialchars((string) ($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])), ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="content-preview">
                                    <?= htmlspecialchars(mb_substr(strip_tags((string) $article['details']), 0, 160), ENT_QUOTES, 'UTF-8') ?>...
                                </p>

                                <div class="card-actions" onclick="event.stopPropagation()">
                                    <a href="/admin/edit/<?= (int) $article['id'] ?>" class="btn btn-warning btn-icon" title="Modifier" aria-label="Modifier">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18.71-11.04a1.004 1.004 0 0 0 0-1.42l-2.5-2.5a1.004 1.004 0 0 0-1.42 0L15.13 4.95l3.75 3.75 2.83-2.49z"/></svg>
                                        <span class="sr-only">Modifier</span>
                                    </a>
                                    <a href="/admin/delete/<?= (int) $article['id'] ?>" class="btn btn-danger btn-icon" title="Supprimer" aria-label="Supprimer" onclick="return confirm('Etes-vous sur de vouloir supprimer cet article ?')">
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
