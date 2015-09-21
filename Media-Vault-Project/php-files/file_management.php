<?php

	/**
	 * Writes the entire contents of a given PDO to rows of a table.
	 *
	 * @param (PDO) - $pdo - the PDO to be written to the table.
	 * @param (array) - $columns - the String values of the PDO's columns to be written.
	 * * NOTE: All cells are hyperlinked individually
     *         Date string is reformatted
     *         Size string is reformatted
	 *
	 * @author James Galloway
	 */
function writeTable($pdo, $columns, $selectedFile, $isFolder) {
	if ($pdo == null) {
        echo "<tr id='listingRow'><td>No files to display</td></tr>";
    } else {
        foreach ($pdo as $row) {
		    echo "<tr  id='listingRow'>";
		    foreach ($columns as $column) {
                    if ($column == 'timestamp') {
                        $row[$column] = date("g:i a - d.m.y", strtotime($row[$column]));
                    }
                    if ($column == 'filesize') {
                        $row[$column] = round($row[$column] / 1024);
                        $row[$column] = $row[$column] . " KB";
                    }
                    if ($row['filename'] == $selectedFile && $isFolder) {
                        echo "<td><a href='directory.php?selectedFolder=" . $row['filename'] . "'>" . $row[$column] . "</a></td>";
                    } else {
				        echo "<td><a href='directory.php?selectedFile=" . $row['filename'] . "'>" . $row[$column] . "</a></td>";
                    }
		    }
		    echo "</tr>";
	    }
    }
} // end writeTable

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
		return true;
	} else {
		echo "<p>There was an error in uploading the file.</p>";
		print_r(error_get_last());
		return false;
	}
} // end uploadFile

/** Delete Related Functions **/
    
   	/**
	 * Delete selected file.
	 *
	 * @param $file - string - the file to be deleted.
	 *
	 * @author Christian Ruiz
	 */
function deleteFile($file, $currentLocation) {
	$file = ROOT_DIR . '/' . $currentLocation . $file;
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
                <input type='submit' name='newFolderForm' value='Create'>
                <input type='submit' name='newFolderForm' value='Cancel'>
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

/** Move File Related Functions **/

    /**
     * Retrieve a list of valid folders from the database and write them
     * to a dropdown menu.
     *
     * @param $currentUserID - the $_SESSION ID of the current user. Used to compare table values.
     * @param $selectedFile - string name of the selected file.
     *
     * @author Christian Ruiz 
     */
function writeFolders($currentUserID, $selectedFile) {       
    // Get valid folders.
    $pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $folders = $pdo->prepare('SELECT * FROM metadata WHERE filetype = "folder"');
        $folders->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Write dropdown menu.
    echo "<div class='simpleInputDiv'>
            <form action='' 'method='get' class='simpleInputForm'>
                <input type='hidden' value='" . $selectedFile . "' name='selectedFile'>
                <select name='folderMenu'>";

    foreach ($folders as $singleFolder) {
        echo '<option value="' . $singleFolder['filename'] . '">' . $singleFolder['filename'] . '</option>';
    }

    echo '          <option value="uploads">Uploads</option>";
                </select>
               <input type="submit" name="selectFolderButton" value="Move">
               <input type="submit" name="selectFolderButton" value="Cancel">
            </form>
         </div>';
} // end writeFolders


    /**
    * Moves file from current position to destination folder.
    *
    * @param $file - string - file to be moved.
    * @param $location - string - the location of the file to be moved.
    * @param $folder - string - destination folder.
    *
    * @author Christian Ruiz & James Galloway
    */
function moveFile($file, $location, $folder) {
    $filePath = ROOT_DIR . '/' . $location . $file;
    $folderPath = ROOT_DIR . '/' . $location . $folder . '/' . $file;

    if (rename($filePath, $folderPath)) {
        echo "<p>File: " . $file . " has been successfully moved to " . $folder . "</p>";
        return true;
    }

    return false;
} // end moveFile

?>