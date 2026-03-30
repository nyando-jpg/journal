# Simple PHP Login

Mini projet independant sans Flight avec uniquement la fonctionnalite de login.

## Fichiers

- `config/config.php`: configuration base de donnees
- `config/db.php`: connexion PDO
- `pages/login.php`: formulaire + traitement login
- `pages/index.php`: page protegee apres connexion
- `pages/logout.php`: deconnexion

## URL

Avec ton setup Docker/Apache actuel, ouvre:

http://localhost:8000/simple-php-login/pages/login.php

## Comportement

- Verifie le login sur la table `journal_user`
- Compare `nom` + `mdp` comme dans le projet Flight actuel
- Stocke `user_id`, `user_name`, `is_admin` en session
- Redirige vers `pages/index.php` uniquement si la session est complete
