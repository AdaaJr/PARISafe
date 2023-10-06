<?php
include './bdd/db_pdo.php'; // Assurez-vous d'utiliser le bon fichier de connexion

// Récupérer les données POST
$googlePlaceID = $_POST['googlePlaceID'];
$nom = $_POST['nom'];
$typeLieu = $_POST['typeLieu'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$descriptions = $_POST['descriptions'];

$query = "SELECT lieuID FROM Lieu WHERE googlePlaceID = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$googlePlaceID, $nom, $latitude, $longitude]);

if ($stmt->fetch()) {
    // Le lieu existe déjà
    echo "Le lieu existe déjà dans la base de données.";
} else {
    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO Lieu (googlePlaceID, nom, typeLieu, latitude, longitude, descriptions) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$googlePlaceID, $nom, $typeLieu, $latitude, $longitude, $descriptions]);

    echo "Données insérées avec succès.";
}

?>
