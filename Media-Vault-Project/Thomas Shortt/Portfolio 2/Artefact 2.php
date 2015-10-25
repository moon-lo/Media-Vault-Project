<?php
	define('ROOT_DIR', dirname(__FILE__));
    include ROOT_DIR . '/php-files/file_management.php';
    
    $pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//Set error variable.
	$error = '';
	
	//Determine if the Login button has been pressed.
	if (isset($_POST['Login']))
	{
		//Set the username and password values to a variable.
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		//Check if the username and password fields are empty. Return an error if they are, otherwise search the users table for the inputted username and password.
		if (empty($username) || empty($password))
		{
			$error = 'Username or Password is invalid!';
		}
		else
		{
			try
			{
				//Use a prepared statement to search the users table for the requested username and password.
				$stmt = $pdo->prepare('SELECT * FROM users '.
				'WHERE username = :username AND password = SHA2(CONCAT(:password, salt), 0)');
				$stmt->bindValue(':username', $username);
				$stmt->bindValue(':password', $password);
				$stmt->execute();
			}
			catch (PDOException $e)
			{
				echo $e->getMessage();
			}
			//Determine if the query returned any rows. If it did then the user will be redirected to their directory and they will be signed-in. Otherwise return an error.
			$row = $stmt->rowCount();
			if ($row > 0)
			{
				session_start();
				$_SESSION['isUser'] = $username;
				//header("Location: http://localhost/mediavault/Bootstrap_ver/directory.php");
                header("Location: http://{$_SERVER['HTTP_HOST']}/Media-Vault-Project/Media-Vault-Project/Bootstrap_ver/directory.php");
				exit();
			}
			else
			{
				$error = 'Username or Password is invalid!';
			}
		}
	}
	elseif (isset($_POST['register-submit']))
	{
		//Set the email, username and password values to a variable.
		$email = $_POST['email'];
		$username = $_POST['username'];
		$newPassword = $_POST['password'];
		$confirmPassword = $_POST['confirm-password'];
		//Check all the fields have data in them.
		if (empty($username) || empty($newPassword) || empty($email) || empty($confirmPassword))
		{
			$error = 'Invalid input. Please try again.';
		}
		//Determine if the confirmed password matches the original password.
		elseif ($newPassword != $confirmPassword)
		{
			$error = 'Passwords do not match. Please try again';
		}
		//Place the data into the users table.
		else
		{	
			//Generate random 10 characters for salt
			$chars = '0123456789abcdefghijklmnopABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charsLength = strlen($chars);
			$randomSalt = "";
			for($i = 0; $i < 13; $i++)
			{
				$randomSalt .= $chars[rand(0, $charsLength - 1)];
			}
			$max_storage = (50 * 1024);
				
			try
			{
				//Use a prepared statement to insert the data into the users table.
				$stmt = $pdo->prepare("INSERT INTO users (username, email, password, salt, max_storage) ".
				"VALUES ('$username','$email', SHA2(CONCAT('$newPassword', '$randomSalt'), 0), '$randomSalt', '$max_storage')");
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				//Display an error if the data could not be added.
				$error = 'Error creating account. The username you are trying to use may have been taken. Please try again.';
			}
			//Determine if the query created a row. If true then the user has successfully created an account. Otherwise return an error.
			$row = $stmt->rowCount();
			if ($row > 0)
			{
				mkdir(dirname(__FILE__) . '/uploads/' . $username);
				header("Location: http://{$_SERVER['HTTP_HOST']}/Media-Vault-Project/Media-Vault-Project/Bootstrap_ver/index.php");
				exit();
			}
			else
			{
				$error = 'Error creating account. The username you are trying to use may have been taken. Please try again.';
			}
		}
	}
?>