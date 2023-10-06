<?php
session_start();
include './bdd/db_pdo.php'; // Assurez-vous d'utiliser le bon fichier de connexion

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données POST
    $description = $_POST['description'];
    $googlePlaceID = $_POST['googlePlaceID']; // Cela suppose que l'ID du lieu est aussi envoyé
    $type = $_POST['type']; // Type d'incident (gare ou arrêt de bus, etc.)


    // Vérification de l'existence de GooglePlaceID dans la table Lieu
    $stmt = $pdo->prepare("SELECT lieuID FROM Lieu WHERE googlePlaceID = ?");
    $stmt->execute([$googlePlaceID]);
    $result = $stmt->fetch();

    if($result) {
        $lieuID = $result['lieuID'];

        $userID = isset($_SESSION['user']['userID']) ? $_SESSION['user']['userID'] : null;

        // Si userID est nul, lui attribuer la valeur 1
        if (is_null($userID)) {
            $userID = 1;
        }

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO Signalement (userID, lieuID, descriptions, typeSignalement) VALUES (?, ?, ?, ?)");
    
    try {
        $stmt->execute([$userID, $lieuID, $description, $type]);
        header("Location: ../../index.php?success=1");
        exit(); // Assurez-vous de mettre exit() après une redirection pour stopper le script
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
    } else {
        // Gérer le cas où le GooglePlaceID n'existe pas dans la table.
        // Par exemple: Insérer un nouvel enregistrement dans la table Lieu et obtenir le nouvel lieuID
        // OU rediriger vers une page d'erreur.
        die("GooglePlaceID non trouvé");
    }
}
?>
