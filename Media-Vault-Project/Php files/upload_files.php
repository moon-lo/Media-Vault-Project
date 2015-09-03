<?php
	/**
	 * Upload file to server.
	 *
	 * @author James Galloway
	 */ 
	function upload_file() {
		$dir = "/var/www/html/uploads/";
		$file = $dir . basename($_FILES["file"]["name"]);
		$filetype = $_FILES["file"]["type"];
		$uploadOk = 1;
		
		// Check if file already exists
		if (file_exists($file)) {
			echo "<p>File already exists.</p>";
			$uploadOk = 0;
		}
		
		// Upload file
		if ($uploadOk == 0) {
			echo "<p>File could not uploaded.</p>";
			return false;
		} else {
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
				echo "<p>File: " . basename($_FILES["file"]["name"]) . " was successfully uploaded.</p>";
				return true;
			} else {
				echo "<p>There was an error in uploading the file.</p>";
				print_r(error_get_last());
				return false;
			}
		}
	} // end upload_file
	
	/**
	 * Add file record to MySQL DB - 'metadata' table.
	 * * Only includes file name, type and size at the moment *
	 *
	 * @author James Galloway
	 */
	function add_record() {
		$filename = $_FILES["file"]["name"];
		$filetype = $_FILES["file"]["type"];
		$filesize = $_FILES["file"]["size"];
		
		$sql = "INSERT INTO metadata (filename, filetype, filesize)
				VALUES (:filename, :filetype, :filesize)";
		
		$pdo = new PDO('mysql:host=54.206.80.50:3306;dbname=mediavault', 'root', 'password');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$result = $pdo->query('SELECT * FROM metadata');
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':filename', $filename);
		$stmt->bindValue(':filetype', $filetype);
		$stmt->bindValue(':fiesize', $filesize);
		$stmt->execute();

		$pdo = null;
	} // end add_record
?>