<?php
include './bdd/db_pdo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hashage du mot de passe avec SHA-256 pour comparer avec celui enregistré
    $hashedPassword = hash('sha256', $password);

    $query = $pdo->prepare("SELECT * FROM Utilisateur WHERE email = :email AND motDePasse = :hashedPassword");
    $query->execute([':email' => $email, ':hashedPassword' => $hashedPassword]);
    
    $user = $query->fetch();

    if ($user) {
        session_start();
        $_SESSION['user'] = $user;
        header('Location: ../../index.php');
    } else {
        $error_message = "Adresse email ou mot de passe incorrect.";
    }
}
?>
