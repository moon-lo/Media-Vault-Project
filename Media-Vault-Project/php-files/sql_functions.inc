<?php 

	/**
	 * Establishes a connection with a database and executes the specified query.
	 *
	 * @param (String) - $sql - the MySQL query to be executed.
	 * @returns (PDO) - $result - the PDO result of the query.
	 * 
	 * @author James Galloway
	 */
function read_table($sql) {
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
function write_table($pdo, $columns) {
	foreach ($pdo as $row) {
		echo "<tr>";
		foreach ($columns as $column) {
			if ($column == 'filename') {
				echo "<td><a href='directory.php?email=blank&selectedFile="
					. $row[$column] . "'>" . $row[$column] . "</a></td>"; 
			} else {
				echo "<td>" . $row[$column] . "</td>";
			}
		}
		echo "</tr>";
	}
} // end write_table

?>