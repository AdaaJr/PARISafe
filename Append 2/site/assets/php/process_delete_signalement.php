<?php
session_start();

// Vérifiez si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['user']) || $_SESSION['user']['typeUSER'] != 1) {
    // Redirigez vers la page d'accueil ou d'erreur
    header('Location: ../../index.php');
    exit;
}

// Connectez-vous à votre base de données
include './bdd/db_pdo.php'; // Assurez-vous d'utiliser le bon fichier de connexion

// Récupérez l'ID du signalement à supprimer
$signalementID = $_GET['id'];

// Exécutez une requête pour supprimer le signalement
$query = "DELETE FROM Signalement WHERE signalementID = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $signalementID, PDO::PARAM_INT);
$stmt->execute();

// Redirigez vers la page d'accueil avec un message de succès
header('Location: ../../index.php?success=1');
exit;
?>
