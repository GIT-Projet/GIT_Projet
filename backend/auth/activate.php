<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session en premier
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure la configuration de la base de données
require_once '../config/config.php';
require_once 'mail.php';

// Désactiver temporairement la sortie HTML si nécessaire
ob_start();

// Vérifier si le token est passé en paramètre via GET
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    // Connexion à la base de données
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("<p class='error'>Erreur de connexion à la base de données : " . $conn->connect_error . "</p>");
    }

    // Rechercher le token dans la base de données
    $stmt = $conn->prepare("SELECT id, email FROM UserAccounts WHERE activation_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Activer le compte de l'utilisateur
        $stmt = $conn->prepare("UPDATE UserAccounts SET activation_token = NULL WHERE activation_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        // Rediriger après 3 secondes
        header("refresh:3;url=home.php");
        echo "<p class='success'>Votre compte a été activé avec succès ! Vous allez être redirigé vers la page de connexion.</p>";
    } else {
        echo "<p class='error'>Token invalide ou expiré.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p class='error'>Token manquant. Impossible d'activer le compte.</p>";
}

// Envoyer tout le contenu et libérer le tampon
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation de compte</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #002244, #004488);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        p {
            font-size: 16px;
            margin: 10px 0;
        }

        .success {
            color: #00ff00;
        }

        .error {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Le contenu PHP est affiché ici -->
    </div>
</body>
</html>
