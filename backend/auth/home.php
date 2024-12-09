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
            background: linear-gradient(to bottom right, #1e3c72, #2a5298); /* Bleu similaire à celui de la page de registre */
            color: #fff;
            text-align: center;
            padding: 0;
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
            max-width: 800px;
            width: 100%;
            padding: 2em;
            background: rgba(0, 0, 0, 0.6); /* Fond semi-transparent pour contraster */
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h1 {
            font-size: 3.5em;
            text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
            color: #fff;
        }

        p {
            font-size: 1.5em;
            line-height: 1.6;
            margin-top: 20px;
            color: #fff;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Nouveau bouton stylisé */
        .cssbuttons-io-button {
            background: #a370f0;
            color: white;
            font-family: inherit;
            padding: 0.35em;
            padding-left: 1.2em;
            font-size: 17px;
            font-weight: 500;
            border-radius: 0.9em;
            border: none;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            box-shadow: inset 0 0 1.6em -0.6em #714da6;
            overflow: hidden;
            position: relative;
            height: 2.8em;
            padding-right: 3.3em;
            cursor: pointer;
        }

        .cssbuttons-io-button .icon {
            background: white;
            margin-left: 1em;
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 2.2em;
            width: 2.2em;
            border-radius: 0.7em;
            box-shadow: 0.1em 0.1em 0.6em 0.2em #7b52b9;
            right: 0.3em;
            transition: all 0.3s;
        }

        .cssbuttons-io-button:hover .icon {
            width: calc(100% - 0.6em);
        }

        .cssbuttons-io-button .icon svg {
            width: 1.1em;
            transition: transform 0.3s;
            color: #7b52b9;
        }

        .cssbuttons-io-button:hover .icon svg {
            transform: translateX(0.1em);
        }

        .cssbuttons-io-button:active .icon {
            transform: scale(0.95);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.8em;
            }
            p {
                font-size: 1.4em;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
        <p>Nous sommes heureux de vous accueillir sur notre plateforme. Profitez de toutes les fonctionnalités que nous proposons et rejoignez notre communauté grandissante !</p>
        <form action="/frontend/home.html" method="POST" style="display: inline;">
        <!-- Le bouton centré -->
        <button class="cssbuttons-io-button">
        Accéder à l'Accueil
            <div class="icon">
                <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z" fill="currentColor"></path>
                </svg>
            </div>
        </button>
        </form>
    </div>
</body>
</html>
