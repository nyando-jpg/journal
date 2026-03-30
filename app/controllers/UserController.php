<?php

namespace app\controllers;

use app\models\UserModel;
use Flight;
session_start();

class UserController
{
    private $userModel;

    public function __construct(UserModel $userModel) 
    {
        $this->userModel = $userModel;
    }

    // Inscription
    public function registerUser()
    {
        $name = Flight::request()->data->name;
        $email = Flight::request()->data->email;
        $password = Flight::request()->data->password;

        // Validation de base
        if (empty($name) || empty($email) || empty($password) ) {
            Flight::redirect('register?error=Veuillez remplir tous les champs');
            return;
        }

        // Créer un utilisateur
        if ($this->userModel->createUser($name, $email, $password)) {
            Flight::redirect('log');
        } else {
            Flight::redirect('register?error=Une erreur s\'est produite');
        }
    }

    // Connexion
    public function loginUser()
    {
        $name = Flight::request()->data->name; // Utilisation de 'nom' au lieu de 'email'
        $password = Flight::request()->data->password;
    
        // Récupérer l'utilisateur par nom
        $user = $this->userModel->getUserByName($name);    
        // Vérifier les informations
        if ($user && $user['mdp'] === $password) { // Comparaison avec le champ `mdp`
            $_SESSION['user_id'] = (int)$user['id_user']; // Utilisation de `id_client`
    
            // Vérifier si l'utilisateur est admin
            if ($this->userModel->isAdmin($user['id_user'])) { // Vérification admin avec `id_client`
                Flight::redirect('/admin/articles?q=&category=0'); // Redirection pour les admins
            } else {
                Flight::redirect('index'); // Redirection pour les utilisateurs normaux
            }
        } else {
            Flight::redirect('?error=Identifiants invalides'); // Redirection en cas d'erreur
        }
    }
    

    // Afficher la page de connexion
    public function loginPage()
    {
        Flight::render('login'); // Rendre la vue login.php
    }
    public function logPage()
    {
        Flight::render('log'); // Rendre la vue log.php
    }
    public function logPageUser()
    {
        Flight::render('login_user'); // Rendre la vue log.php
    }
    public function logPageAdmin()
    {
        Flight::render('login_admin'); // Rendre la vue log.php
    }

    public function registerPage()
    {
        Flight::render('register'); // Rendre la vue register.php
    }

    public function index()
    {
        Flight::render('index'); // Rendre la vue index.php
    }

}
