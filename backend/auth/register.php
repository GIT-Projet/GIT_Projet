<?php
// Inclure la configuration de la base de données
require_once '../config/config.php';

// Vérifier que la méthode HTTP est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification des champs obligatoires
    if (empty($username) || empty($password) || empty($confirm_password)) {
        die("Veuillez remplir tous les champs.");
    }

    // Vérifier la correspondance des mots de passe
    if ($password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas.");
    }

    // Vérification de la force du mot de passe
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        die("Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.");
    }

    // Hashage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Connexion à la base de données
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    // Préparer la requête SQL pour insérer un nouvel utilisateur
    $stmt = $conn->prepare("INSERT INTO UserAccounts (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    // Exécuter la requête et gérer les erreurs
    if ($stmt->execute()) {
        echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    } else {
        if ($stmt->errno === 1062) { // Code d'erreur pour doublons
            die("Ce nom d'utilisateur est déjà utilisé.");
        }
        die("Erreur : " . $stmt->error);
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
} else {
    die("Méthode HTTP non autorisée.");
}
?>
