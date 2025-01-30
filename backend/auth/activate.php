<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure la configuration de la base de données
require_once '../config/config.php';
require_once 'mail.php';

$message = ""; // Initialiser le message

try {
    // Désactiver temporairement la sortie HTML si nécessaire
    ob_start();

    // Vérifier si le token est présent dans l'URL
    if (isset($_GET['token']) && !empty($_GET['token'])) {
        $token = htmlspecialchars(trim($_GET['token']));

        // Connexion à la base de données
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($conn->connect_error) {
            throw new Exception("Erreur de connexion : " . $conn->connect_error);
        }

        // Vérifier si le token existe dans la base
        $stmt = $conn->prepare("SELECT id, email, activation_token FROM UserAccounts WHERE activation_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Vérifier si le token est déjà NULL
            if ($user['activation_token'] !== NULL) {
                // Mettre à jour le compte (activation_token à NULL)
                $stmt = $conn->prepare("UPDATE UserAccounts SET activation_token = NULL WHERE activation_token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Succès : Le compte a été activé
                    $message = "<p class='success'>Votre compte a été activé avec succès ! Vous pouvez maintenant vous connecter.</p>";
                } else {
                    // Aucun changement (token déjà utilisé)
                    $message = "<p class='error'>Ce token a déjà été utilisé ou est invalide.</p>";
                }
            } else {
                // Token déjà null (compte déjà activé)
                $message = "<p class='error'>Votre compte a déjà été activé.</p>";
            }
        } else {
            // Aucun utilisateur trouvé avec ce token
            $message = "<p class='error'>Token invalide ou expiré.</p>";
        }

        $stmt->close();
        $conn->close();
    } else {
        // Token manquant
        $message = "<p class='error'>Token manquant. Impossible d'activer le compte.</p>";
    }
} catch (Exception $e) {
    // Gestion des erreurs internes
    $message = "<p class='error'>Une erreur interne est survenue : " . $e->getMessage() . "</p>";
} finally {
    // Nettoyer le tampon pour éviter les sorties parasites
    if (ob_get_length()) {
        ob_end_clean();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
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

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #0066cc;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0055aa;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Afficher le message de succès ou d'erreur
        echo $message;
        ?>
        <a href="home.php" class="btn">Aller à l'accueil</a>
    </div>
</body>
</html>
