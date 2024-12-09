<?php
require_once __DIR__ . '/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/PHPMailer-master/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendConfirmationEmail($email, $fullname, $activation_token) {
    $mail = new PHPMailer(true);
    try {
        // Paramètres SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'siyadiarra@gmail.com'; // Remplacez par votre adresse email
        $mail->Password = 'rcer olhr ktbw biih'; // Remplacez par votre mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Activer le débogage SMTP (niveau 2 pour des détails plus complets)
        $mail->SMTPDebug = 2; // Niveau de débogage (0 = aucun, 1 = erreurs et messages, 2 = tout)

        // Expéditeur et destinataire
        $mail->setFrom('siyadiarra@gmail.com', 'SAE502');
        $mail->addAddress($email, $fullname);

        // Contenu de l'email
        $activation_link = "http://localhost:8080/activate.php?token=" . urlencode($activation_token);
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre inscription';
        $mail->Body = "
            <p>Bonjour $fullname,</p>
            <p>Merci de vous être inscrit ! Cliquez sur le lien pour activer votre compte :</p>
            <p><a href='$activation_link'>$activation_link</a></p>
        ";

        // Envoi de l'email
        $mail->send();
        echo 'Un email de confirmation a été envoyé à votre adresse.';
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
    }
}
?>
