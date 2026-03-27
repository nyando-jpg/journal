# 🚀 SETUP - GESTION DU JOURNAL

Guide pour configurer le projet en local sur votre machine.

---

## 📋 Prérequis

- ✅ **Docker Desktop** (installé et en cours d'exécution)
- ✅ **Git** (pour cloner le projet)
- ✅ **PowerShell** ou Terminal

---

## 🔧 INSTALLATION RAPIDE (POUR 2 PERSONNES)

### 1️⃣ Cloner le projet

```powershell
git clone https://github.com/[username]/gestion-journal.git
cd gestion-journal
```

### 2️⃣ Créer le fichier `.env` local

```powershell
# Windows
copy .env.example .env

# Mac/Linux
cp .env.example .env
```

**Modifie `.env` si besoin** (généralement pas nécessaire avec les valeurs par défaut).

### 3️⃣ Démarrer Docker

```powershell
docker-compose up -d
```

Attends 10 secondes que MySQL démarre.

### 4️⃣ Vérifier que tout fonctionne

```powershell
docker-compose ps
```

### 5️⃣ Accéder à l'application

👉 **http://localhost:8000**

---

## 🔄 WORKFLOW COLLABORATIF

### Avant de commencer à coder

```powershell
# 1. Récupérer les dernières modifications
git pull origin main

# 2. Démarrer Docker (si arrêté)
docker-compose up -d
```

### Pendant que vous codez

Chacun code de son côté dans son éditeur. Les fichiers à modifier sont dans:
- `app/controllers/`
- `app/models/`
- `app/views/`
- `assets/`
- `sql/`

### Après vos modifications

```powershell
# 1. Voir les changements
git status

# 2. Ajouter les fichiers
git add .

# 3. Commit local
git commit -m "Description des changements"

# 4. Pousser vers GitHub
git push origin main
```

---

## ⚠️ NE PAS VERSIONNER

Ces fichiers sont dans `.gitignore` et NE doivent PAS être pushés:

```
❌ mysql_data/          (données locales)
❌ vendor/              (dépendances)
❌ app/log/*            (logs locaux)
❌ app/cache/*          (cache local)
❌ .env                 (fichier local)
❌ .vscode/, .idea/     (paramètres IDE)
```

Si vous avez besoin de config partagée, utilisez `.env.example`.

---

## 🐛 TROUBLESHOOTING

### Docker ne démarre pas

```powershell
docker-compose restart
```

### Les changements d'une personne ne s'affichent pas

```powershell
git pull origin main
docker-compose restart
```

### Erreur de conflit (merge conflict)

Git vous préviendra. Ouvrez le fichier en conflit et choisissez la version à garder.

---

## 📚 RESSOURCES

- [README_LANCER.md](README_LANCER.md) - Guide complet pour lancer le projet
- [GitHub Desktop Guide](https://docs.github.com/en/desktop)
- [Git Documentation](https://git-scm.com/doc)

---

**Questions? Consultez le README_LANCER.md!** 📖
