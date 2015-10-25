<?php
	//Function that updates the description of a file.
	function changeDescription($filename, $description, $user) {
		$sql = "UPDATE metadata SET description = :newDescription WHERE filename = :file AND owner = :user";
		$parameters = array(
			':newDescription' => $description,
			':file' => $filename,
			':user' => $user
			//':editor' => $editor
		);
		alterDB($sql, $parameters);
	}

	//Change description when description form is used.
	if (isSetAndNotEmpty($_GET, 'newDescription')) {
        $newDes = $_GET['newDescription'];
        $fileName = $_GET['selectedFile'];
        //$editor = $_SESSION['isUser'];
        changeDescription($fileName, $newDes, $accountName);
    }
	
	//Display description in directory.
	$description = queryDB('SELECT description FROM metadata WHERE filename = "' . $selectedFile . '" AND owner = "' . $accountName . '"');
	
	if ($description == NULL || $description[0]['description'] == NULL) {
		echo "No description avaliable.";
	} 
	else { 
		foreach ($description as $item)
		{
			echo $item['description'];
		}
	}
?>