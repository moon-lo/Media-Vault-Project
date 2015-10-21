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
                writeMessage($error);
			}
		}
	}
?>
<!doctype html>
<html>
	<head>
		<title>Team 12 Media Vault</title>
		<script type="text/javascript" src="javascript/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="javascript/display_functions.js"></script>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		
		<link rel="stylesheet" type="text/css" href="style2.css">
	</head>
	
	<body>
	
	<!-- NAVIGATION BAR -->
	<nav class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="#">Team 12 Media Vault</a>
		</div>
	  </div>
	</nav>
	
	<!-- HEADER -->



	<!-- LOGIN/REGISTER FORM -->
	<!-- Source: http://bootsnipp.com/snippets/featured/login-and-register-tabbed-form -->
	<div class="container">
		<div style="background-color:transparent !important" class="jumbotron">
		  <h1>Team 12 Media Vault</h1>      
		  <p>Welcome to Team 12 Media Vault - your free and easy personal cloud storage system. Simply sign up today to access your files from anywhere.</p>
		</div>
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
							<!-- temporary link to directory -->
								<a href="directory.php" class="active" id="login-form-link" onClick="swapForm(this)">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="#" id="register-form-link" onClick="swapForm(this)">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
                               

                                <!--  <form action="index.php" method="POST" name="SignIn"> -->
                                <form id="login-form" action="index.php" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="">
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="Login" id="Login" tabindex="4" class="form-control btn btn-login" value="Login">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-lg-12">
											</div>
										</div>
									</div>
								</form>
								<form id="register-form" action="#" method="post" role="form" style="display: none;">
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="">
									</div>
									<div class="form-group">
										<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="">
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
									</div>
									<div class="form-group">
										<input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	</body>

</html>