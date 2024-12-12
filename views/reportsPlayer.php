<?php
session_start();
require_once "../classes/Quiz.php";
require "../classes/Question.php";
require "../classes/Result.php";

if(isset($_SESSION['id'])){
    $results = Result::getByUserId((int)[$_SESSION['id']]);
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
        <h1>Résultats des Quiz</h1>
        <div class="results-list">
            <h2>Tableau des Résultats</h2>
            <table>
                <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Quiz</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?= htmlspecialchars($result['userId']) ?></td>
                        <td><?= htmlspecialchars($result['quizId']) ?></td>
                        <td><?= htmlspecialchars($result['score'] . '%') ?></td>
                        <td><?= htmlspecialchars($result['date']) ?></td>
                    </tr>
                <?php endforeach; ?></tbody>
            </table>
            <div class="filters">
                <label for="quiz-filter">Filtrer par Quiz:</label>
                <select id="quiz-filter">
                    <option value="">Tous les Quiz</option>
                    <option value="1">Géographie Mondiale</option>
                    <option value="2">Histoire de France</option>
                </select>
            </div>
        </div>
    </div>
</div>
</body>
</html>
