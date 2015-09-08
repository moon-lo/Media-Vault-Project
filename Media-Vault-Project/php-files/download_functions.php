<?php
/** Download Related Functions **/

/**
* 
* Checks the metadata table using unique fileid and compares current session user id
* with the file ownership.
*
* @param (int) - $fileid - the fileid of the file which a user wishes to download.
* @param (int) - $id - id of current session user (or NULL).
* @returns (bool) - true or false after comparing whether id matches the id found in metadata table.
* 
* @author Benjamin McCloskey
**/
	 
function ownFile($fileid, $id = NULL){
	if(is_null(id)){
		return true;
	}else{
		$sql = "SELECT id FROM metadata WHERE fileid :fileid"
		
		$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$result = $pdo->query('SELECT * FROM metadata');
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':fileid', $fileid);
		$idresult = $pdo->query($stmt);
		$foundId = mysql_fetch_assoc($idresult);
		
		$pdo = null;
		
		if($id = $foundId){
			return true;
		}else{
			return false;
		}
		
	}
}

/**
* 
* downloadFile is used to dynamically assess the type of file given it's name and location
* and then make the file available for download.
*
* TODO: catch errors.
*
* @param (string) - $filename - the name of the file to be downloaded.
* @param (string) - $location - the location or path of the file (or NULL).
* @returns (bool) - true or false after comparing whether id matches the id found in metadata table.
* 
* @author Benjamin McCloskey
**/

function downloadFile($filename, $location = NULL) {
	//if default location set to uploads folder.
	if(is_null($location)){
		$location = "/uploads/";
	}
	
	//file info to get MIME_TYPE for setting Content-Type header.
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mType = finfo_file($finfo,ROOT_DIR.$location.$filename);
	
	//download file
	header('Content-Type: '.$mType);
	header('Content-Disposition: attachment; filename="'.$filename$.'"');
	readfile($location.$filename);
	exit;
	
}

if(isset($_POST['filename'])){
    
    $fname = $_POST['filename'];
    $flocation = NULL;
    //do the action
    downloadFile($fname, $flocation);
}

?>


<form action="download_functions.php" method="post" name = "downloadform">  
    <input name="filename" value="test.txt" type = "hidden">
    <input name="location" value="" >   
    <input type="submit" name="Download" > 
</form>