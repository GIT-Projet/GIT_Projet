<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Vérifier si la méthode HTTP est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Détruire la session pour déconnecter l'utilisateur
    session_unset();
    session_destroy();

    // Redirection vers la page de connexion (chemin absolu)
    header("Location: /frontend/login.html");
    exit();
} else {
    echo "Méthode HTTP non autorisée.";
    exit();
}
?>
