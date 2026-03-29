<?php
use app\controllers\UserController;
use app\controllers\ArticleController;
use flight\Engine;
use flight\net\Router;

require_once 'services.php';

/**
 * @var Router $router
 * @var Engine $app
 */
$userModel = Flight::UserModel();
$UserController = new UserController($userModel);

$articleModel = Flight::ArticleModel();
$categoryModel = Flight::CategoryModel();
$ArticleController = new ArticleController($articleModel, $categoryModel);

// Routes principales
$router->get('/', [$UserController, 'logPageAdmin']);
$router->get('/loginAdmin', [$UserController, 'logPageAdmin']);
$router->post('/login_admin', [$UserController, 'loginUser']);

// Routes CRUD Articles (BackOffice)
$router->get('/admin/articles', [$ArticleController, 'index']);
$router->get('/admin/articles/create', [$ArticleController, 'create']);
$router->post('/admin/articles/store', [$ArticleController, 'store']);
$router->post('/admin/articles/upload-image', [$ArticleController, 'uploadImage']);
$router->get('/admin/articles/view/@id', [$ArticleController, 'adminShow']);
$router->get('/admin/articles/edit/@id', [$ArticleController, 'edit']);
$router->post('/admin/articles/update/@id', [$ArticleController, 'update']);
$router->get('/admin/articles/delete/@id', [$ArticleController, 'delete']);

// Routes FrontOffice (public)
$router->get('/actualites', [$ArticleController, 'home']);           // Liste des articles
$router->get('/article/@id', [$ArticleController, 'show']);          // Détail d'un article
















