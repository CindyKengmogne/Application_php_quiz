<?php
session_start();
require_once '../classes/User.php';
$id = $_SESSION['id'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];
$role = $_SESSION['role'];

// Connexion à la base de données



// Vérification de la méthode de soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST['password'];


    $password_hash = User::findById($id)['password'];

    if (password_verify($password, $password_hash)) {
        // Redirection vers la page de modification

            header("Location: updateUser.php");


    } else {
        $error = "Mot de passe incorrect";
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation du mot de passe</title>
    <link rel="stylesheet" href="../static/dashboardPlayer.css">
</head>
<body>

<!-- Contenu principal -->
<div class="login-container">
    <div class="login-box">
        <h1>Validation du mot de passe</h1>
        <p>Veuillez entrer votre mot de passe pour continuer</p>

        <?php if (isset($error)) : ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Valider</button>
        </form>
    </div>
</div>

</body>
</html>