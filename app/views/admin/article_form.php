<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $article ? 'Modifier' : 'Créer' ?> un Article - Admin</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Merriweather", Georgia, serif;
            line-height: 1.8;
            color: #333;
            background-color: #f5f5f5;
        }

        /* Header */
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

        /* Main */
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

        /* Alert */
        .alert {
            padding: 12px 16px;
            margin-bottom: 18px;
            border-left: 4px solid #dc2626;
            border-radius: 0;
            background: #fef2f2;
            color: #7f1d1d;
            font-size: 0.88rem;
        }

        /* Form card */
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

        /* Actions */
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

        /* Footer */
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
            <span>Édition du <?= date('d/m/Y') ?> — Mise à jour continue</span>
            <span>Contact rédaction : redaction@journalinfo.fr</span>
        </div>
        <div class="header-main">
            <div>
                <div class="brand-name">Journal d'Information</div>
                <div class="brand-tagline">Analyses, terrain et décryptage géopolitique</div>
            </div>
            <div class="admin-chip">Espace Administration</div>
        </div>
    </header>

    <main>
        <a href="/admin/articles?q=&category=0" class="back-link">← Retour à la liste</a>

        <span class="title-badge">Espace édition</span>
        <h1 class="page-title">
            <?= $article ? 'Modifier l\'article #' . htmlspecialchars($article['id']) : 'Créer un nouvel article' ?>
        </h1>
        <p class="page-subtitle">Renseignez les informations ci-dessous pour publier un article clair et bien structuré.</p>

        <?php if (isset($_GET['error']) && $_GET['error'] === '1'): ?>
            <div class="alert">Le titre, la catégorie et le contenu de l'article sont obligatoires.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'category'): ?>
            <div class="alert">La catégorie sélectionnée est invalide.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'local_image'): ?>
            <div class="alert">Une image utilise un chemin local (ex: C:\...). Utilisez le bouton image de l'éditeur pour uploader vers /uploads.</div>
        <?php endif; ?>

        <div class="form-card">
            <form action="<?= htmlspecialchars($action) ?>" method="POST">

                <div class="form-group">
                    <label for="titre">Titre de l'article</label>
                    <input type="text" id="titre" name="titre"
                           value="<?= $article ? htmlspecialchars($article['titre']) : '' ?>"
                           placeholder="Ex : Situation géopolitique au Moyen-Orient…"
                           required>
                </div>

                <div class="form-group">
                    <label for="id_categorie">Catégorie</label>
                    <select id="id_categorie" name="id_categorie" required>
                        <option value="">Sélectionner une catégorie</option>
                        <?php foreach (($categories ?? []) as $category): ?>
                            <option
                                value="<?= (int) $category['id_categorie'] ?>"
                                <?= ($article && (int) ($article['id_categorie'] ?? 0) === (int) $category['id_categorie']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars((string) $category['nom_categorie']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="details">Contenu de l'article</label>
                    <textarea id="details" name="details"><?= $article ? htmlspecialchars($article['details']) : '' ?></textarea>
                </div>

                <div class="form-divider"></div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <?= $article ? 'Mettre à jour' : 'Créer l\'article' ?>
                    </button>
                    <a href="/admin/articles" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Journal d'Information. Tous droits réservés. &nbsp;|&nbsp; Mentions légales
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
            images_upload_url: '/admin/articles/upload-image',
            automatic_uploads: true,
            images_file_types: 'jpg,jpeg,png,gif,webp',
            file_picker_types: 'image',
            file_picker_callback: function (callback, value, meta) {
                if (meta.filetype !== 'image') return;
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.addEventListener('change', function () {
                    const file = this.files && this.files[0] ? this.files[0] : null;
                    if (!file) return;
                    const formData = new FormData();
                    formData.append('file', file);
                    fetch('/admin/articles/upload-image', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(r => r.ok ? r.json() : r.json().then(d => { throw new Error(d.error || 'Upload failed'); }))
                    .then(data => {
                        if (!data.location) throw new Error('Invalid upload response');
                        callback(data.location, { alt: file.name });
                    })
                    .catch(err => alert('Erreur upload image : ' + err.message));
                });
                input.click();
            },
            content_style: 'body { font-family: "Merriweather", Georgia, serif; font-size: 16px; }'
        });
    </script>
</body>
</html>