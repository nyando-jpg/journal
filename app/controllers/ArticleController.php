<?php

namespace app\controllers;

use app\models\ArticleModel;
use Flight;

class ArticleController
{
    protected ArticleModel $articleModel;

    public function __construct(ArticleModel $articleModel)
    {
        $this->articleModel = $articleModel;
    }

    /**
     * Afficher la liste des articles (BackOffice)
     */
    public function index(): void
    {
        $articles = $this->articleModel->getAll();
        Flight::render('admin/articles', ['articles' => $articles]);
    }

    /**
     * Afficher un article complet en mode admin
     */
    public function adminShow(int $id): void
    {
        $article = $this->articleModel->getById($id);

        if (!$article) {
            Flight::redirect('/admin/articles?error=notfound');
            return;
        }

        Flight::render('admin/article_show', ['article' => $article]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(): void
    {
        Flight::render('admin/article_form', [
            'article' => null,
            'action' => '/admin/articles/store'
        ]);
    }

    /**
     * Enregistrer un nouvel article
     */
    public function store(): void
    {
        $details = Flight::request()->data->details ?? '';

        if (empty($details)) {
            Flight::redirect('/admin/articles/create?error=1');
            return;
        }

        [$details, $invalidImageSources] = $this->normalizeImageSources($details);
        if (!empty($invalidImageSources)) {
            Flight::redirect('/admin/articles/create?error=local_image');
            return;
        }

        $this->articleModel->create($details);
        Flight::redirect('/admin/articles?success=created');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(int $id): void
    {
        $article = $this->articleModel->getById($id);

        if (!$article) {
            Flight::redirect('/admin/articles?error=notfound');
            return;
        }

        Flight::render('admin/article_form', [
            'article' => $article,
            'action' => '/admin/articles/update/' . $id
        ]);
    }

    /**
     * Mettre à jour un article
     */
    public function update(int $id): void
    {
        $details = Flight::request()->data->details ?? '';

        if (empty($details)) {
            Flight::redirect('/admin/articles/edit/' . $id . '?error=1');
            return;
        }

        [$details, $invalidImageSources] = $this->normalizeImageSources($details);
        if (!empty($invalidImageSources)) {
            Flight::redirect('/admin/articles/edit/' . $id . '?error=local_image');
            return;
        }

        $this->articleModel->update($id, $details);
        Flight::redirect('/admin/articles?success=updated');
    }

    /**
     * Supprimer un article
     */
    public function delete(int $id): void
    {
        $this->articleModel->delete($id);
        Flight::redirect('/admin/articles?success=deleted');
    }

    /**
     * Upload d'image TinyMCE vers /uploads
     */
    public function uploadImage(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Flight::json(['error' => 'Method not allowed'], 405);
            return;
        }

        if (empty($_FILES)) {
            Flight::json(['error' => 'No file uploaded'], 400);
            return;
        }

        $file = null;
        foreach ($_FILES as $uploadedFile) {
            if (is_array($uploadedFile) && isset($uploadedFile['tmp_name'])) {
                $file = $uploadedFile;
                break;
            }
        }

        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            Flight::json(['error' => 'Upload failed'], 400);
            return;
        }

        $tmpName = $file['tmp_name'];
        $maxSize = 5 * 1024 * 1024;
        if (($file['size'] ?? 0) > $maxSize) {
            Flight::json(['error' => 'File too large (max 5MB)'], 400);
            return;
        }

        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        $mimeType = '';
        $imageInfo = @getimagesize($tmpName);
        if (is_array($imageInfo) && isset($imageInfo['mime'])) {
            $mimeType = strtolower((string) $imageInfo['mime']);
        }

        if ($mimeType === '' && function_exists('mime_content_type')) {
            $mimeType = strtolower((string) mime_content_type($tmpName));
        }

        $extensionFromName = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!isset($allowedMimeTypes[$mimeType]) && !in_array($extensionFromName, $allowedExtensions, true)) {
            Flight::json(['error' => 'Invalid image type'], 400);
            return;
        }

        $uploadsDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads';
        if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0775, true) && !is_dir($uploadsDir)) {
            Flight::json(['error' => 'Cannot create upload directory'], 500);
            return;
        }

        $targetExtension = $allowedMimeTypes[$mimeType] ?? ($extensionFromName === 'jpeg' ? 'jpg' : $extensionFromName);
        if ($targetExtension === '') {
            $targetExtension = 'jpg';
        }

        $fileName = 'img_' . date('Ymd_His') . '_' . bin2hex(random_bytes(5)) . '.' . $targetExtension;
        $targetPath = $uploadsDir . DIRECTORY_SEPARATOR . $fileName;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            Flight::json(['error' => 'Cannot move uploaded file'], 500);
            return;
        }

        Flight::json(['location' => '/uploads/' . $fileName], 200);
    }

    /**
     * Rejette les chemins locaux (Windows/file://) et convertit si un fichier existe deja dans /uploads.
     *
     * @return array{0:string,1:array<int,string>}
     */
    protected function normalizeImageSources(string $html): array
    {
        if (stripos($html, '<img') === false) {
            return [$html, []];
        }

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $invalidSources = [];
        $uploadsDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads';

        /** @var \DOMElement $image */
        foreach ($doc->getElementsByTagName('img') as $image) {
            $src = trim((string) $image->getAttribute('src'));
            if ($src === '') {
                continue;
            }

            $decodedSrc = rawurldecode($src);
            $decodedTwiceSrc = rawurldecode($decodedSrc);
            $isLocalPath = (bool) preg_match('/^(file:\/\/\/|[a-zA-Z]:([\\\/]|%5[cC])|\\\\)/i', $src)
                || (bool) preg_match('/^(file:\/\/\/|[a-zA-Z]:[\\\/]|\\\\)/i', $decodedSrc)
                || (bool) preg_match('/^(file:\/\/\/|[a-zA-Z]:[\\\/]|\\\\)/i', $decodedTwiceSrc);
            if (!$isLocalPath) {
                continue;
            }

            $normalized = str_replace('\\', '/', $decodedTwiceSrc);
            $basename = basename(parse_url($normalized, PHP_URL_PATH) ?: $normalized);
            $candidate = $uploadsDir . DIRECTORY_SEPARATOR . $basename;

            if ($basename !== '' && file_exists($candidate)) {
                $image->setAttribute('src', '/uploads/' . $basename);
                continue;
            }

            $invalidSources[] = $src;
        }

        $cleanHtml = $doc->saveHTML() ?: $html;
        $cleanHtml = preg_replace('/^<\?xml[^>]+\?>\s*/i', '', $cleanHtml) ?? $cleanHtml;

        return [$cleanHtml, $invalidSources];
    }

    // ============ FRONTOFFICE ============

    /**
     * Page d'accueil - Liste des articles (FrontOffice)
     */
    public function home(): void
    {
        $articles = $this->articleModel->getAll();
        Flight::render('front/home', ['articles' => $articles]);
    }

    /**
     * Afficher un article complet (FrontOffice)
     */
    public function show(int $id): void
    {
        $article = $this->articleModel->getById($id);

        if (!$article) {
            Flight::redirect('/actualites?error=notfound');
            return;
        }

        Flight::render('front/article', ['article' => $article]);
    }
}
