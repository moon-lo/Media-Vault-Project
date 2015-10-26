<?php

	/**
	 * Writes the entire contents of a given PDO to rows of a table.
	 *
	 * @param (PDO) - $pdo - the PDO to be written to the table.
	 * @param (array) - $columns - the String values of the PDO's columns to be written.
	 * * NOTE: All cells are hyperlinked individually
     *         Date string is reformatted
     *         Size string is reformatted
	 *
	 * @author James Galloway
	 */
function writeTable($pdo, $columns, $selectedFile, $isFolder, $currentDir) {
	if ($pdo == null) {
        echo "<tr id='listingRow'><td>No files to display</td></tr>";
    } else {
        foreach ($pdo as $row) {
		    echo "<tr  id='listingRow'>";
		    foreach ($columns as $column) {
                    if ($column == 'timestamp') {
                        $row[$column] = date("g:i a - d.m.y", strtotime($row[$column]));
                    }
                    if ($column == 'filesize') {
                        $row[$column] = round($row[$column] / 1024);
                        $row[$column] = $row[$column] . " KB";
                    }
                    if ($row['filename'] == $selectedFile && $isFolder) {
                        echo '<td class="selectedFile"><a href="directory.php?currentDir=' . $currentDir . $row['filename'] . '/">' . $row[$column] . '</a></td>';
                    } else if ($row['filename'] == $selectedFile && !$isFolder) {
				        echo '<td class="selectedFile"><a href="directory.php?currentDir=' . $currentDir . '&selectedFile=' . $row['filename'] . '">' . $row[$column] . '</a></td>';    
                    } else {
                        echo '<td><a href="directory.php?currentDir=' . $currentDir . '&selectedFile=' . $row['filename'] . '">' . $row[$column] . '</a></td>';
                    }
		    }
		    echo "</tr>";
	    }
    }
} // end writeTable


?>
<script>
/**
 * Disable file management buttons if no file has been selected.
 *
 * @param setDisabled - Boolean - true if no file has been selected, therefore disable.
 *
 * @author James Galloway
 */
function setFileManButtons(setDisabled) {
    var inputs = document.getElementById("fileManForm").elements;

    for (var i = 0, element; element = inputs[i++];) {
        if (element !== document.getElementById("newFolderButton") &&
            element !== document.getElementById("selectedFileHidden") &&
            element !== document.getElementById("currentDirHidden"))
        {

            element.disabled = setDisabled;

            if (setDisabled) {
                element.className += "inactive";
            } else {
                element.className += "active";
            }
        }
    }
} // end setFileManButtons
</script>