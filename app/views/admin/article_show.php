<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre']) ?> - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Merriweather", Georgia, serif;
            line-height: 1.8;
            color: #333;
            background-color: #f5f5f5;
        }

        /* Header */
        .site-header { background: #0f172a; color: #f8fafc; }
        .header-top {
            background: #0b1220;
            border-bottom: 1px solid rgba(148,163,184,0.25);
            padding: 8px 24px;
            font-size: 0.8rem;
            color: #cbd5e1;
            display: flex;
            justify-content: space-between;
        }
        .header-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 24px 0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
        }
        .brand-link {
            font-family: "Playfair Display", "Times New Roman", serif;
            font-size: 1.85rem;
            color: #fff;
            font-weight: 400;
            text-decoration: none;
            display: block;
        }
        .brand-tagline { font-size: 0.83rem; color: #94a3b8; margin-top: 2px; }
        .header-actions { display: flex; gap: 8px; align-items: center; padding-top: 6px; }
        .header-chip {
            border: 1px solid rgba(148,163,184,0.5);
            color: #e2e8f0;
            text-decoration: none;
            padding: 6px 13px;
            border-radius: 999px;
            font-size: 0.85rem;
            transition: background 0.2s;
        }
        .header-chip:hover { background: rgba(148,163,184,0.15); }
        .header-chip-primary {
            background: #0f3b8a;
            color: #fff;
            border-color: #0f3b8a;
            font-weight: 600;
        }
        .header-chip-primary:hover { background: #1e4fa5; }
        .header-categories {
            max-width: 1200px;
            margin: 10px auto 0;
            padding: 10px 24px 12px;
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            border-top: 1px solid rgba(148,163,184,0.2);
        }
        .category-link { color: #fff; text-decoration: none; font-size: 0.88rem; font-weight: 500; transition: color 0.2s; }
        .category-link:hover { color: #60a5fa; }

        /* Main */
        main { max-width: 1200px; margin: 28px auto; padding: 0 22px 48px; }
        .back-link {
            display: inline-block;
            color: #64748b;
            font-size: 0.85rem;
            text-decoration: none;
            margin-bottom: 16px;
            transition: color 0.2s;
        }
        .back-link:hover { color: #334155; }

        /* Article card */
        .article-card {
            background: #fff;
            border: 1px solid #dde1e7;
            border-radius: 10px;
            border-top: 4px solid #0b1220;
            overflow: hidden;
        }
        .article-header {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: start;
            padding: 28px 28px 20px;
            border-bottom: 1px solid #f0f4f8;
        }
        .article-header h1 {
            font-family: "Playfair Display", "Times New Roman", serif;
            font-size: 2rem;
            font-weight: 400;
            color: #0f172a;
            line-height: 1.25;
            margin-bottom: 18px;
        }
        .article-meta-list { display: flex; flex-direction: column; gap: 8px; }
        .meta-item { display: flex; align-items: center; gap: 8px; font-size: 0.88rem; }
        .meta-label {
            font-weight: 600;
            color: #475569;
            min-width: 82px;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .meta-value {
            color: #1e293b;
            padding: 3px 10px;
            background: #f1f5f9;
            border-radius: 4px;
            border-left: 3px solid #0b1220;
            font-size: 0.88rem;
        }
        .article-actions-top { display: flex; gap: 7px; }

        /* Buttons */
        .btn { display: inline-block; padding: 10px 18px; text-decoration: none; border-radius: 6px; cursor: pointer; border: none; font-size: 15px; }
        .btn:hover { opacity: 0.88; }
        .btn-icon {
            width: 36px; height: 36px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 6px; border: none; cursor: pointer; text-decoration: none;
        }
        .btn-icon svg { width: 17px; height: 17px; fill: currentColor; }
        .btn-warning { background: #fef9c3; color: #854d0e; }
        .btn-warning:hover { background: #fef08a; }
        .btn-danger { background: #fef2f2; color: #991b1b; }
        .btn-danger:hover { background: #fee2e2; }
        .btn-action {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 16px; border-radius: 6px; font-size: 0.85rem;
            font-family: inherit; text-decoration: none; border: none;
            cursor: pointer; font-weight: 600;
        }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }

        /* Article content */
        .article-content { padding: 28px; font-size: 0.97rem; line-height: 1.85; color: #1e293b; }
        .article-content p { margin-bottom: 14px; }
        .article-content p:first-of-type::first-letter {
            float: left;
            font-family: "Playfair Display", "Times New Roman", serif;
            font-size: 4.2em;
            line-height: 0.82;
            margin: 0.03em 0.14em 0 0;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
        }
        .article-content img { max-width: 100%; border-radius: 6px; }
        .article-content .image-sequence { display: grid; gap: 12px; margin: 18px 0; }
        .article-content .image-sequence.image-count-1 { grid-template-columns: minmax(280px, 65%); justify-content: center; }
        .article-content .image-sequence.image-count-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .article-content .image-sequence.image-count-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .article-content .image-sequence.image-count-many { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        /* Footer actions */
        .article-footer-actions {
            padding: 0 28px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        /* Related */
        .related-section { border-top: 1px solid #e5e7eb; padding: 22px 28px 26px; }
        .related-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 14px;
        }
        .related-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
        .related-card { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: #fafafa; }
        .related-card:hover { box-shadow: 0 3px 12px rgba(0,0,0,0.09); }
        .related-link { display: block; text-decoration: none; color: inherit; }
        .related-thumb { width: 100%; height: 108px; object-fit: cover; display: block; background: #e2e8f0; }
        .related-body { padding: 10px 10px 4px; }
        .related-date { font-size: 0.78rem; color: #6b7280; margin-bottom: 4px; }
        .related-name { font-size: 0.88rem; color: #111827; line-height: 1.4; }
        .related-actions { padding: 6px 10px 10px; display: flex; justify-content: flex-end; gap: 5px; }

        @media (max-width: 900px) {
            .article-header { grid-template-columns: 1fr; }
            .article-content p:first-of-type::first-letter { font-size: 3.2em; }
            .article-content .image-sequence { grid-template-columns: 1fr !important; }
            .related-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <span>Espace Administration</span>
            <span>Gestion des contenus et éditeurs</span>
        </div>
        <div class="header-main">
            <div>
                <a href="/admin/articles?q=&category=0" class="brand-link">Journal d'Information</a>
                <p class="brand-tagline">Espace d'administration</p>
            </div>
            <div class="header-actions">
                <a href="/admin/articles?q=&category=0" class="header-chip">Tous les articles</a>
                <a href="/admin/articles/create" class="header-chip header-chip-primary">+ Nouvel article</a>
            </div>
        </div>
        <div class="header-categories">
            <?php foreach (($categories ?? []) as $headerCategory): ?>
                <a href="/admin/articles?q=&category=<?= (int) $headerCategory['id_categorie'] ?>" class="category-link">
                    <?= htmlspecialchars((string) $headerCategory['nom_categorie']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </header>

    <main>
        <a href="/admin/articles?q=&category=0" class="back-link">← Retour à la liste</a>

        <article class="article-card">
            <div class="article-header">
                <div>
                    <h1><?= htmlspecialchars($article['titre']) ?></h1>
                    <div class="article-meta-list">
                        <div class="meta-item">
                            <span class="meta-label">Auteur</span>
                            <span class="meta-value"><?= htmlspecialchars($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])) ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Catégorie</span>
                            <span class="meta-value"><?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classé')) ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Date</span>
                            <span class="meta-value"><?= htmlspecialchars($article['date']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="article-actions-top">
                    <a href="/admin/articles/edit/<?= (int) $article['id'] ?>" class="btn-icon btn-warning" title="Modifier" aria-label="Modifier">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18.71-11.04a1 1 0 0 0 0-1.42l-2.5-2.5a1 1 0 0 0-1.42 0L15.13 4.95l3.75 3.75 2.83-2.49z"/></svg>
                        <span class="sr-only">Modifier</span>
                    </a>
                    <a href="/admin/articles/delete/<?= (int) $article['id'] ?>"
                       class="btn-icon btn-danger"
                       title="Supprimer" aria-label="Supprimer"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 7h12l-1 14H7L6 7zm3-3h6l1 2h4v2H4V6h4l1-2z"/></svg>
                        <span class="sr-only">Supprimer</span>
                    </a>
                </div>
            </div>

            <div class="article-content">
                <?= $articleDetailsHtml ?? '' ?>
            </div>

            <div class="article-footer-actions">
                <a href="/admin/articles/edit/<?= (int) $article['id'] ?>" class="btn-action btn-warning">
                    <svg viewBox="0 0 24 24" style="width:15px;height:15px;fill:currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18.71-11.04a1 1 0 0 0 0-1.42l-2.5-2.5a1 1 0 0 0-1.42 0L15.13 4.95l3.75 3.75 2.83-2.49z"/></svg>
                    Modifier l'article
                </a>
                <a href="/admin/articles/delete/<?= (int) $article['id'] ?>"
                   class="btn-action btn-danger"
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                    <svg viewBox="0 0 24 24" style="width:15px;height:15px;fill:currentColor"><path d="M6 7h12l-1 14H7L6 7zm3-3h6l1 2h4v2H4V6h4l1-2z"/></svg>
                    Supprimer
                </a>
            </div>

            <?php if (!empty($relatedArticles)): ?>
                <div class="related-section">
                    <p class="related-title">Autres articles</p>
                    <div class="related-grid">
                        <?php foreach ($relatedArticles as $related): ?>
                            <article class="related-card">
                                <a href="/admin/articles/view/<?= (int) $related['id'] ?>" class="related-link">
                                    <img src="<?= htmlspecialchars((string) $related['first_image']) ?>" alt="Aperçu article" class="related-thumb">
                                    <div class="related-body">
                                        <p class="related-date"><?= htmlspecialchars((string) ($related['date'] ?? '')) ?></p>
                                        <p class="related-name"><?= htmlspecialchars((string) ($related['titre'] ?? '')) ?></p>
                                    </div>
                                </a>
                                <div class="related-actions">
                                    <a href="/admin/articles/edit/<?= (int) $related['id'] ?>" class="btn-icon btn-warning" title="Modifier" aria-label="Modifier">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18.71-11.04a1 1 0 0 0 0-1.42l-2.5-2.5a1 1 0 0 0-1.42 0L15.13 4.95l3.75 3.75 2.83-2.49z"/></svg>
                                        <span class="sr-only">Modifier</span>
                                    </a>
                                    <a href="/admin/articles/delete/<?= (int) $related['id'] ?>"
                                       class="btn-icon btn-danger"
                                       title="Supprimer" aria-label="Supprimer"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
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