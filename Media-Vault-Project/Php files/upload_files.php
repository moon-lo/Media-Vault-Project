<?php
	/**
	 * Preliminary upload function.  Just want to see how this is gonna work.
	 *
	 * @author James Galloway
	 */ 
	
	$dir = "/var/www/html/uploads/";
	$file = $dir . basename($_FILES["filename"]["name"]);
	$filetype = pathinfo($file, PATHINFO_EXTENSION);
	$uploadOk = 1;
	
	// Check if file already exists
	if (file_exists($file)) {
		echo "<br>File already exists.";
		$uploadOk = 0;
	}
	
	// Upload file
	if ($uploadOk == 0) {
		echo "<br>File could not uploaded.";
	} else {
		if (move_uploaded_file($_FILES["filename"]["tmp_name"], $file)) {
			echo "<br>File: " . basename($_FILES["filename"]["name"]) . " was successfully uploaded.";
		} else {
			echo "<br>There was an error in uploading the file.";
			print_r(error_get_last());
		}
	}
	echo "<br><a href='test.php'> Back to test page</a>";
?>