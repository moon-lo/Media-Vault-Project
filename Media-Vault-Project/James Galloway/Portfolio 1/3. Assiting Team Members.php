<?php

// THIS FUNCTION INSIDE 'file_management.php'
    /**
     * Retrieve a list of valid folders from the database and write them
     * to a dropdown menu.
     *
     * @param $currentUserID - the $_SESSION ID of the current user. Used to compare table values.
     * @param $selectedFile - string name of the selected file.
     *
     */
function writeFolders($currentUserID, $selectedFile) {    
    // Added $selectedFile - just the string name of the selected file to be passed to the method - otherwise it won't be visible to things inside this function.

    // Added $currentUserID - The ID allocated to the session ($_SESSION) is what we need to compare in the table.
    // 'user = user' will only check the column 'user' for the string 'user'.  We need to check the column 'id' for the session ID, which should be the ID 
    // of a particular user.

    // Removed line '$folderSelection = $_get('moveTo');' - it is not being used.  Did you want to use this to pass the selected folder somewhere?  If so, that's
    // done in the form information below.
    
    $sql = 'SELECT * FROM metadata WHERE filetype = "folder"';
    $folders = readTable($sql);

    // Need to connect to the database first and assign a PDO first.
    $pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
    
    // Surrounded execute(); in a try/catch block to catch and print errors if they occur.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        // Change parameter name $movef to $folders for less ambiguity.
        $folders = $pdo->prepare('SELECT * FROM metadata WHERE filetype = "folder"');
        $folders->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Must have the dropdown menu in a form so that the selected value can be submitted - otherwise a selection won't be sent to the server.
    // 'method='get' might be what you were looking for.  This will send form values to the next page-load, including the $selectedFile from before and also the $selectedFolder.
    // Add a hidden input (type='hidden') to store the string name of the selected file.  This is necessary to hold the value between page-loads.
    echo "<div class='simpleInputDiv'>
            <form action='' 'method='get' class='simpleInputForm'>
                <input type='hidden' value='" . $selectedFile . "' name='selectedFile'>
                <select name='folderMenu'>";
            
    // Changed parameter name from $selectedFolder to $singleFolder - less ambiguity.
    // The value of each option must be set.  This is the value that is sent by the form.  The text between <option></option> is just the string that is printed to the dropdown menu.
    // The $folders we've retrieved from the database are PDO(PHP Data Objects) files.  So they have multiple values associated with them.  These values are those found in the database.
    // So we can use  $singleFolder['filename'] to get the actual name of the folder each time. 'filename' doesn't come out of nowhere though - this is what we've named the column in our database.
    foreach ($folders as $singleFolder) {
        echo "<option value='" . $singleFolder['filename'] . "'>" . $singleFolder['filename'] . "</option>";
    }

    echo "     </select>
               <input type='submit' name='selectFolderButton' value='Move'>
               <input type='submit' name='selectFolderButton' value='Cancel'>
            </form>
         </div>";
} // end writeFolders

// THIS FUNCTION INSIDE 'file_management.php'
    /**
    * Moves file from current position to destination folder.
    *
    * @param $file - string - file to be moved.
    * @param $location - string - the location of the file to be moved.
    * @param $folder - string - destination folder.
    *
    */
function moveFile($file, $location, $folder) {
    // Have to add the full path of the file otherwise it won't be found.  ROOT_DIR is defined in 'directory.php' and should be "/var/www/html/Media-Vault-Project/Media-Vault-Project"
    // Realised an error in my earlier code - have fixed it now so that file location is accessible.
    $filePath = ROOT_DIR . '/' . $location . $file;
    $folderPath = ROOT_DIR . '/' . $location . $folder . '/' . $file;

    // Rename is probably the function you were looking for.  It's a bit strange but you can rename a file 'into' another folder without actually changing 
    // the file's name.  Weird, ambiguous, and kind of annoying, I know.
    if (rename($filePath, $folderPath)) {
        echo "<p>File: " . $file . " has been successfully moved to " . $folder . "</p>";
        return true;
    }

    return false;
} // end moveFile

// THIS SCRIPT INSIDE 'directory.php'
	
    // If the 'move to...' button is clicked AND a file is selected - call the writeFolders() function to print dropdown menu.
    // $fileFlag is just a boolean value - true if a file is selected, false if not.
    if(isset($_GET['moveTo']) && $fileFlag) {
        writeFolders(null, $selectedFile); // Passing 'null' into the function for now because no user stuff has been implemented.
    }

    if (isset($_GET['selectFolderButton'])) { // if 'selectedFolder' is set
        if ($_GET['selectFolderButton'] == 'Move') { // if the Move button was clicked (as opposed to 'Cancel')
            if (moveFile($selectedFile, $currentLocation, $_GET['folderMenu'])) { // the moveFile() method returns true or false depending on whether it succeeds or not.  This basically says "If moveFile succeeds, change file location in the database".
                renameFileLocationRecord($selectedFile, $currentLocation, $_GET['folderMenu']);
            } 
        }
    }

// FOR COMPLETENESS - THE DATABASE RECORD FUNCTION - INSIDE 'sql_functions.php'
	 
	 /**
     * Alter table to reflect proper location of a file post-move.
     *
     * @param $file - the filename of the record to be altered.
     * @param $newLocation - string - the new location (file path starting from uploads/) of the file.
     *
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