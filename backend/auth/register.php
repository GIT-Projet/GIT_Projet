<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session en haut du fichier
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once 'mail.php'; // Inclure le fichier contenant la fonction sendConfirmationEmail

// Vérifier que la méthode HTTP est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification des champs obligatoires
    if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        die("Veuillez remplir tous les champs.");
    }

    // Vérification de la validité de l'adresse e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Adresse e-mail invalide.");
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

    // Générer un jeton d'activation unique
    $activation_token = bin2hex(random_bytes(16));

    // Connexion à la base de données
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    // Préparer la requête SQL pour insérer un nouvel utilisateur
    $stmt = $conn->prepare("INSERT INTO UserAccounts (fullname, email, username, password, activation_token) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $email, $username, $hashed_password, $activation_token);

    // Exécuter la requête et gérer les erreurs
    if ($stmt->execute()) {
        // Appeler la fonction pour envoyer l'email de confirmation
        sendConfirmationEmail($email, $fullname, $activation_token);

        // Enregistrer l'utilisateur dans la session
        $_SESSION['username'] = $username;
        $_SESSION['fullname'] = $fullname;

        // Redirection vers login.html
        header("Location: /frontend/login.html");
        exit();
    } else {
        if ($stmt->errno === 1062) { // Code d'erreur pour doublons
            die("Ce nom d'utilisateur ou cette adresse e-mail est déjà utilisé(e).");
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
