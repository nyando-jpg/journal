<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $article ? 'Modifier' : 'Créer' ?> un Article - Admin</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
    <style>
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $article ? 'Modifier l\'article #' . htmlspecialchars($article['id']) : 'Créer un nouvel article' ?></h1>

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

        <form action="<?= htmlspecialchars($action) ?>" method="POST">
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" id="titre" name="titre" value="<?= $article ? htmlspecialchars($article['titre']) : '' ?>" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;" required>
            </div>

            <div class="form-group">
                <label for="id_categorie">Categorie</label>
                <select id="id_categorie" name="id_categorie" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;" required>
                    <option value="">Selectionner une categorie</option>
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

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <?= $article ? 'Mettre à jour' : 'Créer l\'article' ?>
                </button>
                <a href="/admin/articles" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

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
