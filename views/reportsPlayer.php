<?php
session_start();
require_once "../classes/Quiz.php";
require "../classes/Question.php";
require "../classes/Result.php";

$results = [];
if (isset($_SESSION['id'])) {
    $results = Result::getByUserId((int)$_SESSION['id']);
    $quizzes = Quiz::getAll(); // Fetch all quizzes for the filter
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Joueur</title>
    <link rel="stylesheet" href="../static/dashboardPlayer.css">
    <style>
        .filters {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <h2>Quizzinonss</h2>
        <ul>
            <li><a href="../views/dashboardPlayer.php" class="dashboard-link">Tableau de bord</a></li>
            <li><a href="profilPlayer.php">Profile</a></li>
            <li><a href="reportsPlayer.php">Rapports</a></li>
            <li><a href="logout.php" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?')">Déconnexion</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Résultats des Quiz</h1>
        <div class="results-list">
            <h2>Tableau des Résultats</h2>
            <div class="filters">
                <label for="quiz-filter">Filtrer par Quiz:</label>
                <select id="quiz-filter">
                    <option value="">Tous les Quiz</option>
                    <?php foreach ($quizzes as $quiz): ?>
                        <option value="<?= htmlspecialchars($quiz['id']) ?>">
                            <?= htmlspecialchars($quiz['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <table>
                <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Quiz</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody id="results-tbody">
                <?php foreach ($results as $result): ?>
                    <tr data-quiz-id="<?= htmlspecialchars($result['quizId']) ?>">
                        <td><?= htmlspecialchars($result['userId']) ?></td>
                        <td><?= htmlspecialchars($result['quizId']) ?></td>
                        <td><?= htmlspecialchars($result['score'] . '%') ?></td>
                        <td><?= htmlspecialchars($result['date']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterSelect = document.getElementById('quiz-filter');
        const resultsTableBody = document.getElementById('results-tbody');

        filterSelect.addEventListener('change', function () {
            const selectedQuizId = this.value;

            // Loop through table rows and filter results
            const rows = resultsTableBody.querySelectorAll('tr');
            rows.forEach(row => {
                const quizId = row.getAttribute('data-quiz-id');
                if (!selectedQuizId || quizId === selectedQuizId) {
                    row.style.display = ''; // Show row
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });
        });
    });
</script>
</body>
</html>
