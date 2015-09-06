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
	 * * NOTE: If 'filename' is provided as a column, that cell will be hyperlinked
	 *
	 * @author James Galloway
	 */
function writeTable($pdo, $columns) {
	foreach ($pdo as $row) {
		echo "<tr  id='listingRow'>";
		foreach ($columns as $column) {
				echo "<td><a href='directory.php?selectedFile=" . $row['filename'] . "'>" . $row[$column] . "</a></td>";
		}
		echo "</tr>";
	}
} // end write_table

?>