
# Projet en PHP avec Docker

## Structure
- `frontend/` : Contient les fichiers HTML et CSS pour l'interface utilisateur.
- `backend/` : Contient le code PHP pour le traitement back-end.
- `database/` : Scripts SQL pour la base de données MySQL.
- `docker/` : Configuration Docker.

## **Arborescence du Projet**
```plaintext
GIT_Projet/
├── backend/
│   ├── auth/
│   │   ├── login.php
│   │   ├── logout.php
│   │   ├── register.php
│   │   └── home.php
│   ├── config/
│   │   ├── config.php
│   │   └── index.php
│   └── Dockerfile
├── database/
│   ├── schema.sql
│   └── seed_data.sql
├── docker/
│   └── docker-compose.yml
├── frontend/
│   ├── login.html
│   ├── register.html
│   ├── style.css
│   └── home.html
└── README.md
```
---

## **Installation et Déploiement**

### **Prérequis :**
1. **Docker** installé sur votre machine.
2. **Docker Compose** pour gérer les conteneurs.

### **Étapes des instructions :**
1. Clonez le projet :
   ```bash
   git clone https://github.com/votre-utilisateur/votre-repo.git
   cd votre-repo
   ```
3. Lancez Docker Compose :
   ```bash
   docker-compose up
   ```
4. Accédez aux services :

- Frontend (page de connexion) : `http://localhost:8080`.
- phpMyAdmin : `http://localhost:8081`.

5. Vérifiez que les conteneurs fonctionnent :
   ```bash
   docker ps
   
Tests Fonctionnels
Inscription :

Rendez-vous sur `http://localhost:8080/frontend/register.html`.
Remplissez le formulaire et validez.

Vérifiez l'enregistrement dans phpMyAdmin (table UserAccounts).
Connexion :
Accédez à `http://localhost:8080/frontend/login.html`.

Entrez vos identifiants et connectez-vous.

Page d'accueil :
Une fois connecté, accédez à la page d'accueil personnalisée

