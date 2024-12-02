<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Informations de connexion à la base de données
define('DB_HOST', 'db');        // Nom du conteneur MySQL dans Docker
define('DB_USER', 'root');      // Nom d'utilisateur MySQL
define('DB_PASS', 'rootpassword'); // Mot de passe correct
define('DB_NAME', 'myapp'); // Nom de la base de données

// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion (" . $conn->connect_errno . ") : " . $conn->connect_error);
} else {
}

