<?php 

	/**
	 * Establishes a connection with a database and executes the specified query.
	 *
	 * @param (String) - $sql - the MySQL query to be executed.
	 * @return (PDO) - $result - the PDO result of the query.
	 * 
	 * @author James Galloway
	 */
function readTable($sql) {
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$result = $pdo->prepare($sql);
        $result->execute();
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
    $result = $result->fetchAll();
	$pdo = null;
	return $result;
} // end read_table

	/**
	 * Delete a file's associated record in the metadata table.
	 *
	 * @param $file - string - the file name of the record to be deleted.
	 *
	 * @author James Galloway
	 */
function deleteFileRecord($file) {	
	$sql = "DELETE FROM metadata WHERE filename = :filename";
	
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$result = $pdo->query('SELECT * FROM metadata');
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':filename', $file);
	$stmt->execute();

	$pdo = null;
} // end deleteFileRecord

	/**
	 * Add file record to MySQL DB - 'metadata' table.
	 * * Only includes file name, type and size at the moment *
	 *
	 * @author James Galloway
	 */
function addUploadRecord() {	
	$filename = $_FILES["file"]["name"];
	$filetype = $_FILES["file"]["type"];
	$filesize = $_FILES["file"]["size"];
	
	$sql = "INSERT INTO metadata (filename, filetype, filesize)
			VALUES (:filename, :filetype, :filesize)";
	
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$result = $pdo->query('SELECT * FROM metadata');
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':filename', $filename);
	$stmt->bindValue(':filetype', $filetype);
	$stmt->bindValue(':filesize', $filesize);
	$stmt->execute();

	$pdo = null;
} // end addUploadRecord


    /**
     * Rename a file's asscoiated record in the metadata table.
     * 
     * @param $oldName - string - the original name of the file.
     * @param $newName - string - the new name for the file.
     * 
     * @author James Galloway
     */
function renameFileRecord($oldName, $newName) {
    $fileExtension = pathinfo($oldName, PATHINFO_EXTENSION);
    $sql = "UPDATE metadata SET filename = :newName WHERE filename = :oldName";
	
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$result = $pdo->query('SELECT * FROM metadata');
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':newName', $newName . '.' . $fileExtension);
	$stmt->bindValue(':oldName', $oldName);
	$stmt->execute();

	$pdo = null;
} // end renameFileRecord

    /**
     * Add record for new folder into metadata table.
     *
     * @param $name - string - the name of the folder to be created.
     *
     * @author James Galloway
     */
function newFolderRecord($name) {
	$sql = "INSERT INTO metadata (filename, filetype, filesize)
			VALUES (:filename, :filetype, :filesize)";
	
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$result = $pdo->query('SELECT * FROM metadata');
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':filename', $name);
	$stmt->bindValue(':filetype', 'folder');
	$stmt->bindValue(':filesize', '0');
	$stmt->execute();

	$pdo = null;
} // end newFolderRecord

    /**
     * Alter table to reflect proper location of a file post-move.
     *
     * @param $file - the filename of the record to be altered.
     * @param $newLocation - string - the new location (file path starting from uploads/) of the file.
     *
     * @author James Galloway
     */
function renameFileLocationRecord($file, $currentLocation, $newLocation) {
    $location = $currentLocation . $newLocation;
    $sql = "UPDATE metadata SET location = :newLocation WHERE filename = :file";
	
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$result = $pdo->query('SELECT * FROM metadata');
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':newLocation', $location);
	$stmt->bindValue(':file', $file);
	$stmt->execute();

	$pdo = null;
} // end renameFileLocationRecord


?>