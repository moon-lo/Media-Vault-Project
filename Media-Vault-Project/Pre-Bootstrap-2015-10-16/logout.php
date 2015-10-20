<?php
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	session_start();
	
	if (isset($_SESSION['isUser'])) {
		unset($_SESSION['isUser']);
		header("Location: http://{$_SERVER['HTTP_HOST']}/Media-Vault-Project/Media-Vault-Project/index.php");
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sorry</title>
</head>
<body>
You must be signed in to access this page. To sign in with your account click <a href="index.php">here</a>. If you do not have an account you can sign up for one by clicking <a href="signup.php">here</a>.
</body>
</html>
