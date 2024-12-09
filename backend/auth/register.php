<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start(); // Démarrer la mise en tampon de sortie pour éviter l'erreur "headers already sent"

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once 'mail.php'; // Inclure le fichier contenant la fonction d'envoi d'email

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

    // Génération d'un token d'activation unique
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

    if ($stmt->execute()) {
        // Envoi d'un email de confirmation
        require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
        require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';
        require 'PHPMailer-master/PHPMailer-master/src/Exception.php';

        // Création de l'objet PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // Paramètres du serveur SMTP
            $mail->isSMTP(); 
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'votre-email@gmail.com'; // Remplacer par votre email
            $mail->Password = 'votre-mot-de-passe-app'; // Utiliser un mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Expéditeur et destinataire
            $mail->setFrom('votre-email@gmail.com', 'Nom de votre service');
            $mail->addAddress($email, $fullname);

            // Contenu de l'email
            $activation_link = "http://localhost:8080/activate.php?token=" . urlencode($activation_token);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de votre inscription';
            $mail->Body = "
                <html>
                    <head><title>Activation de votre compte</title></head>
                    <body>
                        <p>Bonjour $fullname,</p>
                        <p>Merci de vous être inscrit ! Pour activer votre compte, veuillez cliquer sur ce lien : 
                        <a href='$activation_link'>$activation_link</a></p>
                        <p>Si vous n'avez pas demandé l'inscription, ignorez cet email.</p>
                    </body>
                </html>";

            // Envoi de l'email
            if ($mail->send()) {
                echo 'Un email de confirmation a été envoyé à votre adresse.';
            } else {
                echo 'L\'envoi de l\'email a échoué.';
            }
        } catch (Exception $e) {
            echo "Erreur d'envoi de l'email. Erreur : {$mail->ErrorInfo}";
        }

        // Enregistrer l'utilisateur dans la session
        $_SESSION['username'] = $username;
        $_SESSION['fullname'] = $fullname;

        // Redirection vers la page de connexion
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
