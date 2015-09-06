<?php 

	/**
	 * Establishes a connection with a database and executes the specified query.
	 *
	 * @param (String) - $sql - the MySQL query to be executed.
	 * @returns (PDO) - $result - the PDO result of the query.
	 * 
	 * @author James Galloway
	 */
function readTable($sql) {
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$result = $pdo->query($sql);
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$pdo = null;
	return $result;
} // end read_table

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
function writeTable($pdo, $columns) {
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
				echo "<td><a href='directory.php?selectedFile=" . $row['filename'] . "'>" . $row[$column] . "</a></td>";
		}
		echo "</tr>";
	}
} // end write_table

?>