<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article #<?= htmlspecialchars($article['id']) ?> - Admin</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
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
    <div class="container">
        <h1><?= htmlspecialchars($article['titre']) ?></h1>

        <nav style="margin-bottom: 20px;">
            <a href="/admin/articles" class="btn">← Retour a la liste</a>
        </nav>

        <article class="article-card">
            <div class="article-meta">
                <strong>Auteur:</strong> <?= htmlspecialchars($article['admin_nom'] ?? ('Admin #' . $article['id_admin'])) ?><br>
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
    </div>
</body>
</html>
