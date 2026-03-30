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
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }
        .article-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .article-card-content {
            padding: 25px;
        }
        .article-card h2 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: #1a1a2e;
        }
        .article-card h2 a {
            color: inherit;
            text-decoration: none;
        }
        .article-card h2 a:hover {
            color: #e94560;
        }
        .article-meta {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }
        .article-excerpt {
            color: #555;
            margin-bottom: 20px;
        }
        .read-more {
            display: inline-block;
            background-color: #e94560;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .read-more:hover {
            background-color: #d63850;
        }
        footer {
            background-color: #1a1a2e;
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: 50px;
        }
        .no-articles {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 8px;
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

            <?php if (empty($articles)): ?>
                <div class="no-articles">
                    <p>Aucun article publié pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="articles-grid">
                    <?php foreach ($articles as $article): ?>
                        <?php
                        $title = $article['titre'] ?? mb_substr(strip_tags($article['details']), 0, 50) . '...';

                        // Extraire un extrait
                        $excerpt = mb_substr(strip_tags($article['details']), 0, 150) . '...';

                        // Formater la date
                        $date = new DateTime($article['date']);
                        $dateFormatted = $date->format('d F Y');
                        ?>
                        <article class="article-card">
                            <div class="article-card-content">
                                <h2><a href="/article/<?= htmlspecialchars($article['id']) ?>"><?= htmlspecialchars($title) ?></a></h2>
                                <p class="article-meta">
                                    <time datetime="<?= $date->format('Y-m-d') ?>"><?= $dateFormatted ?></time>
                                </p>
                                <p class="article-excerpt"><?= htmlspecialchars($excerpt) ?></p>
                                <a href="/article/<?= htmlspecialchars($article['id']) ?>" class="read-more">Lire la suite</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Journal d'Information - Tous droits réservés</p>
    </footer>
</body>
</html>
