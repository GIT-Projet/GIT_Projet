<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: /frontend/login.html");
    exit();
}

// Afficher une page d'accueil personnalisée ou autre
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>!</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: linear-gradient(to bottom right, #6a11cb, #2575fc);
            color: #ffffff;
            text-align: center;
            padding: 5% 10%;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-attachment: fixed;
            background-position: center;
            background-size: cover;
        }

        .container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 2em;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
        }

        h1 {
            font-size: 4em;
            text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.7);
            margin-bottom: 20px;
        }

        p {
            font-size: 1.8em;
            line-height: 1.6;
            margin-top: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-button {
            margin-top: 40px;
            padding: 15px 40px;
            background-color: #3498db;
            color: #ffffff;
            border: none;
            border-radius: 50px;
            font-size: 1.5em;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 3em;
            }
            p {
                font-size: 1.4em;
            }
            .cta-button {
                font-size: 1.2em;
                padding: 12px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
        <p>Nous sommes heureux de vous accueillir. Explorez les nombreuses fonctionnalités de notre site et faites partie de notre communauté !</p>
        <form action="/backend/auth/logout.php" method="POST" style="display: inline;">
    <button type="submit" class="cta-button">Se déconnecter</button>
        </form>
    </div>
</body>
</html>
