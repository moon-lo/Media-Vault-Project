<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<table width="100%" border="0" style="float: centre;">
  <tr>
    <td width="929"><strong><font size="+1">TEAM 12 MEDIA VAULT</font></strong></td>
    <td width="182">email@address.com</td>
    <td width="119"><a href="index.html">Log out</a></td>
  </tr>
</table>
<hr>
<table width="100%" border="0" style="float: centre;">
  <tr>
    <td width="818">[NAV BAR PLACEHOLDER]</td>
    <td width="107"><div align="center"><strong><a href="upload.php">Upload</a></strong></div></td>
    <td width="305">View: List, Grid</td>
  </tr>
</table>
<table width="75%" border="1" style="float: left;">
  <tr>
    <td width="50%">Name</td>
    <td width="25%">Last modified</td>
    <td width="25%">Size</td>
  </tr>
	
	<?php
		require_once 'sql_functions.php';
		
		// Get metadata table info
		$metadata = read_table("SELECT * FROM metadata;");
		// Define desired columns
		$columns = array('filename', 'timestamp', 'filesize');
		// Write to HTML table
		write_table($metadata, $columns);		
	?>
	
</table>
<table width="25%" height="100%" border="1" style="float: right;">
  <tr>
    <td height="47" colspan="2">[PLACEHOLDER FILE NAME]</td>
  </tr>
  <tr>
    <td height="38" colspan="2"><strong>Description:</strong></td>
  </tr>
  <tr>
    <td height="209" colspan="2">[PLACEHOLDER DESC]</td>
  </tr>
  <tr>
    <td colspan="2"><strong>Colour tag:</strong></td>
  </tr>
  <tr>
    <td colspan="2">[PLACEHOLDER TAG]</td>
  </tr>
  <tr>
    <td width="133"><div align="center"><button name="downloadButton" type="button" onclick="alert('The file is downloading.')">Download</button></div></td>
    <td width="132"><div align="center"><button name="editButton" type="button">Edit</button></div></td>
  </tr>
  <tr>
    <td><div align="center"><button name="shareButton" type="button">Share</button></div></td>
    <td><div align="center"><button name="deleteButton" type="button">Delete</button></div></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>