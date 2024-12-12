<?php
session_start();
require_once "../classes/Quiz.php";
require "../classes/Question.php";

$quizzes = Quiz::getAll();

if (isset($_GET['play'])) {
    $_SESSION['Play'] = $_GET['play'];
    header('location: Play.php');
}

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Joueur</title>
    <link rel="stylesheet" href="../static/dashboardPlayer.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Quizzinonss</h2>
            <ul>
                <li><a href="../views/dashboardPlayer.php" class="dashboard-link">Tableau de bord</a></li>
                <li><a href="profilPlayer.php">Profile</a></li>
                <li><a href="reportsPlayer.php">Rapports</a></li>
                <li><a href="logout.php" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?')">Déconnexion</a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Bienvenue dans ton tableau de bord,<?php echo  $_SESSION['name']??" "?></h1>
            <div class="stats">
                <h2>Statistiques</h2>
                <p>Nombre de quiz : <?php echo Quiz::countQuizz();?></p>
                <p>Meilleur score : 85%</p>
            </div>
            <div class="quiz-list">
                <h2>Quiz disponibles</h2>
                <table>
                    <thead>
                    <tr>

                        <th>Titre</th>
                        <th>Apercu</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($quizzes as $quiz): ?>
                        <tr>

                            <td><?= htmlspecialchars($quiz['title']) ?></td>
                            <td><?= htmlspecialchars(substr($quiz['description'], 0, 50) . '...') ?></td>
                            <td>
                                <a href="?play=<?= $quiz['id'] ?>">Play</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
