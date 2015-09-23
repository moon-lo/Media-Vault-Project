<?php
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	if (isset($_SESSION['isUser'])) {
		unset($_SESSION['isUser']);
	}
	
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
				//header("Location: http://localhost/mediavault/directory.php");
                header("Location: http://{$_SERVER['HTTP_HOST']}/Media-Vault-Project/Media-Vault-Project/directory.php");
				exit();
			}
			else
			{
				$error = 'Username or Password is invalid!';
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Team 12 Media Vault</title>
</head>

<body class="indexPage">
<table width="100%" height="83" border="0">
  <tr>
    <td width="7%" rowspan="2">&nbsp;</td>
    <td width="93%" height="32"><font size="4">Welcome to</font></td>
  </tr>
  <tr>
    <td><strong><font size="+3">TEAM 12 MEDIA VAULT</font></strong></td>
  </tr>
</table>
<hr />
<table width="40%" height="443" border="0" style="float: left;">
  <tr>
    <td width="102" height="55">&nbsp;</td>
    <td width="444">&nbsp;</td>
    <td width="56">&nbsp;</td>
  </tr>
  <tr>
    <td height="286">&nbsp;</td>
    <td id="aboutText"><p><font size="+2">About</font></p>
      <p><font size="4">Welcome to Team 12 Media Vault - your free and easy personal cloud storage system. Simply sign up today to access your files from anywhere.</font></p>
    <p><font size="4">Features:</p>
    <p><font size="4"> * Supports Androids, iPhones and tablets</font></p>
    <p><font size="4">* Max. storage of 1GB per user</font></p>
    <p><font size="4">* Max. file size of 2MB per upload</font></p></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="60%" border="0" style="float: right;">
  <tr>
    <td width="13%" height="55">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="37%">&nbsp;</td>
  </tr>
  <tr>
    <td height="285" rowspan="5">&nbsp;</td>
    <td id="emphasisText" height="26" colspan="2"><p><strong><font size="3">Existing user</font></strong></p></td>
    <td rowspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td height="114" colspan="2"><div align="left">
      <form action="index.php" method="POST" name="SignIn">
        <p>Username:
		  <input type="text" name="username" value="" />
		  <br /><br />
          Password:
          <input type="password" name="password" value="" />
          <br /><br />
          <input type="submit" name="Login" value="Sign in" />
		  <br /><br />
		  <span><?php echo $error; ?></span>
        </p>
      </form>
    </div></td>
  </tr>
  <tr>
    <td height="22" colspan="2"><hr /></td>
  </tr>
  <tr>
    <td id="emphasisText" height="27" colspan="2"><strong><font size="3">New user</font></strong></td>
  </tr>
  <tr>
    <td height="38" colspan="2"><font size="3"><a href="signup.php">Sign up</a></font></td>
  </tr>
  <tr>
    <td height="138">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>