<?php
session_start();

// Si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    // Rediriger vers la page d'accueil
    header("Location: index.php");
    exit; // Assurez-vous d'arrêter l'exécution du script après une redirection
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login - PARISafe</title>

    <!-- CSS & JS liens de votre code précédent -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="index-page">

    <header id="header" class="header fixed-top d-flex align-items-center">
        <!-- Votre en-tête ici -->
    </header>

    <main id="main">
        <section class="login-section d-flex align-items-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Login</div>
                            <div class="card-body">
                                <form action="./assets/php/process_login.php" method="post">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Adresse mail</label>
                                        <input type="text" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Se connecter</button>
                                    <a href="register.php">S'inscrire</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="footer" class="footer">
        <!-- Votre pied de page ici -->
    </footer>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

</body>

</html>
