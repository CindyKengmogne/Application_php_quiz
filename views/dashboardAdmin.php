<?php

session_start();

include ('../classes/User.php');
include ('../classes/Quiz.php');
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../static/dashboardPlayer.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Quizzinonss</h2>
            <ul>
                <li><a href="../views/dashboardAdmin.php" class="dashboard-link">Tableau de bord</a></li>
                <li><a href="usersAdmin.php">Gérer les utilisateurs</a></li>
                <li><a href="quizzesAdmin.php">Gérer les quiz</a></li>
                <li><a href="reportsAdmin.php">Rapports</a></li>
                <li><a href="ProfileAdmin.php">Profile</a></li>
                <li><a href="logout.php" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?')">Déconnexion</a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Bienvenue , <?php echo $_SESSION['name']??""; ?></h1>
            <div class="overview">
                <h2>Vue d'ensemble</h2>
                <p>Utilisateurs inscrits :<?php echo User::countUser();?></p>
                <p>Nombres d'admins: <?php echo User::countbyRole("admin");?></p>
                <p>Nombre de joueurs: <?php echo User::countbyRole("player");?></p>
                <p>Quiz créés :<?php echo Quiz::countQuizz();?></p>
            </div>

        </div>
    </div>
</body>
</html>
