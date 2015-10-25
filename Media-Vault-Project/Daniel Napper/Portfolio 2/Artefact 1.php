<?php 
$max_storage = (50 * 1024);


	    try {
		    $result = $pdo->query("select (select sum(filesize) from metadata where metadata.owner = users.username) current_storage1, max_storage from users where username = '$accountName'");
	    } catch (PDOException $e) {
		    echo $e->getMessage();
	    }
	
	    $pdo = null;
	    $rows = $result->fetchAll();
	    $row = $rows[0];
	    $space = round($row['current_storage1'] / 1024, 2) . 'KB / ' . $row['max_storage'] . "KB";
	?>
	<nav class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="directory.php">Team 12 Media Vault</a>
		</div>
        <div id="navbar" class="navbar-collapse collapse">
		  <ul class="nav navbar-nav navbar-right">
			<li><a href="upload.php">Upload</a></li>
			<li><a  class="bottom" href="#" data-toggle="tooltip" data-placement="bottom" title="Current Storage Space: <?php echo $space; ?>"><?php echo $accountName ?></a></li>
			<li><a href="logout.php">Log out</a></li>
		  </ul>
		  <form class="navbar-form navbar-right" name="searchForm" action="" method="GET">
                <input type="text" name="searchStr" class="form-control" placeholder="Search...">
		  </form>
	    </div>
	  </div>
	</nav>

	<?php
	function addUploadRecord($owner) {	
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO metadata (filename, filetype, filesize, location, owner)
	        VALUES (:filename, :filetype, :filesize, :location, :owner)";
    $parameters = array(
        ':filename' => $_FILES["file"]["name"],
        ':filetype' => $_FILES["file"]["type"],
        ':filesize' => $_FILES["file"]["size"],
        ':location' => 'uploads/' . $owner . '/',
        ':owner' => $owner,
    );
	try {
		$result = $pdo->query("select (select sum(filesize) from metadata where metadata.owner = users.username) current_storage1, max_storage from users where username = '$owner'");
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$pdo = null;
	$rows = $result->fetchAll();
	$row = $rows[0];
	if ((($_FILES["file"]["size"] + $row['current_storage1']) / 1024) > $row['max_storage']){
		echo '<script language="javascript">';
		echo 'alert("Not enough storage space left!")';
		echo '</script>';
	}
	else {
		alterDB($sql, $parameters);
	}
	
	
	
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$result = $pdo->query("select (select sum(filesize) from metadata where metadata.owner = users.username) current_storage1, max_storage from users where username = '$currentUser'");
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	$pdo = null;
	$rows = $result->fetchAll();
	$row = $rows[0];
	if ((($_FILES["file"]["size"] + $row['current_storage1']) / 1024) > $row['max_storage']){
		echo "<p>There is not enough storage space left for that file.</p>";
		return true;
	}
	else if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
		return true;
	} 
	else {
		echo "<p>There was an error in uploading the file.</p>";
		print_r(error_get_last());
		return false;
	}
	
	
	
	?>
	