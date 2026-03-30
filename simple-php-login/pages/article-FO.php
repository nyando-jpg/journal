<?php

declare(strict_types=1);

require __DIR__ . '/../config/db.php';

$articleId = max(0, (int) ($_GET['id'] ?? 0));
if ($articleId <= 0) {
    header('Location: /Iran/actualites.html?error=notfound');
    exit;
}

$article = null;
$categories = [];
$relatedArticles = [];
$dbError = '';

function normalize_front_image_src_detail(string $src): string
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

function extract_first_image_from_details_front(string $details): string
{
    if ($details === '') {
        return '';
    }

    if (preg_match('/src=["\']([^"\']+)["\']/i', $details, $match) === 1) {
        return normalize_front_image_src_detail((string) $match[1]);
    }

    return '';
}

try {
    $pdo = db_connect();

    $catStmt = $pdo->query('SELECT id_categorie, nom_categorie FROM journal_categories ORDER BY nom_categorie ASC');
    $categories = $catStmt->fetchAll();

    $stmt = $pdo->prepare('SELECT ji.*, ju.nom AS admin_nom, jc.nom_categorie FROM journal_info ji LEFT JOIN journal_user ju ON ji.id_admin = ju.id_user LEFT JOIN journal_categories jc ON ji.id_categorie = jc.id_categorie WHERE ji.id = :id LIMIT 1');
    $stmt->bindValue(':id', $articleId, PDO::PARAM_INT);
    $stmt->execute();
    $article = $stmt->fetch();

    if (!$article) {
        header('Location: /Iran/actualites.html?error=notfound');
        exit;
    }

    $relatedStmt = $pdo->prepare('SELECT id, titre, date, details FROM journal_info WHERE id <> :id AND details LIKE :withImage ORDER BY date DESC LIMIT 3');
    $relatedStmt->bindValue(':id', $articleId, PDO::PARAM_INT);
    $relatedStmt->bindValue(':withImage', '%src=%');
    $relatedStmt->execute();
    $relatedRows = $relatedStmt->fetchAll();

    foreach ($relatedRows as $row) {
        $img = extract_first_image_from_details_front((string) ($row['details'] ?? ''));
        if ($img === '') {
            $img = '/uploads/image.png';
        }
        $row['first_image'] = $img;
        $relatedArticles[] = $row;
    }
} catch (Throwable $e) {
    $dbError = 'Erreur de connexion a la base de donnees.';
}

$title = is_array($article) ? (string) ($article['titre'] ?? 'Article') : 'Article';
$description = is_array($article) ? mb_substr(strip_tags((string) ($article['details'] ?? '')), 0, 160) : '';
$articleDetailsHtml = '';
if (is_array($article)) {
    $articleDetailsHtml = (string) ($article['details'] ?? '');
    $articleDetailsHtml = preg_replace_callback(
        '/(<img[^>]+src=["\'])([^"\']+)(["\'])/i',
        static function (array $matches): string {
            return $matches[1] . normalize_front_image_src_detail((string) $matches[2]) . $matches[3];
        },
        $articleDetailsHtml
    ) ?? $articleDetailsHtml;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> - Journal d'Information</title>
    <meta name="description" content="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Merriweather", Georgia, serif; line-height: 1.8; color: #333; background-color: #f5f5f5; }
        .site-header { background-color: #0f172a; color: #f8fafc; border-bottom: 1px solid rgba(148, 163, 184, 0.35); }
        .header-top { background: #0b1220; border-bottom: 1px solid rgba(148, 163, 184, 0.25); font-size: 0.86rem; }
        .header-top-inner { max-width: 1200px; margin: 0 auto; padding: 8px 20px; display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; color: #cbd5e1; }
        .header-main-inner { max-width: 1200px; margin: 0 auto; padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
        .brand-link { color: #ffffff; text-decoration: none; font-size: 2rem; line-height: 1.1; font-family: "Playfair Display", "Times New Roman", serif; }
        .brand-tagline { margin: 4px 0 0; font-size: 0.95rem; color: #cbd5e1; }
        .header-categories { display: flex; gap: 16px; flex-wrap: wrap; padding-top: 12px; border-top: 1px solid rgba(148, 163, 184, 0.25); }
        .category-link { color: #ffffff; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
        .category-link:hover { color: #60a5fa; }
        main { width: 90%; max-width: 1200px; margin: 20px auto; padding: 0; }
        .article-container { background: white; border-radius: 8px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .article-title { font-size: 2rem; color: #1f2937; margin-bottom: 20px; font-family: "Playfair Display", "Times New Roman", serif; line-height: 1.3; }
        .article-meta { color: #666; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .article-content { font-size: 1.1rem; }
        .article-content p:first-of-type::first-letter { float: left; font-family: "Playfair Display", "Times New Roman", serif; font-size: 4.4em; line-height: 0.82; margin: 0.03em 0.14em 0 0; font-weight: 700; color: #0f172a; text-transform: uppercase; }
        .article-content img { max-width: 100%; border-radius: 6px; height: auto; }
        .related-section { margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 20px; }
        .related-title { margin: 0 0 14px 0; font-size: 1.1rem; color: #1a1a2e; }
        .related-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
        .related-card { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: #fff; text-decoration: none; color: inherit; }
        .related-thumb { width: 100%; height: 130px; object-fit: cover; display: block; background: #f3f4f6; }
        .related-body { padding: 10px; }
        .related-date { margin: 0 0 6px 0; font-size: 0.85rem; color: #6b7280; }
        .related-name { margin: 0; font-size: 0.95rem; color: #111827; }
        .back-link { display: inline-block; margin-top: 30px; padding: 12px 25px; background-color: #e94560; color: white; text-decoration: none; border-radius: 5px; }
        .site-footer { background-color: #0f172a; color: #f8fafc; border-top: 1px solid rgba(148, 163, 184, 0.35); }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1.4fr 1fr 1fr; gap: 24px; padding: 38px 20px 0; }
        .footer-title { margin: 0 0 10px 0; font-size: 1rem; color: #ffffff; letter-spacing: 0.04em; text-transform: uppercase; }
        .footer-text { margin: 0 0 8px 0; color: #cbd5e1; font-size: 0.95rem; line-height: 1.7; }
        .footer-list { margin: 0; padding: 0; list-style: none; display: grid; gap: 7px; }
        .footer-list a { color: #cbd5e1; text-decoration: none; font-size: 0.95rem; }
        .footer-list a:hover { color: #60a5fa; }
        .footer-bottom { max-width: 1200px; margin: 18px auto 0; padding: 16px 20px; border-top: 1px solid rgba(148, 163, 184, 0.35); color: #94a3b8; font-size: 0.9rem; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        @media (max-width: 900px) { .article-container { padding: 25px; } .related-grid { grid-template-columns: 1fr; } .footer-inner { grid-template-columns: 1fr 1fr; } .footer-bottom { flex-direction: column; } }
        @media (max-width: 600px) { .footer-inner { grid-template-columns: 1fr; } }
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
                    <a href="/Iran/actualites.html?category=<?= (int) $headerCategory['id_categorie'] ?>" class="category-link">
                        <?= htmlspecialchars((string) $headerCategory['nom_categorie'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <main>
        <?php if ($dbError !== ''): ?>
            <div class="article-container"><p><?= htmlspecialchars($dbError, ENT_QUOTES, 'UTF-8') ?></p></div>
        <?php endif; ?>

        <?php if ($article): ?>
            <article class="article-container">
                <h1 class="article-title"><?= htmlspecialchars((string) $article['titre'], ENT_QUOTES, 'UTF-8') ?></h1>
                <div class="article-meta">
                    <p><strong>Date:</strong> <?= htmlspecialchars((string) $article['date'], ENT_QUOTES, 'UTF-8') ?> | <strong>Categorie:</strong> <?= htmlspecialchars((string) ($article['nom_categorie'] ?? 'Non classe'), ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <div class="article-content">
                    <?= $articleDetailsHtml ?>
                </div>

                <?php if (!empty($relatedArticles)): ?>
                    <section class="related-section">
                        <h3 class="related-title">Autres articles</h3>
                        <div class="related-grid">
                            <?php foreach ($relatedArticles as $related): ?>
                                <a href="/Iran/article/<?= (int) $related['id'] ?>.html" class="related-card">
                                    <img src="<?= htmlspecialchars((string) $related['first_image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) ($related['titre'] ?? 'Article'), ENT_QUOTES, 'UTF-8') ?>" class="related-thumb">
                                    <div class="related-body">
                                        <p class="related-date"><?= htmlspecialchars((string) ($related['date'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                                        <p class="related-name"><?= htmlspecialchars((string) ($related['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <a href="/Iran/actualites.html" class="back-link">Retour aux actualites</a>
            </article>
        <?php endif; ?>
    </main>

    <footer class="site-footer">
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
                <a href="#" style="color: #cbd5e1; text-decoration: none; margin-left: 15px;">Mentions legales</a>
                <a href="#" style="color: #cbd5e1; text-decoration: none; margin-left: 15px;">Politique de confidentialite</a>
            </div>
        </div>
    </footer>
</body>
</html>
