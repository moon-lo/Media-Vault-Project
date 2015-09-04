<?php
	/**
	 * Delete selected file.
	 *
	 * @param $file - string - the file to be deleted.
	 *
	 * @author Christian Ruiz
	 */
function deleteFile($file) {
	if (unlink(ROOT_DIR . '/uploads/' . $file)) {
		echo "<p>File successfully deleted</p>";
		return true;
	}
	return false;
} // end deleteFile

	/**
	 * Delete a file's associated record in the metadata table.
	 *
	 * @param $file - string - the file name of the record to be deleted.
	 *
	 * @author James Galloway
	 */
function deleteFileRecord($file) {	
	$sql = "DELETE FROM metadata WHERE filename = :filename";
	
	$pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$result = $pdo->query('SELECT * FROM metadata');
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':filename', $file);
	$stmt->execute();

	$pdo = null;
} // end deleteFileRecord
	
?>