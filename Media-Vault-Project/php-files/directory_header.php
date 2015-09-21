<?php
    
    include ROOT_DIR . '/php-files/file_management.php';
    include ROOT_DIR . '/php-files/sql_functions.php';
	include ROOT_DIR . '/php-files/download_functions.php';

    //Determine the username of the account
    if (isset($_POST['Login']))
    {
    	$accountName = $_POST['username'];
    }

    $isSelected = false;
    $isFolder = false;
    $selectedFile = null;
    $selectedFolder = null;
    $currentLocation = 'uploads/';

    // Process the file's DB record - set isFolder flag if file is folder
    if (isset($_GET['selectedFile']) && $_GET['selectedFile'] !== '') {
        $query = 'SELECT * FROM metadata WHERE filename = "' . $_GET['selectedFile'] . '"';
        $fileRecord = queryDB($query);
        if ($fileRecord == null) {
            return;   
        }

        $selectedFile = $fileRecord[0]['filename'];
        $fileID = $fileRecord[0]['fileid'];
        $fileType = $fileRecord[0]['filetype'];
        $currentLocation = $fileRecord[0]['location'];
        
        if ($fileType == 'folder') {
            $isFolder = true;
        }
        $isSelected = true;
    }

    // Process the folder's DB record if folder has been clicked twice - set location to inside folder
    if (isset($_GET['selectedFolder']) && $_GET['selectedFolder'] !== '') {
        $query = 'SELECT * FROM metadata WHERE filename = "' . $_GET['selectedFolder'] . '"';
        $folderRecord = queryDB($query);
        $currentLocation = $folderRecord[0]['location'] . $folderRecord[0]['filename'] . '/';
    }

    echo $currentLocation;

    // DELETE
    // Delete file if delete & file are set
    if (isset($_GET['delete'])) {
        if (deleteFile($selectedFile, $currentLocation)) {
            deleteFileRecord($selectedFile);
        }
    }

    // RENAME
    // Write rename form is edit & file are set
    if (isset($_GET['edit'])) {
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

    // NEW FOLDER
    // Write folder naming form is create folder is set
    if (isset($_GET['newFolder'])) {
        writeNewFolderForm();
    }

    // Create new folder if create button is set
    if (isset($_GET['newFolderForm'])) {
        if ($_GET['newFolderForm'] == 'Create') {
            if (newFolder($_GET['folderName'])) {
                newFolderRecord($_GET['folderName'], $currentLocation);
            }
        }
    }

    // DOWNLOAD
    // Download file if download button is clicked
    if(isset($_GET['download'])){
        //$fname = $_GET['filename'];
        $fname = $selectedFile;
        //$flocation = NULL;	
        downloadFile($fname);
    }

    // MOVE FILE
    // If the 'move to...' button is clicked AND a file is selected
    if(isset($_GET['moveTo']) && $isSelected) {
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