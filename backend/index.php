<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si le script est directement appelé
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Redirection vers register.html dans le dossier frontend
    header('Location: /frontend/register.html');
    exit();
} else {
    // Optionnel : message si une autre méthode HTTP est utilisée
    die("Méthode HTTP non autorisée.");
}
?>
