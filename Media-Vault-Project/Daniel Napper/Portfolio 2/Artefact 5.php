<?php
$max_storage = (50 * 1024);



    if (!isset($_SESSION['isUser']))
    {
        header("Location: http://{$_SERVER['HTTP_HOST']}/Media-Vault-Project/Media-Vault-Project/logout.php");
        exit();
    }
    
    $accountName = $_SESSION['isUser'];

?>
	<body>
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
		</nav><br><br>
	
		<div style="background-color:transparent !important" class="jumbotron_logout">
		  <h1>Oops, looks like you've logged out.</h1><br>      
		  <p>You must be signed in to access this page. To sign in with your account click <a href="index.php">here</a>. If you do not have an account you can sign up for one by clicking <a href="index.php">here</a>.</p>
		</div>
		
	</body>


