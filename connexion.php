<?php 

try 
{
    $bdd = new PDO('mysql:host=localhost;dbname=TPespace_membre', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

} catch (Exception $e) 
{
    die('Erreur : ' . $e->getMessage());
}

if (isset($_POST['pseudo']) && isset($_POST['pass'])) 
{
    //  Récupération de l'utilisateur et de son pass hashé
    $req = $bdd->prepare('SELECT id, pass FROM membres WHERE pseudo = :pseudo');
    $req->execute(array(
        'pseudo' => htmlspecialchars($_POST['pseudo'])));
    $resultat = $req->fetch();

    // Comparaison du pass envoyé via le formulaire avec la base
    $isPasswordCorrect = password_verify($_POST['pass'], $resultat['pass']);

    if (!$resultat)
    {
        echo '<p>Mauvais identifiant ou mot de passe !<br> <a href="inscription.php"> Inscrivez-vous ici ! </a></p>';
    }
    else
    {
        if ($isPasswordCorrect) {
            session_start();
            $_SESSION['id'] = $resultat['id'];
            $_SESSION['pseudo'] = $_POST['pseudo'];
            echo '<p>Vous êtes connecté !</p>';
        }
        else {
            echo '<p>Mauvais identifiant ou mot de passe !</p>';
        }
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
<p>Si vous n'avez pas de compte <a href="inscription.php">inscrivez-vous ici</a></p>
<form action="" method="post">
        <legend>Identifiez-vous :</legend>
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