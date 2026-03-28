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
