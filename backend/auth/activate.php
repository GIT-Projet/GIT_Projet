<?php
// Démarrer la session
session_start();

// Inclure la configuration de la base de données
require_once '../config/config.php'; // chemin de fichier de configuration
require_once 'mail.php'; // Ajout du point-virgule ici

// Vérifier si le token est passé en paramètre via GET
if (isset($_GET['token']) && !empty($_GET['token'])) {
    // Récupérer le token
    $token = $_GET['token'];

    // Connexion à la base de données
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }

    // Rechercher le token dans la base de données
    $stmt = $conn->prepare("SELECT id, email FROM UserAccounts WHERE activation_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si le token est valide
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Activer le compte de l'utilisateur en supprimant le token
        $stmt = $conn->prepare("UPDATE UserAccounts SET activation_token = NULL WHERE activation_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        // Message de succès avant la redirection
        echo "<p>Votre compte a été activé avec succès ! Vous allez être redirigé vers la page de connexion.</p>";

        // Rediriger après 3 secondes
        header("refresh:3;url=login.php");  // Redirige vers la page de connexion après 3 secondes
        exit(); // Ne pas oublier de terminer le script après la redirection
    } else {
        // Si le token n'est pas valide
        echo "<p>Token invalide ou expiré.</p>";
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
} else {
    echo "<p>Token manquant. Impossible d'activer le compte.</p>";
}
?>
