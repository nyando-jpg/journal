<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre']) ?> - Admin</title>
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
        .article-header {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: start;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .article-header-left h1 {
            margin: 0 0 16px 0;
            font-size: 2.2rem;
            color: #0f172a;
            line-height: 1.2;
            font-family: "Playfair Display", "Times New Roman", serif;
        }
        .article-meta-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .meta-item {
            display: flex;
            gap: 8px;
            font-size: 0.95rem;
            align-items: center;
        }
        .meta-label {
            font-weight: 600;
            color: #475569;
            min-width: 80px;
        }
        .meta-value {
            color: #1f2937;
            padding: 4px 10px;
            background: #f1f5f9;
            border-radius: 4px;
            border-left: 3px solid #3b82f6;
        }
        .article-actions-top {
            display: flex;
            gap: 8px;
            justify-self: end;
        }
        .article-content {
            line-height: 1.7;
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
        .btn-back {
            background-color: transparent;
            color: #94a3b8;
            margin-bottom: 10px;
            padding: 6px 12px;
            font-size: 14px;
            transition: color 0.2s ease;
        }
        .btn-back:hover {
            color: #64748b;
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
        .related-section {
            margin-top: 28px;
            border-top: 1px solid #e5e7eb;
            padding-top: 18px;
        }
        .related-title {
            margin: 0 0 12px 0;
            font-size: 1.05rem;
            color: #1f2937;
        }
        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }
        .related-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }
        .related-card:hover {
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }
        .related-link {
            display: block;
            text-decoration: none;
            color: inherit;
        }
        .related-thumb {
            width: 100%;
            height: 120px;
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
        .related-actions {
            padding: 0 10px 10px;
            display: flex;
            justify-content: flex-end;
            gap: 6px;
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
                        href="/admin/articles?q=&category=<?= (int) $headerCategory['id_categorie'] ?>"
                        class="category-link"
                    >
                        <?= htmlspecialchars((string) $headerCategory['nom_categorie']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <main>
        <br><br><br><br><br><br><br>
        <a href="/admin/articles?q=&category=0" class="btn btn-back">← Retour à la liste</a>
        <article class="article-card">
            <div class="article-meta">
                <strong>Auteur:</strong> <?= htmlspecialchars($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])) ?><br>
                <strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe')) ?><br>
                <strong>Date:</strong> <?= htmlspecialchars($article['date']) ?>
            </div>
            <div class="article-content">
                <?= $articleDetailsHtml ?? '' ?>
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

            <?php if (!empty($relatedArticles)): ?>
                <div class="related-section">
                    <h3 class="related-title">Autres articles</h3>
                    <div class="related-grid">
                        <?php foreach ($relatedArticles as $related): ?>
                            <article class="related-card">
                                <a href="/admin/articles/view/<?= (int) $related['id'] ?>" class="related-link">
                                    <img src="<?= htmlspecialchars((string) $related['first_image']) ?>" alt="Apercu article" class="related-thumb">
                                    <div class="related-body">
                                        <p class="related-date"><?= htmlspecialchars((string) ($related['date'] ?? '')) ?></p>
                                        <p class="related-name"><?= htmlspecialchars((string) ($related['titre'] ?? '')) ?></p>
                                    </div>
                                </a>
                                <div class="related-actions">
                                    <a href="/admin/articles/edit/<?= (int) $related['id'] ?>" class="btn btn-warning btn-icon" title="Modifier" aria-label="Modifier">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18.71-11.04a1.004 1.004 0 0 0 0-1.42l-2.5-2.5a1.004 1.004 0 0 0-1.42 0L15.13 4.95l3.75 3.75 2.83-2.49z"/></svg>
                                        <span class="sr-only">Modifier</span>
                                    </a>
                                    <a href="/admin/articles/delete/<?= (int) $related['id'] ?>"
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
            <?php endif; ?>
        </article>
    </main>
</body>
</html>
