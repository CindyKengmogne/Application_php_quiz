<?php
require_once "../utils/DbConnexion.php";
require_once "../classes/User.php";

session_start();
$id=$_SESSION['id'];
$message = '';
if ($id) {
    $admin = User::findById($id);
    $name = $admin['name'];
    $email = $admin['email'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($id) {
        // Modifier un user
        if (User ::update($id, $name, $email, $password)) {
            $message = "Administrateur modifié avec succès.";
        } else {
            $message = "Erreur lors de la modification de l'administrateur.";
        }
    } else {
        // Ajouter un administrateur
        if (User ::createAdmin($name, $email, $password)) {
            $message = "Administrateur ajouté avec succès.";
        } else {
            $message = "Erreur lors de l'ajout de l'administrateur.";
        }
    }
    header('Location: ProfilPlayer.php');
}

// Récupérer les informations de l'administrateur si on veut le modifier

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? "Modifier un Administrateur" : "Ajouter un Nouvel Administrateur"; ?></title>
    <link rel="stylesheet" href="../static/dashboardAdmin.css">
</head>
<body>

<div class="container">
    <h2><?php echo $id ? "Modifier un Administrateur" : "Ajouter un Nouvel Administrateur"; ?></h2>

    <?php if ($message): ?>
        <div class="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
<!--    --><?php //echo $id;?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" value="<?php echo $name ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo $email ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" value="<?php echo $password ?? ''; ?>" required>
        </div>
        <button type="submit"><?php echo $id ? "Modifier Administrateur" : "Ajouter Administrateur"; ?></button>
    </form>
</div>

</body>
</html>