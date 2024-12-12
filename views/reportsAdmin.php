<?php
require_once "../classes/Result.php";


$results = Result::getAll();
?>
<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats des Quiz - Quizzinonss</title>
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
                    <th>Détails</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?= htmlspecialchars($result['userId']) ?></td>
                        <td><?= htmlspecialchars($result['quizId']) ?></td>
                        <td><?= htmlspecialchars($result['score'] . '%') ?></td>
                        <td><?= htmlspecialchars($result['date']) ?></td>
                        <td><a href="/result-details/<?= $result['id'] ?>">Voir Détails</a></td>
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
