<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['is_admin'])) {
    session_unset();
    header('Location: /admin/login');
    exit;
}

require __DIR__ . '/../config/db.php';

$categories = [];
$errorCode = '';
$dbError = '';

$titre = trim((string) ($_POST['titre'] ?? ''));
$details = (string) ($_POST['details'] ?? '');
$idCategorie = max(0, (int) ($_POST['id_categorie'] ?? 0));

function has_local_image_path(string $html): bool
{
    if (stripos($html, 'file:///') !== false) {
        return true;
    }

    if (preg_match('#[a-zA-Z]:[\\\\/]#', $html) === 1) {
        return true;
    }

    if (preg_match('#src\s*=\s*["\']\s*\\\\#i', $html) === 1) {
        return true;
    }

    return false;
}

try {
    $pdo = db_connect();

    $catStmt = $pdo->query('SELECT id_categorie, nom_categorie FROM journal_categories ORDER BY nom_categorie ASC');
    $categories = $catStmt->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($titre === '' || $details === '' || $idCategorie <= 0) {
            $errorCode = '1';
        } else {
            $existsStmt = $pdo->prepare('SELECT 1 FROM journal_categories WHERE id_categorie = :id LIMIT 1');
            $existsStmt->bindValue(':id', $idCategorie, PDO::PARAM_INT);
            $existsStmt->execute();

            if (!$existsStmt->fetchColumn()) {
                $errorCode = 'category';
            } elseif (has_local_image_path($details)) {
                $errorCode = 'local_image';
            } else {
                $insertStmt = $pdo->prepare('INSERT INTO journal_info (date, id_admin, id_categorie, titre, details) VALUES (NOW(), :id_admin, :id_categorie, :titre, :details)');
                $insertStmt->bindValue(':id_admin', (int) $_SESSION['user_id'], PDO::PARAM_INT);
                $insertStmt->bindValue(':id_categorie', $idCategorie, PDO::PARAM_INT);
                $insertStmt->bindValue(':titre', $titre);
                $insertStmt->bindValue(':details', $details);
                $insertStmt->execute();

                header('Location: /admin?success=created');
                exit;
            }
        }
    }
} catch (Throwable $e) {
    $dbError = 'Erreur de connexion a la base de donnees.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creer un Article - Admin</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Merriweather", Georgia, serif;
            line-height: 1.8;
            color: #333;
            background-color: #f5f5f5;
        }

        .site-header { background-color: #0f172a; color: #f8fafc; }
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
            max-width: 1100px;
            margin: 0 auto;
            padding: 16px 24px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand-name {
            font-family: "Playfair Display", "Times New Roman", serif;
            font-size: 1.75rem;
            color: #fff;
            font-weight: 400;
        }
        .brand-tagline { font-size: 0.82rem; color: #94a3b8; margin-top: 2px; }
        .admin-chip {
            background: rgba(148,163,184,0.15);
            border: 1px solid rgba(148,163,184,0.3);
            color: #cbd5e1;
            font-size: 0.78rem;
            padding: 5px 12px;
            border-radius: 999px;
            letter-spacing: 0.04em;
        }

        main {
            max-width: 860px;
            margin: 28px auto;
            padding: 0 20px 40px;
        }
        .back-link {
            display: inline-block;
            color: #64748b;
            text-decoration: none;
            font-size: 0.85rem;
            margin-bottom: 18px;
            transition: color 0.2s;
        }
        .back-link:hover { color: #334155; }
        .title-badge {
            display: inline-block;
            background: #0b1220;
            color: #dbeafe;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 999px;
            margin-bottom: 10px;
        }
        .page-title {
            font-family: "Playfair Display", "Times New Roman", serif;
            font-size: 1.85rem;
            font-weight: 400;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .page-subtitle {
            font-size: 0.88rem;
            color: #64748b;
            margin-bottom: 22px;
        }

        .alert {
            padding: 12px 16px;
            margin-bottom: 18px;
            border-left: 4px solid #dc2626;
            border-radius: 0;
            background: #fef2f2;
            color: #7f1d1d;
            font-size: 0.88rem;
        }

        .form-card {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            border-top: 4px solid #0b1220;
            padding: 30px 32px 28px;
        }
        .form-group { margin-bottom: 22px; }
        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 7px;
        }
        .form-group input[type="text"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 13px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input[type="text"]:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #0f3b8a;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(15,59,138,0.09);
        }
        .form-group textarea { resize: vertical; min-height: 120px; }
        .form-divider { height: 1px; background: #e5e7eb; margin: 26px 0 22px; }

        .form-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn-primary {
            padding: 10px 22px;
            background: #0f172a;
            color: #f8fafc;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-primary:hover { background: #1e3a5f; }
        .btn-secondary {
            display: inline-block;
            padding: 10px 22px;
            background: #e2e8f0;
            color: #334155;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-family: inherit;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-secondary:hover { background: #cbd5e1; }

        footer {
            background: #121629;
            color: #94a3b8;
            text-align: center;
            padding: 14px 20px;
            font-size: 0.8rem;
            margin-top: 40px;
            border-top: 1px solid rgba(148,163,184,0.2);
        }

        @media (max-width: 600px) {
            .form-card { padding: 20px 16px; }
            .form-actions { flex-direction: column; }
            .btn-primary, .btn-secondary { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

    <header class="site-header">
        <div class="header-top">
            <span>Edition du <?= date('d/m/Y') ?> - Mise a jour continue</span>
            <span>Contact redaction : redaction@journalinfo.fr</span>
        </div>
        <div class="header-main">
            <div>
                <div class="brand-name">Journal d'Information</div>
                <div class="brand-tagline">Analyses, terrain et decryptage geopolitique</div>
            </div>
            <div class="admin-chip">Espace Administration</div>
        </div>
    </header>

    <main>
        <a href="/admin" class="back-link">&larr; Retour a la liste</a>

        <span class="title-badge">Espace edition</span>
        <h1 class="page-title">Creer un nouvel article</h1>
        <p class="page-subtitle">Renseignez les informations ci-dessous pour publier un article clair et bien structure.</p>

        <?php if ($dbError !== ''): ?>
            <div class="alert"><?= htmlspecialchars($dbError, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if ($errorCode === '1'): ?>
            <div class="alert">Le titre, la categorie et le contenu de l'article sont obligatoires.</div>
        <?php endif; ?>
        <?php if ($errorCode === 'category'): ?>
            <div class="alert">La categorie selectionnee est invalide.</div>
        <?php endif; ?>
        <?php if ($errorCode === 'local_image'): ?>
            <div class="alert">Une image utilise un chemin local (ex: C:\...). Utilisez un chemin web accessible.</div>
        <?php endif; ?>

        <div class="form-card">
            <form action="/admin/create" method="POST">

                <div class="form-group">
                    <label for="titre">Titre de l'article</label>
                    <input type="text" id="titre" name="titre"
                           value="<?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>"
                           placeholder="Ex : Situation geopolitique au Moyen-Orient..."
                           required>
                </div>

                <div class="form-group">
                    <label for="id_categorie">Categorie</label>
                    <select id="id_categorie" name="id_categorie" required>
                        <option value="">Selectionner une categorie</option>
                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= (int) $category['id_categorie'] ?>"
                                <?= $idCategorie === (int) $category['id_categorie'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars((string) $category['nom_categorie'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="details">Contenu de l'article</label>
                    <textarea id="details" name="details"><?= htmlspecialchars($details, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="form-divider"></div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Creer l'article</button>
                    <a href="/admin" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Journal d'Information. Tous droits reserves. &nbsp;|&nbsp; Mentions legales
    </footer>

    <script>
        tinymce.init({
            selector: '#details',
            height: 500,
            relative_urls: false,
            document_base_url: '/',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | image | help',
            images_upload_url: 'upload-image.php',
            automatic_uploads: true,
            images_file_types: 'jpg,jpeg,png,gif,webp',
            file_picker_types: 'image',
            file_picker_callback: function (callback, value, meta) {
                if (meta.filetype !== 'image') {
                    return;
                }

                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.addEventListener('change', function () {
                    const file = this.files && this.files[0] ? this.files[0] : null;
                    if (!file) {
                        return;
                    }

                    const formData = new FormData();
                    formData.append('file', file);

                    fetch('upload-image.php', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(function (response) {
                        if (!response.ok) {
                            return response.json().then(function (d) {
                                throw new Error(d.error || 'Upload failed');
                            });
                        }
                        return response.json();
                    })
                    .then(function (data) {
                        if (!data.location) {
                            throw new Error('Invalid upload response');
                        }
                        callback(data.location, { alt: file.name });
                    })
                    .catch(function (err) {
                        alert('Erreur upload image : ' + err.message);
                    });
                });

                input.click();
            },
            content_style: 'body { font-family: "Merriweather", Georgia, serif; font-size: 16px; }'
        });
    </script>
</body>
</html>
