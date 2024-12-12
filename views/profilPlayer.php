<?php
session_start();
require_once '../classes/User.php';
$id=$_SESSION['id'];

$_SESSION['name']=User::findById($id)['name'];
$_SESSION['email']=User::findById($id)['email'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="../static/profil.css">
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <h2 class="logo">Quizzinonss</h2>
    <ul>
        <li><a href="../views/dashboardPlayer.php" class="dashboard-link">Tableau de bord</a></li>
        <li><a href="profilPlayer.php">Profile</a></li>
        <li><a href="reportsPlayer.php">Rapports</a></li>
        <li><a href="logout.php" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?')">Déconnexion</a>
        </li>
    </ul>
</div>

<!-- Contenu principal -->
<div class="main-content">
    <div class="content-box">
        <h1>Bienvenue dans ton profil</h1>
        <p>Voici tes informations</p>

        <div class="profile-info">
            <p><strong>Nom : </strong><?php echo $name; ?></p>
            <p><strong>Email : </strong><?php echo $email; ?></p>
        </div>

        <div class="profile-buttons">
            <a href="validationMdp.php?<?php echo $id;?>" class="button">Modifier les informations</a>
        </div>
    </div>
</div>
</body>
</html>