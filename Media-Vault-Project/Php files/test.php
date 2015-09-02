<!DOCTYPE html>
<html>
	<body>
		<h1>Test Page</h1>
		<hr>
			<p><b>Test Upload</b></p>
			<form action="upload_files.php" method="POST" enctype="multipart/form-data">
				<p>Select file</p>
				<input type="file" name="filename" id="filename"><br><br>
				<input type="submit" value="Upload" name="submit">
			</form>
		<hr>
			<p><b>Test Directory</b></p>
			<table>
				<?php 
					include 'directory_functions.php';
					list_dir();
				?>
			</table>
		<hr>
	</body>
</html>