<?php
    include ROOT_DIR . '/php-files/file_management.php';
    include ROOT_DIR . '/php-files/sql_functions.php';
	include ROOT_DIR . '/php-files/download_functions.php';
    
    $isSelected = false;
    $isFolder = false;
    $selectedFile = null;
    $selectedFolder = null;
    $searchStr = false;

    if (isSetAndNotEmpty($_GET, 'currentDir')) {
        $currentDir = $_GET['currentDir'];
    } else {
        $currentDir = 'uploads/' . $accountName . '/';
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
        writeDeleteConfirmation($selectedFile, $currentDir);
    }
    // Delete file if confirmed by the user.
    if (isset($_GET['confirmDelete'])) {
        if ($_GET['confirmDelete'] == 'Yes') {    
            if (deleteFile($_GET['selectedFile'], $_GET['currentDir'])) {
                    deleteFileRecord($selectedFile);
            }
        }
    }
	
	if (isset($_GET['colour_select'])) {
		changeFileColour($_GET['selectedFile'], $_GET['colour'], $accountName);
	}

    // EDIT
    if (isset($_GET['edit'])) {
        writeEditForm($selectedFile, $currentDir);
    }
    // Edit file name & description if 'confirm' is set
    if (isset($_GET['confirmEdit'])) {
        if (isSetAndNotEmpty($_GET, 'newDescription')) {
            $newDes = $_GET['newDescription'];
            $fileName = $_GET['fileName'];
            //$editor = $_SESSION['isUser'];
            changeDescription($fileName, $newDes, $accountName);
        }
        if (isSetAndNotEmpty($_GET, 'newName')) {
            $oldName = $_GET['fileName'];
            $newName = $_GET['newName'];
            if (renameFile($oldName, $newName, $currentDir, $accountName)) {
                renameFileRecord($oldName, $newName, $accountName);
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
                newFolderRecord($_GET['folderName'], $currentDir, $accountName);
            }
        }
    }
    // DOWNLOAD
    // Download file if download button is clicked
    if(isset($_GET['download'])){
        //$fname = $_GET['filename'];
        $fname = $selectedFile;
        //$flocation = NULL;	
        downloadFile($fname, $currentDir);
    }
	
	// SHARE
	// Add file to downloads table and provide link to share
	if(isset($_GET['share'])){
		$link = prepareFileToShare($selectedFile, $currentDir);
		echo 'your single use link is: '.$link;
	}
	
    // MOVE FILE
    // If the 'move to...' button is clicked AND a file is selected
    if(isset($_GET['moveTo']) && $isSelected) {
        writeFolders($accountName, $selectedFile);
    }
    if (isset($_GET['selectFolderButton'])) {
        if ($_GET['selectFolderButton'] == 'Move') { // if the Move button was clicked (as opposed to 'Cancel')
            if ($folderPath = moveFile($selectedFile, $_GET['folderMenu'], $accountName)) {
                renameFileLocationRecord($selectedFile, $folderPath);
            }
        }
    }

	if (isSetAndNotEmpty($_GET, 'searchStr')) {
		$searchStr = $_GET['searchStr'];
	}

    echo $currentDir;
?>