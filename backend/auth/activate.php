<?php
// Démarrer la session
session_start();

// Inclure la configuration de la base de données
require_once 'config/config.php'; //  chemin de fichier de configuration

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
    $stmt = $conn->prepare("SELECT id, email, activated FROM UserAccounts WHERE activation_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si le token est valide
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Si l'utilisateur n'est pas déjà activé
        if ($user['activated'] == 0) {
            // Activer le compte de l'utilisateur
            $stmt = $conn->prepare("UPDATE UserAccounts SET activated = 1 WHERE activation_token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            // Optionnel : Supprimer le token après activation
            $stmt = $conn->prepare("UPDATE UserAccounts SET activation_token = NULL WHERE activation_token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            // Message de succès
            echo "<p>Votre compte a été activé avec succès ! Vous pouvez maintenant vous connecter.</p>";

            // Vous pouvez rediriger l'utilisateur vers la page de connexion
            header("Location: login.php");  // Redirige vers la page de connexion
            exit();
        } else {
            // Si l'utilisateur est déjà activé
            echo "<p>Votre compte est déjà activé.</p>";
        }
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
