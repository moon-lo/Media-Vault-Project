<?php
// Artefact 1 - PHP Component
// Added to the writeTable function:

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
	
?>
<script>
// Artefact 1 - JavaScript Component
/** Directory Sorting **/

// Tracks the column that is currently sorted.
var currentSort = null;

/**
 * Order a particular table's rows based on the column header that is selected.
 *
 * @param int - headerIndex - the index value of the column to be sorted.
 *
 * @author James Galloway
 */
function orderTable(headerIndex) {
    var rows = $('#directoryTable tbody tr').get();

    if (currentSort !== headerIndex) {
        currentSort = headerIndex;
        ascending = true;
    } else if (currentSort == headerIndex && ascending == false) {
        ascending = true;
    } else {
        ascending = false;
    }

    rows.sort(function (a, b) {
        var A = $(a).children('td').eq(headerIndex).attr('sortKey');
        var B = $(b).children('td').eq(headerIndex).attr('sortKey');
        if (A < B) {
            if (ascending) {
                return -1;
            }
            return 1;
        }
        if (A > B) {
            if (ascending) {
                return 1;
            }
            return -1;
        }
        return 0;
    });
    $.each(rows, function (index, row) {
        $('#directoryTable').children('tbody').append(row);

    });

    addSymbol('directoryTable', headerIndex, ascending);
} // end orderTable
</script>