<?php
// Inclure la configuration de la base de données
require_once '../config/config.php';

// Vérifier que la méthode HTTP est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Vérification des champs obligatoires
    if (empty($username) || empty($password)) {
        die("Veuillez remplir tous les champs.");
    }

    // Connexion à la base de données
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    // Préparer la requête SQL pour vérifier l'utilisateur
    $stmt = $conn->prepare("SELECT password FROM UserAccounts WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    // Vérifier si l'utilisateur existe et que le mot de passe correspond
    if ($hashed_password && password_verify($password, $hashed_password)) {
        echo "Connexion réussie ! Bienvenue, $username.";
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
} else {
    die("Méthode HTTP non autorisée.");
}
?>
