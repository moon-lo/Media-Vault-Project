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
	 * These strings are also written as column headers for the table.
	 *
	 * @author James Galloway
	 */
function write_table($pdo, $columns) {
	foreach ($pdo as $row) {
		echo "<tr>";
		foreach ($columns as $column) {
			echo "<td>" . $row[$column] . "</td>";
		}
		echo "</tr>";
	}
} // end write_table

?>