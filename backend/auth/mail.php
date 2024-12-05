<?php
// Inclure PHPMailer à partir du dossier où vous l'avez placé dans votre projet
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Fonction pour envoyer un email de confirmation
function sendConfirmationEmail($email, $fullname, $activation_token) {
    // Création de l'objet PHPMailer
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);  // Utiliser l'instance avec la gestion des exceptions

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();  // Utiliser SMTP pour l'envoi d'email
        $mail->Host = 'smtp.gmail.com';  // Hôte SMTP de Gmail
        $mail->SMTPAuth = true;          // Activer l'authentification SMTP
        $mail->Username = 'siyadiarra@gmail.com';  // Remplacer par votre adresse email Gmail
        $mail->Password = 'Siyadiarra2003*';     // Utiliser un mot de passe d'application si l'authentification 2FA est activée
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Sécurisation de la connexion
        $mail->Port = 587;  // Port SMTP pour Gmail (587 pour TLS)

        // Expéditeur et destinataire
        $mail->setFrom('siyadiarra@gmail.com', 'SAE502);  // L'email de l'expéditeur
        $mail->addAddress($email, $fullname);  // L'email et le nom du destinataire

        // Contenu de l'email
        $activation_link = "http://localhost:8080//activate.php?token=" . urlencode($activation_token);  // Lien d'activation a modifier si c pas le bon
        $mail->isHTML(true);  // Spécifier que l'email sera en HTML
        $mail->Subject = 'Confirmation de votre inscription';  // Sujet de l'email
        $mail->Body    = "
            <html>
                <head>
                    <title>Activation de votre compte</title>
                </head>
                <body>
                    <p>Bonjour $fullname,</p>
                    <p>Merci de vous être inscrit sur notre site ! Pour activer votre compte, veuillez cliquer sur le lien ci-dessous :</p>
                    <p><a href='$activation_link'>$activation_link</a></p>
                    <p>Si vous n'avez pas demandé à vous inscrire, veuillez ignorer ce message.</p>
                </body>
            </html>
        ";  // Corps de l'email avec lien d'activation

        // Envoi de l'email
        if ($mail->send()) {
            echo 'Un email de confirmation a été envoyé à votre adresse.';
        } else {
            echo 'L\'envoi de l\'email a échoué.';
        }
    } catch (Exception $e) {
        // Capture des erreurs d'envoi d'email
        echo "Erreur d'envoi de l'email. Erreur : {$mail->ErrorInfo}";
    }
}
?>
