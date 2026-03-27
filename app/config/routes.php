<?php
use app\controllers\UserController;
use flight\Engine;
use flight\net\Router;

require_once 'services.php';

/** 
 * @var Router $router 
 * @var Engine $app
 */
$userModel = Flight::UserModel();
$UserController = new UserController($userModel);

// Routes principales
$router->get('/', [$UserController, 'logPageAdmin']);
$router->get('/loginAdmin', [$UserController, 'logPageAdmin']);
$router->post('/login_admin', [$UserController, 'loginUser']);
















