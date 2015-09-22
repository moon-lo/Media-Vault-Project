<?php
    // Define root directory for use in strings later
	define('ROOT_DIR', dirname(__FILE__));
    include ROOT_DIR . '/php-files/file_management.php';
    include ROOT_DIR . '/php-files/sql_functions.php';
    
    $pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    session_start();
    
    // Check to see if file is set - Attempt to upload file - Add record upon success
	if (isset($_FILES['file'])) {
		if (uploadFile()) {
			addUploadRecord();
		}
	}
?>

<!-- Author: Lok Sum (Moon) Lo -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload file</title>
</head>

<body>
<table class="topBar" width="100%" border="0" style="float: centre;">
  <tr>
    <td width="929"><strong><font size="+1">TEAM 12 MEDIA VAULT</font></strong></td>
    <td width="182">email@address.com</td>
    <td width="119"><a href="index.php">Log out</a></td>
  </tr>
</table>
<hr>
<table width="100%" height="510" border="0" style="float: centre;">
  <tr>
    <td width="11%" height="50">&nbsp;</td>
    <td width="65%"><font size="+2">Upload file</font></td>
    <td width="24%">&nbsp;</td>
  </tr>
  <tr>
    <td height="235">&nbsp;</td>
    <td><form action="upload.php" method="post" enctype="multipart/form-data">
    Select a file to upload:
    <input type="file" name="file" id="file">
    <input type="submit" value="Upload File" name="submit"></form>
    <p>Note: File size must be less than 3MB.</p>      </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><a href="directory.php">Directory</a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
