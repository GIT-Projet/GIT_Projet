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
    <style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #cce7ff, #0077b6); /* Fond amélioré vers un bleu agréable */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Conteneur du formulaire */
        .form-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px; /* Coin arrondi amélioré */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); /* Plus d'ombre pour un effet de profondeur */
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        /* Titre */
        .form-container h2 {
            margin-bottom: 25px;
            font-size: 2em;
            color: #004f7c; /* Couleur de titre plus vive */
        }

        /* Labels */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            text-align: left;
        }

        /* Champs de saisie */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #0077b6; /* Bleu plus visible */
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            background-color: #f0f8ff; /* Arrière-plan des champs plus doux */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #0056b3; /* Bleu plus foncé pour indiquer le focus */
            box-shadow: 0 0 8px rgba(0, 119, 182, 0.5);
            outline: none;
        }

        /* Bouton de soumission */
        button[type="submit"] {
            background-color: #0077b6;
            color: #ffffff;
            padding: 14px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button[type="submit"]:hover {
            background-color: #005f8a;
            transform: translateY(-2px);
        }

        /* Lien vers la page de connexion */
        .login-link {
            margin-top: 20px;
            font-size: 1em;
        }

        .login-link a {
            color: #0077b6;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Responsive design */
        @media (max-width: 500px) {
            .form-container {
                padding: 20px;
            }

            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="email"],
            button[type="submit"] {
                font-size: 0.9em;
            }
        }

        /* Page 3 */

        .welcome-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
        }

        .greeting {
            text-align: center;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2em;
            color: #333333;
            margin-bottom: 10px;
        }

        p {
            font-size: 1.2em;
            color: #666666;
        }

        .logout-btn {
            padding: 10px 20px;
            font-size: 1.2em;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .logout-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Votre contenu HTML va ici -->
</body>
</html>
