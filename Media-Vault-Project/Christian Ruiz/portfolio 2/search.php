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