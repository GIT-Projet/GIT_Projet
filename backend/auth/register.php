<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once 'mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        die("Veuillez remplir tous les champs.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Adresse e-mail invalide.");
    }

    if ($password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas.");
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        die("Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.");
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $activation_token = bin2hex(random_bytes(16));

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO UserAccounts (fullname, email, username, password, activation_token) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $email, $username, $hashed_password, $activation_token);

    if ($stmt->execute()) {
        sendConfirmationEmail($email, $fullname, $activation_token);

        // Aucun texte ou espace avant cette ligne
        $_SESSION['username'] = $username;
        $_SESSION['fullname'] = $fullname;

        exit();
    } else {
        if ($stmt->errno === 1062) {
            die("Ce nom d'utilisateur ou cette adresse e-mail est déjà utilisé(e).");
        }
        die("Erreur : " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    die("Méthode HTTP non autorisée.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Lien vers le fichier CSS externe -->
    <link rel="stylesheet" href="style.css">
</head>
</html>
