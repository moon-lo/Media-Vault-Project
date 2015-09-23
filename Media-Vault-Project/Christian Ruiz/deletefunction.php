/** Delete Related Functions **/
    
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
}