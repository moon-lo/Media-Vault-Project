<li class="list-group-item">Colour tag:<br>
	<button type="submit" name="colour" value="red"><img src="images/red.png"></button> 
	<button type="submit" name="colour" value="aqua"><img src="images/aqua.png"></button> 
	<button type="submit" name="colour" value="lime"><img src="images/lime.png"></button> 
	<button type="submit" name="colour" value="yellow"><img src="images/yellow.png"></button> 
	<button type="submit" name="colour" value="pink"><img src="images/pink.png"></button> 
	<button type="submit" name="colour" value=NULL><img src="images/none.png"></button> 
</li>
					
<th id="colourHead" onclick="orderTable(4, true)">Colour</th>

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
                    writeSearchResults($metadata, $columns, $accountName);
                }
	        ?>
        </form>
		
		
<?php
function changeFileColour($filename, $colour, $user) {
	$sql = "UPDATE metadata SET colour = :newColour WHERE filename = :file AND owner = :user";
    $parameters = array(
        ':newColour' => $colour,
        ':file' => $filename,
        ':user' => $user
    );
    alterDB($sql, $parameters);
}


function writeTable($pdo, $columns, $selectedFile, $isFolder, $currentDir, $username, $searchStr) {
    if ($pdo == null) {
        echo "<tr id='listingRow'><td>No files to display</td></tr>";
    } else {
        foreach ($pdo as $row) {
			echo '<tr class="listingRow">';
		    foreach ($columns as $column) {	
                $colourStyle = '';
                if ($column == 'filename') {
                    $sortKey = strtolower(substr($row[$column], 0, 1));
                }
                if ($column == 'filetype') {
                    $sortKey = substr($row[$column], 0, 1);
                    $row[$column] = selectIcon($row[$column]);
                }
                if ($column == 'timestamp') {
                    $sortKey = $row[$column];
                    $row[$column] = date("g:i a - d.m.y", strtotime($row[$column]));
                }
                if ($column == 'filesize') {
                    $sortKey = $row[$column];
                    $row[$column] = round($row[$column] / 1024);
                    $row[$column] = $row[$column] . " KB";
                }
                if ($column == 'colour') {
                    $sortKey = strtolower(substr($row[$column], 0, 1));
                    if ($row[$column] != null && $row[$column] != '' && $row[$column] != 'NULL') {
                        $colourStyle = " style='background-color:$row[$column]' ";
                    } 
                    $row[$column] = '';
                }

                if ($row['filename'] == $selectedFile && $isFolder) {
                    echo '<td sortKey="' . $sortKey . '" ' . $colourStyle . ' class="selectedFile"><a class="dirHref" href="directory.php?currentDir=' . $currentDir . $row['filename'] . '/">' . $row[$column] . '</a></td>';
                } else if ($row['filename'] == $selectedFile && !$isFolder) {
				    echo '<td sortKey="' . $sortKey . '" ' . $colourStyle . ' class="selectedFile"><a class="dirHref" href="directory.php?currentDir=' . $currentDir . '&selectedFile=' . $row['filename'] . '">' . $row[$column] . '</a></td>';    
                } else {
                    echo '<td sortKey="' . $sortKey . '" ' . $colourStyle . '><a class="dirHref" href="directory.php?currentDir=' . $currentDir . '&selectedFile=' . $row['filename'] . '">' . $row[$column] . '</a></td>';
                }
		    }
		    echo "</tr>";
        }
    }
}

function writeSearchResults($pdo, $columns, $username) {
    if ($pdo == null) {
        echo "<tr id='listingRow'><td>No results</td></tr>";
    } else {
        foreach ($pdo as $row) {
            $dir = $row['location'];
           	$colour = $row['colour'];
			$colourStyle = '';
			if ($colour != null && $colour != '' && $colour != 'none'){
				$colourStyle = "style='background-color:$colour'";
			}
            echo '<tr class="listingRow">';
		    foreach ($columns as $column) {
                $colourStyle = '';
                if ($column == 'filename') {
                    $sortKey = strtolower(substr($row[$column], 0, 1));
                }
                if ($column == 'filetype') {
                    $sortKey = substr($row[$column], 0, 1);
                    $row[$column] = selectIcon($row[$column]);
                }
                if ($column == 'timestamp') {
                    $sortKey = $row[$column];
                    $row[$column] = date("g:i a - d.m.y", strtotime($row[$column]));
                }
                if ($column == 'filesize') {
                    $sortKey = $row[$column];
                    $row[$column] = round($row[$column] / 1024);
                    $row[$column] = $row[$column] . " KB";
                }
                if ($column == 'colour') {
                    $sortKey = strtolower(substr($row[$column], 0, 1));
                    if ($row[$column] != null && $row[$column] != '' && $row[$column] != 'NULL') {
                        $colourStyle = " style='background-color:$row[$column]' ";
                    } 
                    $row[$column] = '';
                }

                if ($column == 'location') {
                    $sortKey = count(explode("/", $row[$column]));
                    $row[$column] = 'Home/' . substr($row[$column], strlen('uploads/' . $username . '/'), strlen($row[$column]));
                }
                echo '<td sortKey="' . $sortKey . '" ' . $colourStyle . '><a class="dirHref" href="directory.php?currentDir=' . $dir . '&selectedFile=' . $row['filename'] . '">' . $row[$column] . '</a></td>';
            }
		}
	    echo "</tr>";
    }
}



    // Colour
	if (isset($_GET['colour'])) {
		changeFileColour($_GET['selectedFile'], $_GET['colour'], $accountName);
	}

?>

