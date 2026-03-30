<?php

require __DIR__ . '/simple-php-login/config/db.php';

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

$pdo = db_connect();
$query = $pdo->query('SELECT id, titre FROM journal_info ORDER BY date DESC LIMIT 5');
$rows = $query->fetchAll();

$test_slug = 'grand-reportage-survivre-entre-front-mobile-et-ville-epuisee';
echo "Testing slug lookup for: $test_slug\n\n";

foreach ($rows as $row) {
    $generated = slugify_admin_title((string) ($row['titre'] ?? ''));
    echo "Article ID {$row['id']}: {$row['titre']}\n";
    echo "  Generated slug: $generated\n";
    echo "  Match: " . ($generated === $test_slug ? 'YES ✓' : 'NO ✗') . "\n\n";
}
