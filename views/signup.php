<?php
require_once "../classes/User.php";

session_start();
$errors=[];

if($_SERVER['REQUEST_METHOD']=='POST'){
    $name=htmlspecialchars($_POST['name']);
    $email=htmlspecialchars($_POST['email']);
    $password=htmlspecialchars($_POST['password']);
    $confirm_password=htmlspecialchars($_POST['confirm_password']);

    if($password===$confirm_password){
        User::create($name,$email,$password);
        header('location: login.php');
        exit;
    }
    else
    {
        $errors['password']='Les mots de passe ne correspondent pas';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'inscription</title>
    <!-- Lien vers le fichier CSS dans le répertoire static -->
    <link rel="stylesheet" href="../static/signup.css">
</head>
<body>
    <div class="signup-container">
        <div class="signup-box">
            <h2>Inscris-toi!</h2>
            <form  action="#" method="POST">
            <div class="input-box">
                    <label for="name">Nom</label>
                    <input type="name" id="name" name="name" required placeholder="Entrez votre nom">
                </div>
                <div class="input-box">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Entrez votre email">
                </div>
                <div class="input-box">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="Créez un mot de passe">
                </div>
                <div class="input-box">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirmez votre mot de passe">
                </div>

                <?php echo $errors['password']??""; ?>

                <div class="accept-terms">
                    <label>
                        <input type="checkbox" name="accept_terms" id="accept_terms" required>
                        J'accepte les <a href="#">conditions générales</a>
                    </label>
                </div>
                <div class="captcha">
                    
                    <img src="captcha_image_url" alt="Captcha Image" class="captcha-image">
                    <label for="captcha">Vérification</label>
                    <input type="text" id="captcha" name="captcha" required placeholder="Entrez le captcha">
                </div>
                <button type="submit" class="submit-btn">S'inscrire</button>
            </form>
            <div class="login-link">
                <p>Déjà un compte ? <a href="login.php">Connectez-vous</a></p>
            </div>
        </div>
    </div>
</body>
</html>
