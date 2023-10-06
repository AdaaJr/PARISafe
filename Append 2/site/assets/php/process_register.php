<?php
include './bdd/db_pdo.php'; // Assurez-vous d'utiliser le bon fichier de connexion

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $typeUSER = 0;

    // Vérifiez si l'e-mail est déjà utilisé
    $query = $pdo->prepare("SELECT email FROM Utilisateur WHERE email = :email");
    $query->execute([':email' => $email]);
    
    if ($query->rowCount() > 0) {
        // L'utilisateur avec cet e-mail existe déjà
        $error_message = "L'adresse e-mail est déjà utilisée.";
    } else {
        // Hasher le mot de passe
        $hashedPassword = hash('sha256', $password);

        // Insérez le nouvel utilisateur
        $stmt = $pdo->prepare("INSERT INTO Utilisateur (nom, email, typeUSER,motDePasse) VALUES (:nom, :email, :typeUSER,:motDePasse)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':typeUSER', $typeUSER);
        $stmt->bindParam(':motDePasse', $hashedPassword);

        if ($stmt->execute()) {
            $lastUserID = $pdo->lastInsertId(); 
            // Connexion réussie, démarrer la session et stocker les informations de l'utilisateur
            session_start();
            $_SESSION['user'] = [
                'userID' => $lastUserID,    // Stockez cet ID dans la session
                'nom' => $nom,
                'email' => $email
            ];
            // Redirigez l'utilisateur vers la page d'accueil ou la page de tableau de bord
            header('Location: ../../index.php');
        } else {
            $error_message = "Erreur lors de l'inscription.";
        }
    }
}
?>
