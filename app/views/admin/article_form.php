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

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Le contenu de l'article ne peut pas être vide.
            </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($action) ?>" method="POST">
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
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 16px; }'
        });
    </script>
</body>
</html>
