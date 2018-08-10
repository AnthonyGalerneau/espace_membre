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
	elseif (strtolower($_POST['email']) == strtolower($verif_email['email']))
	{
		echo 'l\'adresse mail est déjà utilisée.';
	}
	
	elseif(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) 
	{
		echo 'Cette adresse mail n\'est pas valide.';
	}
	elseif($_POST['pass'] !== $_POST['pass_confirm'])
	{
		echo 'Vos mots de passes ne sont pas identiques.';
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
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>inscription</title>
</head>
<body>
	<form action="" method="post">
	    <label for="pseudo">Votre identifiant</label>
	    <input type="text" name="pseudo" id="pseudo">

	    <label for="email">Votre adresse e-mail</label>
	    <input type="text" name="email" id="email">

	    <label for="pass">Votre mot de passe</label>
	    <input type="password" name="pass" id="pass">

	    <label for="pass_confirm">Confirmez votre mot de passe</label>
	    <input type="password" name="pass_confirm" id="pass_confirm">

	    <button type="submit">S'inscrire</button>
	</form>
</body>
</html>



