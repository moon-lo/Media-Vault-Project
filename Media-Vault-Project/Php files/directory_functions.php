<?php
	/**
	 * Writes files in directory (/var/www/html/uploads) to a table.
	 *
	 * @author James Galloway
	 */
function list_dir() {
	$dir = "/var/www/html/uploads";
	$list = scandir($dir);
	
	// Remove '.' & '..' from array (dots representing directories)
	$list = array_diff($list, array('.', '..'));
	
	echo "<table>";
		foreach ($list as $item) {
			echo "<tr><td>" . $item . "</td></tr>";
		}
	echo "</table>";
} // end list_dir
?>