<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    // Extraire le titre de l'article
    preg_match('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/i', $article['details'], $matches);
    $title = isset($matches[1]) ? strip_tags($matches[1]) : 'Article';

    // Extraire une description (premiers 160 caractères)
    $description = mb_substr(strip_tags($article['details']), 0, 160);

    // Formater la date
    $date = new DateTime($article['date']);
    $dateFormatted = $date->format('d F Y');
    $dateISO = $date->format('c');
    ?>

    <!-- SEO Meta Tags -->
    <title><?= htmlspecialchars($title) ?> - Journal d'Information</title>
    <meta name="description" content="<?= htmlspecialchars($description) ?>">
    <meta name="author" content="Journal Info">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?= htmlspecialchars($title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($description) ?>">
    <meta property="og:locale" content="fr_FR">
    <meta property="article:published_time" content="<?= $dateISO ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($description) ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="http://localhost:8000/article/<?= $article['id'] ?>">

    <!-- Schema.org JSON-LD pour SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "headline": "<?= htmlspecialchars($title) ?>",
        "datePublished": "<?= $dateISO ?>",
        "description": "<?= htmlspecialchars($description) ?>",
        "author": {
            "@type": "Organization",
            "name": "Journal d'Information"
        }
    }
    </script>

    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Georgia', serif;
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
            font-size: 2rem;
            margin-bottom: 5px;
        }
        header a {
            color: white;
            text-decoration: none;
        }
        header a:hover {
            text-decoration: underline;
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
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .article-container {
            background: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .article-meta {
            color: #666;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .article-content {
            font-size: 1.1rem;
        }
        .article-content h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #1a1a2e;
        }
        .article-content h2 {
            font-size: 1.6rem;
            margin: 30px 0 15px;
            color: #1a1a2e;
        }
        .article-content h3 {
            font-size: 1.3rem;
            margin: 25px 0 10px;
            color: #1a1a2e;
        }
        .article-content p {
            margin-bottom: 20px;
        }
        .article-content ul, .article-content ol {
            margin: 20px 0;
            padding-left: 30px;
        }
        .article-content li {
            margin-bottom: 10px;
        }
        .article-content strong {
            color: #1a1a2e;
        }
        .article-content blockquote {
            border-left: 4px solid #e94560;
            padding-left: 20px;
            margin: 30px 0;
            font-style: italic;
            color: #555;
        }
        .back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: #e94560;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-link:hover {
            background-color: #d63850;
        }
        footer {
            background-color: #1a1a2e;
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="/actualites">Journal d'Information</a></h1>
    </header>

    <nav class="main-nav" role="navigation" aria-label="Navigation principale">
        <a href="/actualites">Accueil</a>
        <a href="/loginAdmin">Administration</a>
    </nav>

    <main>
        <article class="article-container" itemscope itemtype="https://schema.org/NewsArticle">
            <div class="article-meta">
                <time datetime="<?= $date->format('Y-m-d') ?>" itemprop="datePublished">
                    Publié le <?= $dateFormatted ?>
                </time>
            </div>

            <div class="article-content" itemprop="articleBody">
                <?= $article['details'] ?>
            </div>

            <a href="/actualites" class="back-link">&larr; Retour aux actualités</a>
        </article>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Journal d'Information - Tous droits réservés</p>
    </footer>
</body>
</html>
