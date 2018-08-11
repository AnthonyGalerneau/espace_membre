<?php

$bdd = new PDO('mysql:host=localhost;dbname=TPespace_membre', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
//$reponse = $bdd->query('SELECT * FROM membres'); 

if(isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['pass_confirm']))
{

	$req_pseudo = $bdd->prepare('SELECT pseudo FROM membres WHERE pseudo = ?');
	$req_pseudo->execute(array(htmlspecialchars($_POST['pseudo'])));
	$verif_pseudo = $req_pseudo-> fetch();

	$req_email = $bdd->prepare('SELECT email FROM membres WHERE email = ?');
	$req_email->execute(array(htmlspecialchars($_POST['email'])));
	$verif_email = $req_email-> fetch();
		
	if (strtolower($_POST['pseudo']) == strtolower($verif_pseudo['pseudo'])) 
	{
		echo 'Le pseudo est déjà utilisé.';
	}
	elseif (!preg_match("#^[0-9A-Za-z._-]+$#", $_POST['pseudo']))
	{
		echo 'votre pseudo n\'est pas valide.. (aA-zZ 0-9 . - _)';
	}
	elseif (strtolower($_POST['email']) == strtolower($verif_email['email']))
	{
		echo 'l\'adresse mail est déjà utilisée.';
	}
	
	elseif(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) 
	{
		echo 'Cette adresse mail n\'est pas valide.';
	}
	elseif($_POST['pass'] !== $_POST['pass_confirm'])
	{
		echo 'Vos mots de passes ne sont pas identiques.';
	} 
	elseif (!preg_match("#^[0-9A-Za-z._-]+$#", $_POST['pass']))
	{
		echo 'votre mot de passe n\'est pas valide.. (aA-zZ 0-9 . - _)';
	}
	else
	{
		$pass_hache = password_hash($_POST['pass'], PASSWORD_DEFAULT);

		$req = $bdd->prepare('INSERT INTO membres (pseudo, email, pass, date_inscription) VALUES(:pseudo, :email, :pass, CURDATE())');
		$req->execute(array(
			'pseudo' => htmlspecialchars($_POST['pseudo']),
			'email' => htmlspecialchars($_POST['email']),
			'pass' => $pass_hache));
	}
elseif
{
	echo 'Au moins un champ n\est pas rempli !';
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
	<p><a href="connexion.php">Connectez-vous ici</a> ou inscrivez-vous ci-dessous :</p>
	<form action="inscription.php" method="POST">
		<legend>Inscrivez-vous :</legend>
	    <label for="pseudo">Votre identifiant</label>
	    <input type="text" name="pseudo" id="pseudo">
	    <br>

	    <label for="email">Votre adresse e-mail</label>
	    <input type="text" name="email" id="email">
	    <br>

	    <label for="pass">Votre mot de passe</label>
	    <input type="password" name="pass" id="pass">
	    <br>

	    <label for="pass_confirm">Confirmez votre mot de passe</label>
	    <input type="password" name="pass_confirm" id="pass_confirm">
	    <br>

	    <button type="submit">S'inscrire</button>
	</form>
</body>
</html>



