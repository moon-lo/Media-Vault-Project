<?php
	/* 1. Files that were colour-tagged would not appear coloured if returned by a search. */
	// Added write search results function:
		$colour = $row['colour'];
		$colourStyle = '';
		if ($colour != null && $colour != '' && $colour != 'none'){
			$colourStyle = " style='background-color:$colour' ";
		}
		echo '<td sortKey="' . $sortKey . '"><a ' . $colourStyle . 'href="directory.php?currentDir=' . $dir . '&selectedFile=' . $row['filename'] . '">' . $row[$column] . '</a></td>';
	
	//2. Files of the same name would have their name, description and colour changed together.
	// Added owner matching (WHERE owner = :user) for the respective SQL queries

	/* 3. New users were not having their own folder created */
	// Added to user registration function:
		mkdir(dirname(__FILE__) . '/uploads/' . $username);
	
	/* 4. Searching would return files owned by different users. */
	// Pre SQL function:
		$metadata = queryDB('SELECT * FROM metadata 
			WHERE owner = "' . $accountName . '" 
			AND filename LIKE "%' . $searchStr . '%" 
			OR description LIKE "%' . $searchStr . '%" 
			OR filetype LIKE "' . $searchStr . '"');
	// Post SQL query:
		$metadata = queryDB('SELECT * FROM metadata 
			WHERE owner = "' . $accountName . '" AND filename LIKE "%' . $searchStr . '" 
			OR owner = "' . $accountName . '" AND description LIKE "' . $searchStr . '%" 
			OR owner = "' . $accountName . '" AND filetype LIKE "%' . $searchStr . '%"');
			
?>