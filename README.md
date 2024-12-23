# Projet PHP avec Docker

## **Structure du Projet**
- **`frontend/`** : Contient les fichiers HTML et CSS pour l'interface utilisateur.
- **`backend/`** : Contient le code PHP pour le traitement back-end, y compris l'authentification et la gestion des comptes.
- **`database/`** : Scripts SQL pour la création de la base de données et l'insertion de données de test.
- **`docker/`** : Configuration Docker pour déployer l'application dans des conteneurs.

---

## **Arborescence du Projet**
```plaintext
GIT_Projet/
├── backend/
│   ├── auth/
│   │   ├── login.php
│   │   ├── logout.php
│   │   ├── register.php
│   │   ├── activate.php
│   │   └── mail.php
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

## **Installation et Déploiement**

### **Prérequis**
Avant de commencer, assurez-vous d'avoir les outils suivants installés sur votre machine :
1. **Docker** : Pour créer et gérer les conteneurs. [Guide d'installation](https://docs.docker.com/get-docker/).
2. **Docker Compose** : Pour orchestrer plusieurs conteneurs. [Guide d'installation](https://docs.docker.com/compose/install/).

Étapes d'installation et de déploiement
1. Cloner le projet
Téléchargez le projet depuis GitHub sur votre machine locale :

```plaintext
git clone https://github.com/GIT-Projet/GIT_Projet.git
cd Git-Projet
```
2. Configurer l'environnement
Vérifiez que les configurations dans backend/config/config.php correspondent aux valeurs définies dans le fichier docker-compose.yml.
Assurez-vous que les ports définis dans docker-compose.yml (par exemple, 8080 pour le front-end et 8081 pour phpMyAdmin) sont disponibles.

3. Lancer les conteneurs Docker
Démarrez les services nécessaires à l'aide de Docker Compose :

```plaintext
docker-compose up -d
```
4. Accéder aux services
```plaintext   
Frontend (page d'inscription) : http://localhost:8080
phpMyAdmin : http://localhost:8081
```
5. Vérifier les conteneurs en cours d'exécution
Pour vous assurer que tout fonctionne correctement, exécutez la commande suivante :

```plaintext
docker ps
```
Tests Fonctionnels

1. Inscription
Accédez à la page d'inscription :
```plaintext
 http://localhost:8080/
````
Remplissez le formulaire avec les informations nécessaires :

Nom complet

Adresse email

Nom d'utilisateur

Mot de passe

Cliquez sur "S'inscrire".

Vérifiez que l'utilisateur est ajouté dans la base de données via mysql (table UserAccounts).

2. Activation du compte

Consultez l'email enregistré (via la configuration de PHPMailer).

Cliquez sur le lien d'activation reçu pour valider le compte.

3. Connexion
Accédez à la page de connexion : http://localhost:8080/frontend/login.html.

Entrez les identifiants (nom d'utilisateur et mot de passe).

Une fois connecté, vous serez redirigé vers une page d'accueil personnalisée.

4. Page d'accueil
   
Une fois connecté, vous verrez une page d'accueil personnalisée avec un message de bienvenue et des options.
