<?php
    
    include ROOT_DIR . '/php-files/file_management.php';
    include ROOT_DIR . '/php-files/sql_functions.php';
	include ROOT_DIR . '/php-files/download_functions.php';

    $isSelected = false;
    $isFolder = false;
    $selectedFile = null;
    $selectedFolder = null;

    if (isSetAndNotEmpty($_GET, 'currentDir')) {
        $currentDir = $_GET['currentDir'];
    } else {
        $currentDir = 'uploads/';
    }

    // Process the file's DB record - set isFolder flag if file is folder
    if (isSetAndNotEmpty($_GET, 'selectedFile')) {
        $query = 'SELECT * FROM metadata WHERE filename = "' . $_GET['selectedFile'] . '"';
        $fileRecord = queryDB($query);
        if ($fileRecord == null) {
            return;   
        }
        $selectedFile = $fileRecord[0]['filename'];
        $fileID = $fileRecord[0]['fileid'];
        $fileType = $fileRecord[0]['filetype'];
        
        if ($fileType == 'folder') {
            $isFolder = true;
        }
        $isSelected = true;
    }

    // Process the folder's DB record if folder has been clicked twice - set location to inside folder
    if (isSetAndNotEmpty($_GET, 'selectedFolder')) {
        $query = 'SELECT * FROM metadata WHERE filename = "' . $_GET['selectedFolder'] . '"';
        $folderRecord = queryDB($query);
    }

    // DELETE
    // Delete file if delete & file are set
    if (isset($_GET['delete'])) {
        if (deleteFile($selectedFile, $currentDir)) {
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
        writeNewFolderForm($currentDir);
    }

    // Create new folder if create button is set
    if (isset($_GET['newFolderForm'])) {
        if ($_GET['newFolderForm'] == 'Create') {
            if (newFolder($_GET['folderName'], $currentDir)) {
                newFolderRecord($_GET['folderName'], $currentDir);
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
            if ($folderPath = moveFile($selectedFile, $_GET['folderMenu'])) {
                renameFileLocationRecord($selectedFile, $folderPath);
            }
        }
    }

    echo $currentDir;

?>
