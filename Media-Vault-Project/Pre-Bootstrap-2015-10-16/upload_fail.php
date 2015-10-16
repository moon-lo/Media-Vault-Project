<?php
  $pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	session_start();
	
	if (!isset($_SESSION['isUser']))
	{
		header("Location: http://{$_SERVER['HTTP_HOST']}/Media-Vault-Project/Media-Vault-Project/logout.php");
		exit();
	}
	
	$accountName = $_SESSION['isUser'];
?>
<!-- Author: Lok Sum (Moon) Lo -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload file</title>
</head>

<body>
<table width="100%" border="0" style="float: centre;">
  <tr>
    <td width="929"><strong><font size="+1">TEAM 12 MEDIA VAULT</font></strong></td>
    <td width="182"><?php echo $accountName ?></td>
    <td width="119"><a href="index.html">Log out</a></td>
  </tr>
</table>
<hr>
<table width="100%" height="510" border="0" style="float: centre;">
  <tr>
    <td width="11%" height="50">&nbsp;</td>
    <td width="65%">&nbsp;</td>
    <td width="24%">&nbsp;</td>
  </tr>
  <tr>
    <td height="235">&nbsp;</td>
    <td><p><font size="+2">File upload failed.</font></p>
      <p>Please ensure that  file size is under 3MB. </p>
      <p><font size="+1"><a href="upload.php">Try Again</a></font></p>
      <p><font size="+1"><a href="directory.php">Back</a></font></p></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
