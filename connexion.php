<?php 

try {
    $bdd = new PDO('mysql:host=localhost;dbname=TPespace_membre', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

//  Récupération de l'utilisateur et de son pass hashé
$req = $bdd->prepare('SELECT id, pass FROM membres WHERE pseudo = :pseudo');
$req->execute(array(
    'pseudo' => htmlspecialchars($_POST['pseudo'])));
$resultat = $req->fetch();

// Comparaison du pass envoyé via le formulaire avec la base
$isPasswordCorrect = password_verify($_POST['pass'], $resultat['pass']);

if (!$resultat)
{
    echo 'Mauvais identifiant ou mot de passe !<br> <a href="inscription.php"> Inscrivez-vous ici ! </a>';
}
else
{
    if ($isPasswordCorrect) {
        session_start();
        $_SESSION['id'] = $resultat['id'];
        $_SESSION['pseudo'] = $_POST['pseudo'];
        echo 'Vous êtes connecté !';
    }
    else {
        echo 'Mauvais identifiant ou mot de passe !';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>inscription</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php

if (isset($_SESSION['id']) AND isset($_SESSION['pseudo']))
{
    echo '<p>Bonjour <strong>' . $_SESSION['pseudo'] . '</strong>! </p> <br> <p><a href="inscription.php">Retour inscription</a></p>';
}
else
{
?>
<form action="" method="post">
        <legend>Inscrivez-vous :</legend>
        <label for="pseudo">Votre identifiant</label>
        <input type="text" name="pseudo" id="pseudo">
        <br>

        <label for="pass">Votre mot de passe</label>
        <input type="password" name="pass" id="pass">
        <br>

        <button type="submit">S'inscrire</button>
    </form>
    
<?php
}
?>
    
</body>
</html>