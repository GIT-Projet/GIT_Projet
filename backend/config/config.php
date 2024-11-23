<?php
// Informations de connexion à la base de données
define('DB_HOST', 'localhost'); // Adresse du serveur MySQL (ou le nom du conteneur si Docker)
define('DB_USER', 'root');      // Nom d'utilisateur MySQL
define('DB_PASS', '');  // Mot de passe de l'utilisateur MySQL
define('DB_NAME', 'my_databases'); // Nom de la base de données
// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>
