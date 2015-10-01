<?php
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//Set error variable.
	$error = '';
	
	//Determine if the Sign Up button has been pressed.
	if (isset($_POST['Account']))
	{
		//Set the email, username and password values to a variable.
		$email = $_POST['email'];
		$username = $_POST['username'];
		$newPassword = $_POST['password'];
		$confirmPassword = $_POST['confirmPassword'];
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
				
			try
			{
				//Use a prepared statement to insert the data into the users table.
				$stmt = $pdo->prepare("INSERT INTO users (username, email, password, salt) ".
				"VALUES ('$username','$email', SHA2(CONCAT('$newPassword', '$randomSalt'), 0), '$randomSalt')");
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
				//header("Location: http://localhost/mediavault/directory.php");
                header("Location: http://{$_SERVER['HTTP_HOST']}/Media-Vault-Project/Media-Vault-Project/index.php");
				exit();
			}
			else
			{
				$error = 'Error creating account. The username you are trying to use may have been taken. Please try again.';
			}
		}
	}
?>
<!-- Author: Lok Sum (Moon) Lo -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sign up</title>
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
<table width="100%" border="0" style="float: right;">
  <tr>
    <td width="40%" height="130">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="40%">&nbsp;</td>
  </tr>
  <tr>
    <td height="285" rowspan="2">&nbsp;</td>
    <td height="25" colspan="2"><p>&nbsp;</p></td>
    <td rowspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><div align="left">
    <p><strong><font size="3">Create new account</font></strong></p>
      <form action="signup.php" method="POST" name="signup">
        <p>Email address:<br />
		  <input type="text" name="email" value="" />
		  <br /><br />
		  Username:<br />
		  <input type="text" name="username" value="" />
		  <br /><br />
          Password:<br />
          <input type="password" name="password" value="" />
          <br /><br />
          Confirm password:<br />
          <input type="password" name="confirmPassword" value="" />
          <br /><br />
          <input type="submit" name="Account" value="Sign up" />
		  <br /><br />
		  <span><?php echo $error; ?></span>
        </p>
      </form>
    </div></td>
  </tr>
  <tr>
    <td height="171">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
