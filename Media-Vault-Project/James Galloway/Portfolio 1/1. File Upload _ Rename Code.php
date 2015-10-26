<?php

    /**
     * Establishes a connection with a database and executes the specified prepared statement.
     * 
     * @param (String) - $sql - the MySQL statement to be executed.
     * @param (Array) - $parameters - array of values to be bound.  Must be in the following format: array(':bindString' => $bindValue)
     *
     * @author James Galloway
     */
function alterDB($sql, $parameters) {
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'xx', 'xx');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$result = $pdo->query('SELECT * FROM metadata');
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$stmt = $pdo->prepare($sql);
	$stmt->execute($parameters);
	$pdo = null;
} // end alterTable

	/**
	 * Upload file to server uploads folder
	 *
	 * @author James Galloway
	 */ 
function uploadFile() {
	$dir = ROOT_DIR . '/uploads/';
	$file = $dir . basename($_FILES["file"]["name"]);
	$fileExtension = pathinfo($file, PATHINFO_EXTENSION);
	$validFile = true;
	
	// Check if file already exists
	if (file_exists($file)) {
		echo "<p>File already exists.</p>";
		$validFile = false;
	}
	
	// Check file extension against extension whitelist
	$whitelist = file('file_extension_whitelist.txt', FILE_IGNORE_NEW_LINES);
	if (!in_array($fileExtension, $whitelist)) {
		echo "<p>File is not of a valid type.</p>";
		$validFile = false;
	}
	
	if (!$validFile) {
		return false;
	}
	
	// Upload file
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
		echo "<p>File:  " . basename($_FILES["file"]["name"]) . " was successfully uploaded.</p>";
		return true;
	} else {
		echo "<p>There was an error in uploading the file.</p>";
		print_r(error_get_last());
		return false;
	}
} // end uploadFile

	/**
	 * Add file record to MySQL DB - 'metadata' table.
	 * * Only includes file name, type and size at the moment *
	 *
	 * @author James Galloway
	 */
function addUploadRecord() {	
    $sql = "INSERT INTO metadata (filename, filetype, filesize, location)
	        VALUES (:filename, :filetype, :filesize, :location)";
    $parameters = array(
        ':filename' => $_FILES["file"]["name"],
        ':filetype' => $_FILES["file"]["type"],
        ':filesize' => $_FILES["file"]["size"],
        ':location' => 'uploads/'
    );
	alterDB($sql, $parameters);
} // end addUploadRecord


    /**
     * Rename selected file.
     * 
     * @param $oldName - string - the original name of the file.
     * @param $newName - string - the new name for the file specified by the user.
     * 
     * @author James Galloway
     */
function renameFile($oldName, $newName) {
    $fileExtension = pathinfo($oldName, PATHINFO_EXTENSION);

    if (file_exists(ROOT_DIR . '/uploads/' . $newName . '.' . $fileExtension)) {
        echo "<p>A file of that name already exists.  Please choose a different name.</p>";
        return false;
    }

    if (rename(ROOT_DIR . '/uploads/' . $oldName, ROOT_DIR . '/uploads/' . $newName . '.' . $fileExtension)) {
        echo "<p>File successfully renamed</p>";
        return true;
    }
    echo "<p>There was an error in renaming the file.</p>";
    return false;
} // end renameFile

    /**
     * Rename a file's associated record in the metadata table.
     * 
     * @param $oldName - string - the original name of the file.
     * @param $newName - string - the new name for the file.
     * 
     * @author James Galloway
     */
function renameFileRecord($oldName, $newName) { 
    $sql = "UPDATE metadata SET filename = :newName WHERE filename = :oldName";
    $fileExtension = pathinfo($oldName, PATHINFO_EXTENSION);
    $parameters = array(
        ':newName' => $newName . '.' . $fileExtension,
        ':oldName' => $oldName
    );
    alterDB($sql, $parameters);
} // end renameFileRecord

?>