<?php
session_start();
require_once "../classes/Quiz.php";
require "../classes/Question.php";



if(isset($_GET['delete'],$_GET['id'])){
    Quiz::delete((int)$_GET['id']);
    header("location: quizzesAdmin.php");
}

Quiz::init();
// Récupérer tous les quiz
$quizzes = Quiz::getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Quiz - Quizzinonss</title>
    <link rel="stylesheet" href="../static/dashboardPlayer.css">
    <style>
        .quiz-list {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .quiz-list h2 {
            margin-top: 0;
            font-weight: bold;
            color: #333;
        }

        .quiz-list table {
            width: 100%;
            border-collapse: collapse;
        }

        .quiz-list th, .user-list td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .quiz-list th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .quiz-list td {
            background-color: #fff;
        }

        .quiz-list tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .quiz-list tr:hover {
            background-color: #f2f2f2;
        }

        .actions {
            margin-bottom: 20px;
        }

        .actions a {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .actions a:hover {
            background-color: #3e8e41;
        }

        .filters {
            margin-top: 20px;
        }

        .filters label {
            font-weight: bold;
            margin-right: 10px;
        }

        .filters select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
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
        <h1>Gestion des Quiz</h1>
        <div class="quiz-list">
            <h2>Liste des Quiz</h2>
            <div class="actions">
                <a href="addQuiz.php" class="btn">Créer un Nouveau Quiz</a>
            </div>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($quizzes as $quiz): ?>
                    <tr>
                        <td><?= htmlspecialchars($quiz['id']) ?></td>
                        <td><?= htmlspecialchars($quiz['title']) ?></td>
                        <td><?= htmlspecialchars(substr($quiz['description'], 0, 50) . '...') ?></td>
                        <td>
                            <a href="addQuiz.php?edit=<?= $quiz['id'] ?>">Modifier</a>
                            <a href="?delete&id=<?= $quiz['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce quiz ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
