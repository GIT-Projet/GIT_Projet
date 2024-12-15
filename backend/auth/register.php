<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Toujours démarrer la session en premier
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure les fichiers de configuration et de gestion des mails
require_once '../config/config.php';
require_once 'mail.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>
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
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            outline: none;
        }
        input[type="submit"] {
            background-color: #0066cc;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0055aa;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        echo json_encode(["error" => "Veuillez remplir tous les champs."]);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Adresse e-mail invalide."]);
        exit();
    }

    if ($password !== $confirm_password) {
        echo json_encode(["error" => "Les mots de passe ne correspondent pas."]);
        exit();
    }

    // Vérification de la force du mot de passe
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        echo json_encode(["error" => "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre."]);
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $activation_token = bin2hex(random_bytes(16));

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        echo json_encode(["error" => "Erreur de connexion : " . $conn->connect_error]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO UserAccounts (fullname, email, username, password, activation_token) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $email, $username, $hashed_password, $activation_token);

    if ($stmt->execute()) {
        sendConfirmationEmail($email, $fullname, $activation_token);

        $_SESSION['username'] = $username;
        $_SESSION['fullname'] = $fullname;

        // Redirection vers home.php
        echo json_encode(["success" => "Inscription réussie."]);
        exit();
    } else {
        if ($stmt->errno === 1062) { // Code d'erreur pour doublons
            echo json_encode(["error" => "Ce nom d'utilisateur ou cette adresse e-mail est déjà utilisé(e)."]);
            exit();
        }
        echo json_encode(["error" => "Erreur : " . $stmt->error]);
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Méthode HTTP non autorisée."]);
    exit();
}
?>
