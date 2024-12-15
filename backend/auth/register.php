<?php
header('Content-Type: application/json'); // Assure un retour JSON
ob_start(); // Capture toute sortie parasite

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once 'mail.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullname = htmlspecialchars(trim($_POST['fullname'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $username = htmlspecialchars(trim($_POST['username'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Vérifications
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

        if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            echo json_encode(["error" => "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre."]);
            exit();
        }

        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            echo json_encode(["error" => "Erreur de connexion : " . $conn->connect_error]);
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $activation_token = bin2hex(random_bytes(16));
        $stmt = $conn->prepare("INSERT INTO UserAccounts (fullname, email, username, password, activation_token) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $email, $username, $hashed_password, $activation_token);

        if ($stmt->execute()) {
            if (sendConfirmationEmail($email, $fullname, $activation_token)) {
                echo json_encode(["success" => "Inscription réussie. Un e-mail de confirmation a été envoyé."]);
            } else {
                echo json_encode(["error" => "Inscription réussie, mais l'e-mail n'a pas pu être envoyé."]);
            }
            exit();
            
        } else {
            if ($stmt->errno === 1062) {
                echo json_encode(["error" => "Ce nom d'utilisateur ou cette adresse e-mail est déjà utilisé(e)."]);
            } else {
                echo json_encode(["error" => "Erreur lors de l'insertion : " . $stmt->error]);
            }
            exit();
        }
    } else {
        echo json_encode(["error" => "Méthode HTTP non autorisée."]);
        exit();
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Erreur interne : " . $e->getMessage()]);
} finally {
    ob_end_clean(); // Supprime toute sortie indésirable
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>
