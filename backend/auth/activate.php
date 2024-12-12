<!DOCTYPE html>
<html lang="en">
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
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Démarrer la session
        session_start();

        // Inclure la configuration de la base de données
        require_once '../config/config.php';
        require_once 'mail.php';

        // Vérifier si le token est passé en paramètre via GET
        if (isset($_GET['token']) && !empty($_GET['token'])) {
            // Récupérer le token
            $token = $_GET['token'];

            // Connexion à la base de données
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // Vérifier la connexion
            if ($conn->connect_error) {
                die("<p class='error'>Erreur de connexion à la base de données : " . $conn->connect_error . "</p>");
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

                // Avant la redirection, ne rien afficher d'autre
                echo "<p class='success'>Votre compte a été activé avec succès ! Vous allez être redirigé vers la page de connexion.</p>";
                header("refresh:3;url=login.php");  // Redirige vers la page de connexion après 3 secondes
                exit();
            } else {
                // Si le token n'est pas valide
                echo "<p class='error'>Token invalide ou expiré.</p>";
            }

            // Fermer la connexion
            $stmt->close();
            $conn->close();
        } else {
            echo "<p class='error'>Token manquant. Impossible d'activer le compte.</p>";
        }
        ?>
    </div>
</body>
</html>
