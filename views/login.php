<?php
require_once '../classes/User.php';
require_once '../utils/CookieManager.php';
session_start();
$errors = [];
if (isset($_SESSION['id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: dashboardAdmin.php');
    } else {
        header('Location: dashboardPlayer.php');
    }
    exit;
}

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "gestionquiz");

// Sélection d'un captcha aléatoire
$query = "SELECT * FROM captcha ORDER BY RAND() LIMIT 1";
$result = mysqli_query($conn, $query);
$captcha = mysqli_fetch_assoc($result);
//echo $captcha['code'] ;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $captcha_codeUser = htmlspecialchars($_POST['captcha']);
    $captcha_code=htmlspecialchars($_POST['captcha_code']);
    $remember = isset($_POST['remember']) ? true : false;

    $user = new User();

    $user = User::findByEmail($email);

    if ($user) {
        if (password_verify($password, $user['password'])) {

            // Vérification du captcha
//            $query = "SELECT * FROM captcha WHERE id = '$captcha[id]' AND code = '$captcha_code'";
//            $result = mysqli_query($conn, $query);
//            $captcha_test = mysqli_fetch_assoc($result);
            if ($captcha_codeUser == $captcha_code) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['password'] = $user['password'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                if ($remember) {
                    CookieManager::setCookie('email', $email, 3600 * 24 * 30);
                    CookieManager::setCookie('password', $password, 3600 * 24 * 30);
                }
                if ($user['role'] == "admin") {
                    header('location: dashboardAdmin.php');
                } else {
                    header('location: dashboardPlayer.php');
                }
                exit;
            } else {
                $errors['captcha'] = "Captcha incorrect";
            }
        } else {
            $errors['password'] = "Mot de passe incorrect";
        }
    } else {
        $errors['email'] = "Email inexistant";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="../static/login.css">
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <h1 class="quiz-title">Quizzinonss!</h1> <!-- Titre du quiz -->

        <h2>Connecte toi!</h2>
        <form action="" method="POST">
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Entrez votre email">
            </div>
            <div class="input-box">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="Entrez votre mot de passe">
            </div>
            <?php echo $errors['email'] ?? ""; ?>
            <?php echo $errors['password'] ?? ""; ?>
            <?php echo $errors['captcha'] ?? ""; ?>

            <div class="remember-me">
                <label>
                    <input type="checkbox" name="remember" id="remember">
                    Se souvenir de moi
                </label>
            </div>
            <div class="captcha">
                <img src="data:image/png;base64,<?php echo base64_encode($captcha['image']); ?>" alt="Captcha Image" class="captcha-image">
                <label for="captcha">Vérification</label>
                <input type="text" id="captcha" name="captcha" required placeholder="Entrez le captcha">
                <input type="hidden" name="captcha_code" value="<?php echo $captcha['code']; ?>">
            </div>
            <button type="submit" class="submit-btn">Se connecter</button>
        </form>
        <div class="signup-link">
            <p>Pas encore de compte ? <a href="../views/signup.php">Inscrivez-vous</a></p>
        </div>
    </div>
</div>
</body>
</html>