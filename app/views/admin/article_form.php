<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $article ? 'Modifier' : 'Créer' ?> un Article - Admin</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
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
        main {
            max-width: 900px;
            margin: 26px auto;
            padding: 0 18px 32px;
        }
        .page-title {
            margin: 0 0 16px 0;
            font-size: 2rem;
            color: #1f2937;
            font-family: "Playfair Display", "Times New Roman", serif;
            line-height: 1.25;
        }
        .title-accent {
            display: inline-block;
            margin: 0 0 10px 0;
            padding: 4px 10px;
            border-radius: 999px;
            background: #0b1220;
            color: #dbeafe;
            font-size: 0.78rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-weight: 700;
        }
        .page-subtitle {
            margin: 0 0 20px 0;
            color: #64748b;
            font-size: 0.98rem;
        }
        .form-container {
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 14px 38px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
            position: relative;
        }
        .form-container::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 4px;
            border-radius: 12px 12px 0 0;
            background: linear-gradient(90deg, #020617, #0b1220, #1e3a8a);
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
            font-size: 0.95rem;
        }
        .form-group input[type="text"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-group input[type="text"]:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }
        .btn-primary:hover {
            background-color: #2563eb;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            transform: translateY(-1px);
        }
        .btn-secondary {
            background-color: #e2e8f0;
            color: #334155;
        }
        .btn-secondary:hover {
            background-color: #cbd5e1;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            border-left: 4px solid;
        }
        .alert-danger {
            background-color: #fef2f2;
            color: #7f1d1d;
            border-left-color: #dc2626;
        }
        .back-link {
            display: inline-block;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 10px;
            transition: color 0.2s ease;
        }
        .back-link:hover {
            color: #334155;
        }
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }
            .form-actions {
                flex-direction: column;
            }
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <main>
        <a href="/admin/articles?q=&category=0" class="back-link">← Retour à la liste</a>

        <span class="title-accent">Espace édition</span>
        <h1 class="page-title"><?= $article ? 'Modifier l\'article #' . htmlspecialchars($article['id']) : 'Créer un nouvel article' ?></h1>
        <p class="page-subtitle">Renseignez les informations ci-dessous pour publier un article clair et bien structuré.</p>

        <?php if (isset($_GET['error']) && $_GET['error'] === '1'): ?>
            <div class="alert alert-danger">
                Le titre, la categorie et le contenu de l'article sont obligatoires.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'category'): ?>
            <div class="alert alert-danger">
                La categorie selectionnee est invalide.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'local_image'): ?>
            <div class="alert alert-danger">
                Une image utilise un chemin local (ex: C:\...). Utilisez le bouton image de l'editeur pour uploader vers /uploads.
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="<?= htmlspecialchars($action) ?>" method="POST">
                <div class="form-group">
                    <label for="titre">Titre de l'article</label>
                    <input type="text" id="titre" name="titre" value="<?= $article ? htmlspecialchars($article['titre']) : '' ?>" required>
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

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?= $article ? 'Mettre à jour' : 'Créer l\'article' ?>
                    </button>
                    <a href="/admin/articles" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </main>

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

                    fetch('/admin/articles/upload-image', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    })
                        .then(function (response) {
                            if (!response.ok) {
                                return response.json().then(function (data) {
                                    throw new Error(data.error || 'Upload failed');
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
                        .catch(function (error) {
                            alert('Erreur upload image: ' + error.message);
                        });
                });

                input.click();
            },
            content_style: 'body { font-family: "Merriweather", Georgia, serif; font-size: 16px; }'
        });
    </script>
</body>
</html>
