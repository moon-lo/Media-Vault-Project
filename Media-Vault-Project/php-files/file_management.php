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
function writeTable($pdo, $columns, $selectedFile, $isFolder, $currentDir, $username, $searchStr) {
    if ($pdo == null) {
        echo "<tr id='listingRow'><td>No files to display</td></tr>";
    } else {
        foreach ($pdo as $row) {
			echo '<tr class="listingRow">';
			$colour = $row['colour'];
			$colourStyle = '';
			if ($colour != null && $colour != '' && $colour != 'none'){
				$colourStyle = " style='background-colour:$colour'";
			}
		    foreach ($columns as $column) {
                    if ($column == 'filename') {
                        $sortKey = strtolower(substr($row[$column], 0, 1));
                    }
                    if ($column == 'filetype') {
                        $sortKey = substr($row[$column], 0, 1);
                        $row[$column] = selectIcon($row[$column]);
                    }
                    if ($column == 'timestamp') {
                        $sortKey = $row[$column];
                        $row[$column] = date("g:i a - d.m.y", strtotime($row[$column]));
                    }
                    if ($column == 'filesize') {
                        $sortKey = $row[$column];
                        $row[$column] = round($row[$column] / 1024);
                        $row[$column] = $row[$column] . " KB";
                    }
                    if ($row['filename'] == $selectedFile && $isFolder) {
                        echo '<td sortKey="' . $sortKey . '" class="selectedFile"><a href="directory.php?currentDir=' . $currentDir . $row['filename'] . '/">' . $row[$column] . '</a></td>';
                    } else if ($row['filename'] == $selectedFile && !$isFolder) {
				        echo '<td sortKey="' . $sortKey . '" class="selectedFile"><a href="directory.php?currentDir=' . $currentDir . '&selectedFile=' . $row['filename'] . '">' . $row[$column] . '</a></td>'; 		
                    } 
					else if ($column == 'colour'){
						echo "<td $colourStyle>abc</td>";
					}
					else {
                        echo '<td sortKey="' . $sortKey . '" ><a href="directory.php?currentDir=' . $currentDir . '&selectedFile=' . $row['filename'] . '">' . $row[$column] . '</a></td>';
                    }
		    }
		    echo "</tr>";
        }
    }
} // end writeTable


/**
 * Similar to writeTable.  The difference is in the href attribute values specified by each listing.  
 */
function writeSearchResults($pdo, $columns, $username) {
    if ($pdo == null) {
        echo "<tr id='listingRow'><td>No matches were found</td></tr>";
    } else {
        foreach ($pdo as $row) {
            $dir = $row['location'];
            echo '<tr class="listingRow">';
		    foreach ($columns as $column) {
                if ($column == 'filename') {
                    $sortKey = strtolower(substr($row[$column], 0, 1));
                }
                if ($column == 'filetype') {
                    $sortKey = substr($row[$column], 0, 1);
                    $row[$column] = selectIcon($row[$column]);
                }
                if ($column == 'timestamp') {
                    $sortKey = $row[$column];
                    $row[$column] = date("g:i a - d.m.y", strtotime($row[$column]));
                }
                if ($column == 'filesize') {
                    $sortKey = $row[$column];
                    $row[$column] = round($row[$column] / 1024);
                    $row[$column] = $row[$column] . " KB";
                }
                if ($column == 'location') {
                    $sortKey = count(explode("/", $row[$column]));
                    $row[$column] = 'Home/' . substr($row[$column], strlen('uploads/' . $username . '/'), strlen($row[$column]));
                }
                echo '<td sortKey="' . $sortKey . '"><a href="directory.php?currentDir=' . $dir . '&selectedFile=' . $row['filename'] . '">' . $row[$column] . '</a></td>';
            }
		}
	    echo "</tr>";
    }
} // end writeSearchResults

    /**
     * Select the icon that applies to a particular file's type.
     * 
     * @return str - the string location of the appropriate icon.
     */
function selectIcon($type) {
    if ($type == 'folder') {
        $icon = 'folder.png';
    } else if (strpos($type, 'application') !== false) {
        $icon = 'executable.png';
    } else if (strpos($type, 'image') !== false) {
        $icon = 'image.png';
    } else if (strpos($type, 'audio') !== false) {
        $icon = 'audio.png';
    } else if (strpos($type, 'text') !== false) {
        $icon = 'text.png';
    } else if (strpos($type, 'video') !== false) {
        $icon = 'video.png';
    } else if (strpos($type, 'zip') !== false || strpos($type, 'rar') !== false) {
        $icon = 'zip.png';
    } else {
        $icon = 'unknown.png';
    }

     return $image = '<img src="images/' . $icon . '" width="30" height="40" alt="File type icon" />';
} // end selectIcon

///**
// * Determine the correct sorting values for column heading links.
// *
// * @param str $heading - string name of the column to be sorted.
// *
// * @author James Galloway
// */
//function getSortURL($heading) {
//    $url = $_SERVER['REQUEST_URI'];

//    // Remove prior $_GET information if it exists
//    if (strpos($url, '&sort=') !== false) {
//        $url = substr($url, 0, strpos($url, '&sort'));
//    }

//    if (strpos($_SERVER['REQUEST_URI'], '&sort=' . $heading) !== false) {
//        return $url = $url . '&sort=' . $heading . '&sortType=DESC';
//    }
//    return $url = $url . '&sort=' . $heading . '&sortType=ASC';
//} // end getSortURL

/** Upload Related Functions **/

	/**
	 * Upload file to server uploads folder
	 *
	 * @author James Galloway
	 */ 
function uploadFile($currentUser) {
	$dir = ROOT_DIR . '/uploads/' . $currentUser . '/';
	$file = $dir . basename($_FILES["file"]["name"]);
	$fileExtension = pathinfo($file, PATHINFO_EXTENSION);
	
    // Check to see if the user has selected a file.
    if ($_FILES["file"]["error"] == 4) {
        echo "<p>Please select a file.</p>";
        return false;
    }

	// Check if user has a file of the same name in the uploads folder
    $sql = 'SELECT * FROM metadata WHERE owner = "' . $currentUser . '" AND location = "uploads/' . $currentUser . '"';
    $userFiles = queryDB($sql);
    foreach ($userFiles as $userFile) {
        if ($userFile['filename'] == $_FILES["file"]["name"]) {
            echo "<p>A file of the same name already exists in your home directory.</p>";
            return false;
        }
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
     * Simple function to echo the delete confirmation form.
     *
     * @author James Galloway
     */
function writeDeleteConfirmation($file, $currentDir) {
    echo '<div class="simpleInputDiv">
            <form action="" "method="get" class="simpleInputForm">
                <p>Are you sure you want to delete ' . $file . '?</p>
                <input type="hidden" value="' . $file . '" name="selectedFile">
                <input type="hidden" value="' . $currentDir . '" name="currentDir">
                <input type="submit" name="confirmDelete" value="Yes">
                <input type="submit" name="confirmDelete" value="No">
            </form>
        </div>';
} // end deleteConfirmation

   	/**
	 * Delete selected file.
	 *
	 * @param $file - string - the file to be deleted.
	 *
	 * @author Christian Ruiz
	 */
function deleteFile($file, $currentDir) {
	$file = ROOT_DIR . '/' . $currentDir . $file;
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
function writeRenameForm($selectedFile, $currentDir) {
    echo "<div class='simpleInputDiv'>
        <form action='' 'method='get' class='simpleInputForm'>
            <input type='hidden' value='" . $selectedFile . "' name='oldName'>
            <input type='hidden' value='" . $currentDir . "' name='currentDir'>
            <input type='text' name='newName'>
            <input type='submit' name='newNameSet' value='Rename'>
            <input type='submit' name='newNameSet' value='Cancel'>
        </form>
    </div>";
} // end writeRenameForm

/**
     * Function to create text input form onto page for user to add a description to their selected file.
     *
     * @param $selectedFile - string - file name of selected file - stored in hidden input
     *
     * @author Thomas Shortt
     */
function writeDescriptionForm($selectedFile, $currentDir) {
    echo "<div class='simpleInputDiv'>
        <form action='' 'method='POST' class='simpleInputForm'>
            <input type='hidden' value='" . $selectedFile . "' name='oldName'>
            <input type='hidden' value='" . $currentDir . "' name='currentDir'>
            <input type='text' name='newDescription'>
            <input type='submit' name='newDSet' value='Add description'>
            <input type='submit' name='newDSet' value='Cancel'>
        </form>
    </div>";
} // end writeDescriptionForm

function writeSearchForm() {
    echo '<form name="searchForm" action="search.php" method="POST">
		    <input type="text" name="searchStr" value="">
            <input type="submit" name="searchButton" value="Search">
	      </form>';
} // end writeSearchForm

    /**
     * Rename selected file.
     * 
     * @param $oldName - string - the original name of the file.
     * @param $newName - string - the new name for the file specified by the user.
     * 
     * @author James Galloway
     */
function renameFile($oldName, $newName, $currentDir) {
    $fileExtension = pathinfo($oldName, PATHINFO_EXTENSION);
    if (file_exists(ROOT_DIR . '/uploads/' . $newName . '.' . $fileExtension)) {
        echo "<p>A file of that name already exists.  Please choose a different name.</p>";
        return false;
    }
    if (rename(ROOT_DIR . '/' . $currentDir . $oldName, ROOT_DIR . '/' . $currentDir . $newName . '.' . $fileExtension)) {
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
function writeNewFolderForm($currentDir) {
    echo "<div class='simpleInputDiv'>
            <form action='' 'method='get' class='simpleInputForm'>
                <input type='hidden' name='currentDir' value='" . $currentDir . "'>
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
function newFolder($name, $currentDir) {
    if (mkdir(ROOT_DIR . '/' . $currentDir . $name, 0755)) {
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
     * @param $owner - the $_SESSION ID of the current user. Used to compare table values.
     * @param $selectedFile - string name of the selected file.
     *
     * @author Christian Ruiz 
     */
function writeFolders($owner, $selectedFile) {       
    $sql = 'SELECT * FROM metadata WHERE filetype = "folder" AND owner = "' . $owner . '"';
    $folders = queryDB($sql);
    // Write dropdown menu.
    echo "<div class='simpleInputDiv'>
            <form action='' 'method='get' class='simpleInputForm'>
                <input type='hidden' value='" . $selectedFile . "' name='selectedFile'>
                <select name='folderMenu'>";
    foreach ($folders as $singleFolder) {
        echo '<option value="' . $singleFolder['filename'] . '">' . $singleFolder['filename'] . '</option>';
    }
    echo '          <option value="uploads/' . $owner . '/">Home - ' . $owner . '</option>";
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
function moveFile($file, $folder, $currentUser) {
    $sql = "SELECT * FROM metadata WHERE filename = '$file'";
    $tempPath = queryDB($sql);
    $filePath = ROOT_DIR . '/' . $tempPath[0]['location'];
    
    $sql = "SELECT location FROM metadata WHERE filename = '$folder'";
    $tempPath = queryDB($sql);
    if ($tempPath == null) {
        $DBentry = 'uploads/' . $currentUser . '/';
        $folderPath = ROOT_DIR . '/' . $DBentry;
    } else {
        $DBentry = $tempPath[0]['location'] . $folder . '/';
        $folderPath = ROOT_DIR . '/' . $DBentry;
    }
    if (rename($filePath . $file, $folderPath . $file)) {
        echo "<p>File: " . $file . " has been successfully moved to " . $folder . "</p>";
        return $DBentry;
    }
    return false;
} // end moveFile

    /**
     * Simple helper method to determine whether a particular $_GET element is set and not empty.
     *
     * @param $element - string - the name of the element to be checked.
     *
     * @return - Boolean - true if element is set and not empty - false if not.
     *
     * @author James Galloway
     */
function isSetAndNotEmpty($method, $element) {
    if (isset($method[$element]) && $method[$element] !== '') {
        return true;
    }
    return false;
} // end isSetAndNotEmpty

?>