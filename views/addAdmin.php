<?php
require_once "../utils/DbConnexion.php";
require_once "../classes/User.php";

session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Ajouter un administrateur
    if (User::createAdmin($name, $email, $password)) {
        $message = "Administrateur ajouté avec succès.";

        header('Location: quizzesAdmin.php');
    }

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> "Ajouter un Nouvel Administrateur"; </title>
    <link rel="stylesheet" href="../static/dashboardAdmin.css">
</head>
<body>

<div class="container">
    <h2>Ajouter un Nouvel Administrateur </h2>

    <?php if ($message): ?>
        <div class="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name"  required>
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email"  required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password"  required>
        </div>
        <button type="submit">Ajouter Administrateur"</button>
    </form>
</div>

</body>
</html>