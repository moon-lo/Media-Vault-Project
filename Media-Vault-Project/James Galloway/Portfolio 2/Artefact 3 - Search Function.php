<!--File Selection Form -->
<form action="directory.php" method="post">
	<?php
		if (!$searchStr) {
			// Get metadata table info
			$metadata = queryDB('SELECT * FROM metadata WHERE location = "' . $currentDir . '" AND owner = "' . $accountName . '"');
			// Define desired columns
			$columns = array('filename', 'filetype', 'timestamp', 'filesize', 'colour');
			// Write to HTML table
			writeTable($metadata, $columns, $selectedFile, $isFolder, $currentDir, $accountName, $searchStr);
		} else {
			$metadata = queryDB('SELECT * FROM metadata 
						WHERE owner = "' . $accountName . '" AND filename LIKE "%' . $searchStr . '%" 
						OR owner = "' . $accountName . '" AND description LIKE "' . $searchStr . '%" 
						OR owner = "' . $accountName . '" AND filetype LIKE "' . $searchStr . '"');
			
			$columns = array('filename', 'filetype', 'timestamp', 'filesize', 'colour', 'location');
			writeTable($metadata, $columns, $accountName);
		}
	?>
</form>