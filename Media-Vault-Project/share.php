<?php
	include '/php-files/sql_functions.php';
	include '/php-files/download_functions.php';

	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if(isset($_GET['shareId'])){
		$fileId = (int)$_GET['shareId'];
		
		$query = "SELECT filename, location FROM downloads WHERE fileId = {$fileId}";
		
		$results = queryDB($query);
		
		if(mysql_num_rows($results) != 1){
			echo "file missing, or link expired.";
		}else{
			$filename = $results[0]['filename'];
			$location = $results[0]['location'];
			downloadFile($filename, $location);
			
			$remove = 'DELETE FROM downloads WHERE fileId = :fileId';
			$parameters = array(
				':fileId' => $fileId
			);
			alterDB($remove, $parameters);
		}
		$pdo = null;

		
	}


?>