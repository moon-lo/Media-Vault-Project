* Moves file from current position to destination folder.
    *
    * @param $file - string - file to be moved.
    * @param $location - string - the location of the file to be moved.
    * @param $folder - string - destination folder.
    *
    * @author Christian Ruiz & James Galloway
    */
function moveFile($file, $folder) {
    $sql = "SELECT * FROM metadata WHERE filename = '$file'";
    $tempPath = queryDB($sql);
    $filePath = ROOT_DIR . '/' . $tempPath[0]['location'];
    
    $sql = "SELECT location FROM metadata WHERE filename = '$folder'";
    $tempPath = queryDB($sql);
    if ($tempPath == null) {
        $DBentry = 'uploads/';
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
}