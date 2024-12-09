<?php
// Inclure PHPMailer
require_once __DIR__ . '/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/PHPMailer-master/PHPMailer-master/src/Exception.php';

// Importer les classes nécessaires
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Fonction pour envoyer un email de confirmation
function sendConfirmationEmail($email, $fullname, $activation_token) {
    $mail = new PHPMailer(true); // PHPMailer avec gestion des exceptions
    try {
        // Paramètres SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'siyadiarra@gmail.com';
        $mail->Password = 'Siyadiarra2003*';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Expéditeur et destinataire
        $mail->setFrom('siyadiarra@gmail.com', 'SAE502');
        $mail->addAddress($email, $fullname);

        // Contenu de l'email
        $activation_link = "http://localhost:8080/activate.php?token=" . urlencode($activation_token);
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre inscription';
        $mail->Body = "
            <html>
                <body>
                    <p>Bonjour $fullname,</p>
                    <p>Merci de vous être inscrit ! Cliquez sur le lien pour activer votre compte :</p>
                    <p><a href='$activation_link'>$activation_link</a></p>
                </body>
            </html>
        ";

        // Envoi
        $mail->send();
        echo 'Un email de confirmation a été envoyé.';
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
    }
}
?>
