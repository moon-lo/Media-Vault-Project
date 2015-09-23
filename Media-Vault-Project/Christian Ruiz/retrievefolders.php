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
    echo '          <option value="uploads">Uploads</option>";
                </select>
               <input type="submit" name="selectFolderButton" value="Move">
               <input type="submit" name="selectFolderButton" value="Cancel">
            </form>
         </div>';
}