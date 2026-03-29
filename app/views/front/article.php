<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    $title = $article['titre'] ?? 'Article';

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
        .header-categories {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 12px;
            border-top: 1px solid rgba(148, 163, 184, 0.25);
        }
        .category-link {
            display: inline-block;
            padding: 0;
            background: none;
            color: #ffffff;
            text-decoration: none;
            border-radius: 0;
            font-size: 0.9rem;
            font-weight: 500;
            border: none;
            transition: all 0.2s ease;
        }
        .category-link:hover {
            background: none;
            color: #60a5fa;
            border-color: transparent;
        }
        .category-link.active {
            background: none;
            color: #60a5fa;
            border-color: transparent;
            font-weight: 600;
        }

        main {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 0;
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
        .article-content p:first-of-type {
            letter-spacing: 0.01em;
        }
        .article-content p:first-of-type::first-letter {
            float: left;
            font-family: "Playfair Display", "Times New Roman", serif;
            font-size: 4.4em;
            line-height: 0.82;
            margin: 0.03em 0.14em 0 0;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
        }
        .article-content .image-sequence {
            display: grid;
            gap: 12px;
            margin: 18px 0;
            align-items: start;
            justify-items: center;
        }
        .article-content * + .image-sequence {
            margin-top: 24px;
        }
        .article-content .image-sequence + * {
            margin-top: 18px;
        }
        .article-content .image-sequence > * {
            margin: 0;
            width: 100%;
            display: flex;
            justify-content: center;
        }
        .article-content .image-sequence img {
            width: auto;
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            display: block;
        }
        .article-content .image-sequence.image-count-1 {
            grid-template-columns: minmax(280px, 65%);
            justify-content: center;
        }
        .article-content .image-sequence.image-count-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .article-content .image-sequence.image-count-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .article-content .image-sequence.image-count-many {
            grid-template-columns: repeat(2, minmax(0, 1fr));
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
        .related-section {
            margin-top: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .related-title {
            margin: 0 0 14px 0;
            font-size: 1.1rem;
            color: #1a1a2e;
        }
        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }
        .related-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            text-decoration: none;
            color: inherit;
        }
        .related-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }
        .related-thumb {
            width: 100%;
            height: 130px;
            object-fit: cover;
            display: block;
            background: #f3f4f6;
        }
        .related-body {
            padding: 10px;
        }
        .related-date {
            margin: 0 0 6px 0;
            font-size: 0.85rem;
            color: #6b7280;
        }
        .related-name {
            margin: 0;
            font-size: 0.95rem;
            color: #111827;
            line-height: 1.4;
        }
        footer {
            background-color: #121629;
            color: #e5e7eb;
            padding: 38px 20px 20px;
            margin-top: 50px;
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: 24px;
        }
        .footer-title {
            margin: 0 0 10px 0;
            font-size: 1rem;
            color: #ffffff;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .footer-text {
            margin: 0;
            color: #cbd5e1;
            font-size: 0.95rem;
            line-height: 1.7;
        }
        .footer-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 7px;
        }
        .footer-list a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .footer-list a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        .footer-bottom {
            max-width: 1200px;
            margin: 18px auto 0;
            padding-top: 16px;
            border-top: 1px solid rgba(148, 163, 184, 0.35);
            color: #94a3b8;
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        @media (max-width: 900px) {
            .article-content p:first-of-type::first-letter {
                font-size: 3.4em;
                margin-right: 0.12em;
            }
            .article-content .image-sequence.image-count-2,
            .article-content .image-sequence.image-count-3,
            .article-content .image-sequence.image-count-many {
                grid-template-columns: 1fr;
            }
            .related-grid {
                grid-template-columns: 1fr;
            }
            .footer-inner {
                grid-template-columns: 1fr;
            }
            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
            }
        }
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
                <a href="/actualites" class="brand-link">Journal d'Information</a>
                <p class="brand-tagline">Analyses, terrain et decryptage geopolitique</p>
            </div>
            <div class="header-actions">
                <a href="/actualites" class="header-chip">A la une</a>
                <a href="/actualites" class="header-chip">Newsletter</a>
            </div>
            <div class="header-categories">
                <?php foreach (($categories ?? []) as $headerCategory): ?>
                    <a
                        href="/actualites?category=<?= (int) $headerCategory['id_categorie'] ?>"
                        class="category-link <?= (int) ($article['id_categorie'] ?? 0) === (int) $headerCategory['id_categorie'] ? 'active' : '' ?>"
                    >
                        <?= htmlspecialchars((string) $headerCategory['nom_categorie']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <main>
        <article class="article-container" itemscope itemtype="https://schema.org/NewsArticle">
            <div class="article-meta">
                <div>
                    <strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe')) ?>
                </div>
                <time datetime="<?= $date->format('Y-m-d') ?>" itemprop="datePublished">
                    Publié le <?= $dateFormatted ?>
                </time>
            </div>

            <div class="article-content" itemprop="articleBody">
                <?= $articleDetailsHtml ?? '' ?>
            </div>

            <?php if (!empty($relatedArticles)): ?>
                <div class="related-section">
                    <h3 class="related-title">Les lecteurs lisent aussi</h3>
                    <div class="related-grid">
                        <?php foreach ($relatedArticles as $related): ?>
                            <a href="/article/<?= (int) $related['id'] ?>" class="related-card">
                                <img src="<?= htmlspecialchars((string) $related['first_image']) ?>" alt="Apercu article" class="related-thumb">
                                <div class="related-body">
                                    <p class="related-date"><?= htmlspecialchars((string) ($related['date'] ?? '')) ?></p>
                                    <p class="related-name"><?= htmlspecialchars((string) ($related['titre'] ?? '')) ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <a href="/actualites" class="back-link">&larr; Retour aux actualités</a>
        </article>
    </main>

    <footer>
        <div class="footer-inner">
            <section>
                <h4 class="footer-title">Journal d'Information</h4>
                <p class="footer-text">Couverture continue des actualites, analyses de contexte et suivi terrain. Notre redaction publie des mises a jour quotidiennes avec verification des sources.</p>
            </section>

            <section>
                <h4 class="footer-title">Navigation</h4>
                <ul class="footer-list">
                    <li><a href="/actualites">Retour aux actualites</a></li>
                    <li><a href="/actualites">Les plus recents</a></li>
                    <li><a href="/actualites">Themes suivis</a></li>
                    <li><a href="/loginAdmin">Espace administration</a></li>
                </ul>
            </section>

            <section>
                <h4 class="footer-title">Contact Redaction</h4>
                <p class="footer-text">Email: redaction@journalinfo.fr</p>
                <p class="footer-text">Tel: +261 34 00 000 00</p>
                <p class="footer-text">Lun-Ven: 08h00 - 18h00</p>
            </section>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> Journal d'Information. Tous droits reserves.</span>
            <span>Mentions legales | Politique de confidentialite</span>
        </div>
    </footer>
</body>
</html>
