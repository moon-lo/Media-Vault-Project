<?php
	include '/var/www/html/Media-Vault-Project/Media-Vault-Project/php-files/sql_functions.php';
	include '/var/www/html/Media-Vault-Project/Media-Vault-Project/php-files/download_functions.php';


	if(isset($_GET['shareId'])){
		$fileId = (int)$_GET['shareId'];
		
		$query = "SELECT filename, location FROM downloads WHERE fileId = {$fileId}";
		
		$results = queryDB($query);
		

		$filename = $results[0]['filename'];
		$location = $results[0]['location'];
		downloadFile($filename, $location);
		echo "downloading file";
		$remove = 'DELETE FROM downloads WHERE fileId = :fileId';
		$parameters = array(
			':fileId' => $fileId
		);
		alterDB($remove, $parameters);
		
		$pdo = null;

		
	}


?>