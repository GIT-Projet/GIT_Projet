<?php
session_start(); // Démarrer ou reprendre la session

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['username'])) {
    // Détruire toutes les données de la session
    session_unset();  // Libérer les variables de session
    session_destroy(); // Détruire la session
}

// Afficher le message avant la redirection
echo "
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Déconnexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .message {
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <div class='message'>
        <h1>Au revoir, à la prochaine !</h1>
    </div>
  
</body>
</html>
";
?>
