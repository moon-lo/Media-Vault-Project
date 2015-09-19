<?php
    // Define root directory for use in strings later
	define('ROOT_DIR', dirname(__FILE__));
    include ROOT_DIR . '/php-files/file_management.php';
    include ROOT_DIR . '/php-files/sql_functions.php';
	include ROOT_DIR . '/php-files/download_functions.php';
    
    // Assign selected file if set & not null
    $fileFlag = false;
    if (isset($_GET['selectedFile']) && $_GET['selectedFile'] !== '') {
        $query = 'SELECT * FROM metadata WHERE filename = "' . $_GET['selectedFile'] . '"';
        $fileRecord = readTable($query);
        $selectedFile = $fileRecord[0]['filename'];
        $fileType = $fileRecord[0]['filetype'];
        $currentLocation = $fileRecord[0]['location'];
            
        // If the selected file is a folder, add that folder to the file path.  
        // When this path is used for the directory listing, it will return only files inside that folder.
        if ($fileType == "folder") {
            $currentLocation = $currentLocation . $selectedFile . '/';
            echo "<p>" . $currentLocation . "</p>";
        }
        $fileFlag = true;
    } else {
        $currentLocation = "uploads/";
    }

    // Delete file if delete & file are set
	if (isset($_GET['delete']) && $fileFlag) {
	    if (deleteFile($selectedFile)) {
		    deleteFileRecord($selectedFile);
        }
    }

    // Write rename form is edit & file are set
    if (isset($_GET['edit']) && $fileFlag) {
        writeRenameForm($selectedFile);
    }

    // Rename file if new name & file are set
    if (isset($_GET['newNameSet'])) {
        if ($_GET['newNameSet'] == 'Rename') {
            $oldName = $_GET['oldName'];
            $newName = $_GET['newName'];
  		        if (renameFile($oldName, $newName)) {
			        renameFileRecord($oldName, $newName);
		        }
        }
    }

    // Write folder naming form is create folder is set
    if (isset($_GET['newFolder'])) {
        writeNewFolderForm();
    }

    // Create new folder if create button is set
    if (isset($_GET['newFolder'])) {
        if ($_GET['newFolder'] == 'Create') {
            if (newFolder($_GET['folderName'])) {
                newFolderRecord($_GET['folderName'], $currentLocation);
            }
        }
    }

	//download file if download button is clicked
	if(isset($_GET['download'])){
		if($_GET['selectedFile']){
			if($_GET['selectedFile'] !== ''){
				//$fname = $_GET['filename'];
				$fname = $selectedFile;
				//$flocation = NULL;	
				downloadFile($fname);
			}
		}
	}

    // If the 'move to...' button is clicked AND a file is selected
    if(isset($_GET['moveTo']) && $fileFlag) {
        writeFolders(null, $selectedFile); // Passing 'null' into the function for now because no user stuff has been implemented.
    }

    if (isset($_GET['selectFolderButton'])) {
        if ($_GET['selectFolderButton'] == 'Move') { // if the Move button was clicked (as opposed to 'Cancel')
            if (moveFile($selectedFile, $currentLocation, $_GET['folderMenu'])) {
                renameFileLocationRecord($selectedFile, $currentLocation, $_GET['folderMenu']);
            } 
        }
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<table width="100%" border="0" style="float: centre;">
  <tr>
    <td width="929"><strong><font size="+1">TEAM 12 MEDIA VAULT</font></strong></td>
    <td width="182">email@address.com</td>
    <td width="119"><a href="index.php">Log out</a></td>
  </tr>
</table>
<hr>
<table width="100%" border="0" style="float: centre;">
  <tr>
    <td width="1097">[NAV BAR PLACEHOLDER]</td>
    <td width="144"><div align="center"><strong><a href="upload.php">Upload</a></strong></div></td>
    <td width="177">View: List, Grid</td>
    <td width="233">
  <input type="search" name="searchBar" value="" /><input type="button" name="searchButton" value="Search" /></td>
  </tr>
</table>
<table class="directoryTable" width="75%" border="1" style="float: left;">
  <tr>
    <td width="25%">Name</td>
    <td width="25%">Type</td>
    <td width="25%">Last modified</td>
    <td width="25%">Size</td>
  </tr>
  
    <!--File Selection Form -->
    <form action="directory.php" method="post">
	    <?php
		    // Get metadata table info
		    $metadata = readTable('SELECT * FROM metadata WHERE location = "' . $currentLocation . '"');
		    // Define desired columns
		    $columns = array('filename', 'filetype', 'timestamp', 'filesize');
		    // Write to HTML table
		    writeTable($metadata, $columns);
	    ?>
    </form>

</table>
<div class="fileInfoDiv">
    <!--File Information Table -->
    <table class="fileInfoTable" border="1">
      <tr>
        <td id="fileNameCell" colspan="2">
        <?php
    	    if (isset($selectedFile)) {
    		    echo $selectedFile;
    	    } else {
    		    echo "No file selected";
    	    }
        ?> 
        </td>
      </tr>
      <tr>
        <td id="descriptionTagCell" colspan="2"><strong>Description:</strong></td>
      </tr>
      <tr>
        <td id="descriptionCell" colspan="2">[PLACEHOLDER DESC]</td>
      </tr>
      <tr>
        <td id="colourTagCell" colspan="2"><strong>Colour tag:</strong></td>
      </tr>
      <tr>
        <td id="tagCell" colspan="2">[PLACEHOLDER TAG]</td>
      </tr>
    
        <!--File Management Form -->
        <form action="directory.php" method="get">
            <input type="hidden" value="<?php if (isset($selectedFile)) { echo $selectedFile; } ?>" name="selectedFile">
            <tr id="fileManButtons">
                <td><div id="fileManDiv"><input type="submit" value="Download" name="download" id="fileManButton"></div></td>
                <td><div id="fileManDiv"><input type="submit" value="Edit" name="edit" id="fileManButton"></div></td>
            </tr>
            <tr id="fileManButtons">
                <td><div id="fileManDiv"><input type="submit" value="Share" name="share" id="fileManButton"></div></td>
                <td><div id="fileManDiv"><input type="submit" value="Delete" name="delete" id="fileManButton"></div></td>
            </tr>
            <tr id="fileManButtons">
                <td><div id="fileManDiv"><input type="submit" value="New Folder" name="newFolder" id="fileManButton"></div></td>
                <td><div id="fileManDiv"><input type="submit" value="Move To..." name="moveTo" id="fileManButton"></div></td>
            </tr>
        </form>
    </table>
</div>

<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>