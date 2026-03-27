# 🚀 GUIDE COMPLET - LANCER LE PROJET

## 📋 Prérequis

Tu dois avoir installé:
- ✅ **Docker Desktop** (en cours d'exécution)

---

## 🔧 ÉTAPE 1: Naviguer dans le répertoire du projet

Ouvre **PowerShell** et va au dossier du projet:

```powershell
cd c:\xampp\htdocs\journal
```

---

## ▶️ ÉTAPE 2: Démarrer les conteneurs Docker

Lance Docker Compose:

```powershell
docker-compose up -d
```

## ⏳ ÉTAPE 3: Vérifier que tout est prêt

Attends 10 secondes puis vérifie l'état des conteneurs:

```powershell
Start-Sleep -Seconds 10
docker-compose ps
```

**Tu dois voir:**
```
NAME             IMAGE               STATUS
site_app_mysql   mysql:8.0           Up ... (healthy)
site_app_php     cueillette-flight   Up ...
```

---

## 🌐 ÉTAPE 4: Accéder à l'application web

Ouvre ton navigateur et va à:

👉 **http://localhost:8000**


## 🗄️ ACCÉDER À LA BASE DE DONNÉES MYSQL

### **Option 1: Avec un client MySQL graphique**

**Coordonnées de connexion:**
```
Host:     localhost
Port:     3306
User:     root
Password: root
Database: gestion_journal
```

### **Option 2: Avec la ligne de commande PowerShell**

**Se connecter à MySQL en mode interactif:**
```powershell
docker exec -it site_app_mysql mysql -uroot -proot
```

**Voir les bases de données:**
```powershell
docker exec site_app_mysql mysql -uroot -proot -e "SHOW DATABASES;"
```

**Voir les tables:**
```powershell
docker exec site_app_mysql mysql -uroot -proot gestion_journal -e "SHOW TABLES;"
```

**Voir le contenu d'une table:**
```powershell
docker exec site_app_mysql mysql -uroot -proot gestion_journal -e "SELECT * FROM journal_user;"
```

**Insérer des données depuis data.sql:**
```powershell
docker exec site_app_mysql mysql -uroot -proot gestion_journal < ./sql/data.sql
```

---

## 📊 TABLES DISPONIBLES

```
✅ journal_user              (utilisateurs & admins)
```

---

## 🎯 COMMANDES UTILES

### **Voir les logs en temps réel:**
```powershell
# Logs PHP/Apache
docker-compose logs -f site_app_php

# Logs MySQL
docker-compose logs -f site_app_mysql

# Tous les logs
docker-compose logs -f
```

### **Redémarrer les conteneurs:**
```powershell
docker-compose restart
```

### **Arrêter les conteneurs:**
```powershell
docker-compose down
```

### **Relancer après un arrêt:**
```powershell
docker-compose up -d
```

### **Supprimer TOUT (données incluses):**
```powershell
docker-compose down --volumes
docker-compose up -d
```

### **Vérifier l'état:**
```powershell
docker-compose ps

```
### **nouvelle base**
```powershell
docker-compose down -v
docker-compose up
---

## 🌐 URLS IMPORTANTES

| URL | Description |
|-----|-------------|
| http://localhost:8000/ | Page d'accueil |
| http://localhost:8000/loginUser | Login utilisateur |
| http://localhost:8000/loginAdmin | Login administrateur |
| http://localhost:8000/register | Créer un compte |
| localhost:3306 | Base de données MySQL |

---

## 🔐 IDENTIFIANTS MYSQL

Pour te connecter à la base de données MySQL:

```
Host:     localhost
Port:     3306
User:     root
Password: root
Database: gestion_journal
```

---

## 📁 STRUCTURE DU PROJET

```
cueillette/
├── app/
│   ├── config/           # Configuration et base de données
│   ├── controllers/      # Logique métier
│   ├── models/           # Modèles de données
│   └── views/            # Templates HTML
├── assets/
│   ├── css/              # Feuilles de styles
│   ├── js/               # JavaScript
│   └── fonts/            # Polices
├── sql/
│   ├── script.sql        # Structure de la base (créée automatiquement)
│   └── data.sql          # Données d'exemple
├── docker/
│   ├── Dockerfile.php    # Configuration PHP/Apache
│   └── apache.conf       # Configuration Apache
├── docker-compose.yml    # Configuration Docker
├── .htaccess             # Rewrite rules pour les URLs
└── index.php             # Point d'entrée principal
```

---

## 🐛 TROUBLESHOOTING

### **La page affiche "Internal Server Error"**
```powershell
docker logs site_app_php
```
Vérifie les logs pour voir l'erreur exacte.

### **Les CSS ne s'affichent pas**
- Vide le cache du navigateur: **Ctrl+F5**
- Assure-toi que `/assets/css/` est accessible

### **La base de données n'est pas accessible**
```powershell
docker exec site_app_mysql mysql -uroot -proot -e "SHOW DATABASES;"
```
Vérifie que `gestion_the` existe.

### **Les conteneurs ne démarrent pas**
```powershell
docker-compose down --volumes
docker system prune -f
docker-compose up -d
```

---

## ✅ CONFIGURATION ACTUELLE

- ✅ Docker Compose avec `site_app_mysql` et `site_app_php`
- ✅ MySQL 8.0 avec base `gestion_the`
- ✅ PHP 8-Apache avec mod_rewrite activé
- ✅ CSS chargées correctement
- ✅ Routes rewrite configurées
- ✅ Base de données prête à l'emploi

---

## 📝 NOTES IMPORTANTES

- **Les données sont persistantes** dans `mysql_data` volume
- **Les logs Apache** sont visibles via `docker logs`
- **Les fichiers statiques** (CSS, JS) sont dans `/assets/`
- **La configuration DB** est dans `app/config/config.php`

---

## 🚀 PRÊT À DÉMARRER!

Exécute simplement:

```powershell
docker-compose up -d
```

Puis visite: **http://localhost:8000**

**Bonne chance! 🎉**
