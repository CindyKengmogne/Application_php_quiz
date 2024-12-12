<?php
require_once "../classes/Quiz.php";
require_once "../classes/Question.php";
require_once "../utils/DbConnexion.php"; // Assurez-vous que ce fichier configure la connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["quizTitle"];
    $description = $_POST["quizDescription"];
    $questions = $_POST["questionText"];
    $images = $_POST["imageUrl"];
    $allAnswers = $_POST["answer"];
    $correctAnswers = $_POST["correctAnswer"];

    // Connexion à la base de données
    $conn = getDatabaseConnection(); // Remplacez par votre fonction pour obtenir la connexion

    try {
        // Démarrer une transaction
        $conn->beginTransaction();

        // Insérer les informations générales du quiz
//        $stmt = $conn->prepare("INSERT INTO quiz (title, description) VALUES (?, ?)");
//        $stmt->execute([$title, $description]);
//        $quizId = $conn->lastInsertId();
        Quiz::create($title,$description);
        $quiz=Quiz::findByTitle($title);
        // Insérer chaque question
        foreach ($questions as $index => $questionText) {
            $imageUrl = $images[$index] ?? '';
            $answers = $allAnswers[$index]; // Tableau des réponses pour cette question
            $correctAnswerIndex = $correctAnswers[$index];

            // Combiner les réponses en une seule chaîne séparée par des virgules
            $answerString = implode(";", $answers);


            // Insérer la question dans la base de données
//            $stmt = $conn->prepare("INSERT INTO questions (quizid, questionText, imageUrl, answers, correctAnswer) VALUES (?, ?, ?, ?, ?)");
//            $stmt->execute([$quizId, $questionText, $imageUrl, $answerString, $correctAnswerIndex]);
            Question::create($quiz['id'],$questionText,$imageUrl,$correctAnswerIndex,$answerString);
        }



        echo "Le quiz a été enregistré avec succès.";
        header("location: quizzesAdmin.php");
    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $conn->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer/Modifier un Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .quiz-form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .question-section {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            border-radius: 5px;
        }
        .question-section h3 {
            margin-top: 0;
        }
        .remove-question {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .answer-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn-add {
            background-color: #2196F3;
        }
        .btn-remove {
            background-color: #f44336;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
<form action="#" id="quizForm" class="quiz-form" method="POST">
    <h2>Créer/Modifier un Quiz</h2>

    <div class="form-group">
        <label for="quizTitle">Titre du Quiz</label>
        <input type="text" id="quizTitle" name="quizTitle" required placeholder="Entrez le titre du quiz">
    </div>

    <div class="form-group">
        <label for="quizDescription">Description du Quiz</label>
        <textarea id="quizDescription" name="quizDescription" required placeholder="Décrivez brièvement le quiz"></textarea>
    </div>

    <div id="questionsContainer">
        <!-- Les questions seront ajoutées dynamiquement ici -->
    </div>

    <div>
        <button type="button" id="addQuestionBtn" class="btn btn-add">
            + Ajouter une Question
        </button>
        <button type="submit" class="btn">
            Enregistrer le Quiz
        </button>
    </div>
</form>

<script>
    let questionCount = 0;

    function createQuestionSection() {
        questionCount++;
        const questionSection = document.createElement('div');
        questionSection.classList.add('question-section');
        questionSection.dataset.questionNumber = questionCount;

        // Bouton de suppression
        const removeBtn = document.createElement('button');
        removeBtn.textContent = '✖ Supprimer';
        removeBtn.type = 'button';
        removeBtn.classList.add('remove-question');
        removeBtn.addEventListener('click', () => {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette question ?')) {
                questionSection.remove();
            }
        });

        questionSection.innerHTML = `
        <h3>Question ${questionCount}</h3>
        ${removeBtn.outerHTML}

        <div class="form-group">
            <label for="questionText${questionCount}">Texte de la Question</label>
            <input type="text" id="questionText${questionCount}"
                   name="questionText[]"
                   required
                   placeholder="Entrez le texte de la question">
        </div>

        <div class="form-group">
            <label for="imageUrl${questionCount}">URL de l'Image (optionnel)</label>
            <input type="text" id="imageUrl${questionCount}"
                   name="imageUrl[]"
                   placeholder="Lien vers une image liée à la question">
        </div>

        <div class="form-group">
            <label>Réponses Possibles</label>
            <div class="answer-options">
                <input type="text" name="answer${questionCount}[]"
                       required
                       placeholder="Option 1">
                <input type="text" name="answer${questionCount}[]"
                       required
                       placeholder="Option 2">
                <input type="text" name="answer${questionCount}[]"
                       required
                       placeholder="Option 3">
                <input type="text" name="answer${questionCount}[]"
                       required
                       placeholder="Option 4">
            </div>
        </div>

        <div class="form-group">
            <label for="correctAnswer${questionCount}">Réponse Correcte</label>
            <select id="correctAnswer${questionCount}"
                    name="correctAnswer[]"
                    required>
                <option value="">Sélectionnez la réponse correcte</option>
                <option value="0">Option 1</option>
                <option value="1">Option 2</option>
                <option value="2">Option 3</option>
                <option value="3">Option 4</option>
            </select>
        </div>
    `;

        return questionSection;
    }

    // Ajouter une question initiale
    document.getElementById('questionsContainer').appendChild(createQuestionSection());

    // Bouton pour ajouter des questions
    document.getElementById('addQuestionBtn').addEventListener('click', () => {
        document.getElementById('questionsContainer').appendChild(createQuestionSection());
    });

    // Soumettre le formulaire
    document.getElementById('quizForm').addEventListener('submit', function(event) {
        event.preventDefault();

        // Préparation des données du quiz
        const quizData = {
            title: document.getElementById('quizTitle').value,
            description: document.getElementById('quizDescription').value,
            questions: []
        };

        // Collecter les données de chaque question
        const questionSections = document.querySelectorAll('.question-section');
        questionSections.forEach((section, index) => {
            const answerInputs = section.querySelectorAll(`[name="answer${index+1}[]"]`);
            const correctAnswerIndex = section.querySelector(`[name="correctAnswer[]"]`).value;

            // Préparer les réponses incorrectes
            const answers = Array.from(answerInputs)
                .filter((_, idx) => idx != correctAnswerIndex)
                .map(input => input.value);

            const questionData = {
                questionText: section.querySelector(`[name="questionText[]"]`).value,
                imageUrl: section.querySelector(`[name="imageUrl[]"]`).value || '',
                correctAnswer: answerInputs[correctAnswerIndex].value,
                answers: answers
            };

            quizData.questions.push(questionData);
        });

        // Envoyer les données au serveur (à adapter selon votre backend)
        console.log('Données du quiz :', JSON.stringify(quizData, null, 2));

        // Ici, vous devriez ajouter la logique d'envoi au serveur
        // Par exemple, avec fetch ou XMLHttpRequest
        alert(`Quiz "${quizData.title}" créé avec ${quizData.questions.length} questions !`);
    });
</script>
</body>
</html>