<?php

/** Upload Related Functions **/

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
        
        echo $file;
        chown($file, 'iis apppool\defaultapppool');
        echo substr(sprintf('%o', fileperms($file)), -4);

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

/** Delete Related Functions **/
    
   	/**
	 * Delete selected file.
	 *
	 * @param $file - string - the file to be deleted.
	 *
	 * @author Christian Ruiz
	 */
function deleteFile($file) {
	$file = ROOT_DIR . '/uploads/' . $file;
    if (is_dir($file)) {
        if (rmdir($file)) {
            echo "<p>Folder successfully deleted</p>";
            return true;
        }
    } else {
        if (unlink($file)) {
		    echo "<p>File successfully deleted</p>";
		    return true;
	    } 
    }
	return false;
} // end deleteFile

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

/** Rename Related Functions **/
    
    /**
     * Simple function to echo text input form onto page for user to input new file name.
     *
     * @param $selectedFile - string - file name of selected file - stored in hidden input
     *
     * @author James Galloway
     */
    function writeRenameForm($selectedFile) {
        echo "<div class='simpleInputDiv'>
            <form action='' 'method='get' class='simpleInputForm'>
                <input type='hidden' value='" . $selectedFile . "' name='oldName'>
                <input type='text' name='newName'>
                <input type='submit' name='newNameSet' value='Rename'>
                <input type='submit' name='newNameSet' value='Cancel'>
            </form>
        </div>";
    } // end writeRenameForm

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

    if (rename(ROOT_DIR . '/uploads/' . $oldName, ROOT_DIR . '/uploads/' . $newName . '.' . $fileExtension)) {
        echo "<p>File successfully renamed</p>";
        return true;
    }
    return false;
} // end renameFile

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

/** Create Folder Related Functions **/

    /**
     * Write input form for user to name new folder.
     *
     * @author James Galloway
     */
function writeNewFolderForm() {
    echo "<div class='simpleInputDiv'>
            <form action='' 'method='get' class='simpleInputForm'>
                <input type='text' name='folderName'>
                <input type='submit' name='newFolder' value='Create'>
                <input type='submit' name='newFolder' value='Cancel'>
            </form>
        </div>";
} // end writeNewFolderForm

    /**
     * Create new folder in the /uploads/ directory.
     * * NOTE: Folder is currently created with widest possible privilege.
     *
     * @param $name - string - the name of the folder to be created.
     *
     * @author James Galloway
     */
function newFolder($name) {
    if (mkdir(ROOT_DIR . '/uploads/' . $name, 0755)) {
        echo "<p>Folder succesfully created.</p>";
        return true;
    }
    return false;
} // end newFolder

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

?>