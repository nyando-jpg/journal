docker compose down
docker compose up -d

connexion mysql : docker exec -it site_app_mysql mysql -uroot -proot
nom base : gestion_the

back-office :
http://localhost:8000/simple-php-login/pages/login.php

front-office :
http://localhost:8000/simple-php-login/pages/actualites-FO.php