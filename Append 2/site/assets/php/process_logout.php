<?php
// Démarrage de la session
session_start();

// Suppression de toutes les variables de session
$_SESSION = array();

// Destruction de la session
session_destroy();

// Redirection vers la page d'accueil (ou la page de connexion si vous en avez une)
header('Location: ../../index.php');
exit();
?>
