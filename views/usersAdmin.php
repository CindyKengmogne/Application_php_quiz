<?php
require_once "../classes/User.php"; // Assurez-vous du bon chemin

// Récupérer tous les utilisateurs
$users = User::getAllUsers(); // Vous devez ajouter cette méthode à la classe User
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Quizzinonss</title>
    <link rel="stylesheet" href="../static/dashboardPlayer.css">
    <style>
        .user-list {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-list h2 {
            margin-top: 0;
            font-weight: bold;
            color: #333;
        }

        .user-list table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-list th, .user-list td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .user-list th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .user-list td {
            background-color: #fff;
        }

        .user-list tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .user-list tr:hover {
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
        <h1>Gestion des Utilisateurs</h1>
        <div class="user-list">
            <h2>Liste des Utilisateurs</h2>
            <div class="actions">
                <a href="addAdmin.php" class="btn"> Nouveau</a>
            </div>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['dateInscription']) ?></td>
                        <td>
                            <a href="addAdmin.php?userId<?= $user['id'] ?>">Modifier</a>
                            <a href="/delete-user/<?= $user['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">Supprimer</a>
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