<?php
    // Define root directory for use in strings later
	define('ROOT_DIR', dirname(__FILE__));
    $pdo = new PDO('mysql:host=localhost;dbname=mediavault', 'root', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    session_start();
    //Determine if user is signed in. If not redirect them to a seperate page.
    if (!isset($_SESSION['isUser']))
	{
		header("Location: http://{$_SERVER['HTTP_HOST']}/Media-Vault-Project/Media-Vault-Project/logout.php");
		exit();
	}
        
    //Determine the username of the account
    $accountName = $_SESSION['isUser'];
    include ROOT_DIR . '/php-files/directory_header.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="javascript/display_functions.js"></script>
    <script type="text/javascript" src="javascript/jquery-1.11.3.js"></script>
</head>

<body>
<table class="topBar" style="float: centre;">
  <tr>
    <td width="707"><p>&nbsp;</p>
    <p><strong><font size="+1">TEAM 12 MEDIA VAULT</font></strong></p></td>
    <td width="86"><p>&nbsp;</p>      <?php echo $accountName ?></td>
    <td width="66"><p>&nbsp;</p>
    <p><a href="logout.php">Log out</a></p></td>
  </tr>
</table>
<hr>
<table width="100%" border="0" style="float: centre;">
  <tr>
    <td width="1053"><strong><a href="<?php echo 'directory.php?currentDir=uploads/' . $accountName . '/'; ?>">Home</a></strong> <strong>&gt;</strong> [folder] <strong>&gt;</strong> [folder]</td>
    <td width="142"><div align="center"><strong><a href="upload.php">Upload</a></strong></div></td>
    <?php 
	try {
		$result = $pdo->query("select (select sum(filesize) from metadata where metadata.owner = users.username) current_storage1, max_storage from users where username = '$accountName'");
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$pdo = null;
	$rows = $result->fetchAll();
	$row = $rows[0];
	$space = round($row['current_storage1'] / 1024, 2) . 'KB / ' . $row['max_storage'] . "KB";
	?>
	<td width="135">Current Storage Space: <?php echo $space; ?> </td>
    <td width="88">View: List, Grid</td>
    <td width="235">
    
    <!-- Search Form -->
    <form name="searchForm" action="" method="GET">
			<input type="text" name="searchStr" value="">
            <input type="submit" name="searchButton" value="Search">
	</form>

    </td>
  </tr>
</table>

<table class="directoryTable"  id="directoryTable" width="75%" border="1" style="float: left;">
  <thead>
      <tr>
        <th id="nameHead" onclick="orderTable(0, true)">Name</th>
        <th id="typeHead" onclick="orderTable(1, true)">Type</th>
        <th id="timeHead" onclick="orderTable(2, true)">Last Modified</th>
        <th id="sizeHead" onclick="orderTable(3, true)">Size</th>
        <?php 
            if ($searchStr) { 
                echo '<th id="dirHead"  onclick="orderTable(4, true)">Directory</th>';
            }
        ?>
      </tr>
    </thead>
    <tbody>
        <!--File Selection Form -->
        <form action="directory.php" method="post">
	        <?php
		        if (!$searchStr) {
                    // Get metadata table info
		            $metadata = queryDB('SELECT * FROM metadata WHERE location = "' . $currentDir . '" AND owner = "' . $accountName . '"');
		            // Define desired columns
		            $columns = array('filename', 'filetype', 'timestamp', 'filesize');
                    // Write to HTML table
		            writeTable($metadata, $columns, $selectedFile, $isFolder, $currentDir, $accountName, $searchStr);
                } else {
		            $metadata = queryDB('SELECT * FROM metadata WHERE owner = "' . $accountName . '" AND filename LIKE "%' . $searchStr . '%" OR description LIKE "%' . $searchStr . '%" OR filetype LIKE "' . $searchStr . '"');
		            $columns = array('filename', 'filetype', 'timestamp', 'filesize', 'location');
                    writeSearchResults($metadata, $columns, $accountName);
                }
	        ?>
        </form>
    </tbody>
</table>
<div class="fileInfoDiv">
    <!--File Information Table -->
    <table class="fileInfoTable" border="1">
      <tr>
        <td id="fileNameCell" colspan="2">
        <?php
            echo "<b>";
    	    if (isset($selectedFile)) {
    		    echo $selectedFile;
    	    } else {
    		    echo "No file selected";
    	    }
            echo "</b>";
        ?> 
        </td>
      </tr>
      <tr>
        <td id="descriptionTagCell" colspan="2"><strong>Description:</strong></td>
      </tr>
      <tr>
        <td id="descriptionCell" colspan="2">
		<?php
			$description = queryDB('SELECT description FROM metadata WHERE filename = "' . $selectedFile . '" AND owner = "' . $accountName . '"');
			
			if ($description == NULL) {
				echo "No description avaliable.";
			}
			else {
				foreach ($description as $item)
				{
					echo $item['description'];
				}
			}
		?>
		</td>
      </tr>
     <!-- <tr>
        <td id="colourTagCell" colspan="2"><strong>Colour tag:</strong></td>
      </tr>
      <tr>
        <td id="tagCell" colspan="2">
		</td>
      </tr> -->
    
        <!--File Management Form -->
        <form action="directory.php" method="get" id="fileManForm">
            <input type="hidden" value="<?php if ($isSelected) { echo $selectedFile; } ?>" name="selectedFile" id="selectedFileHidden">
            <input type="hidden" value="<?php echo $currentDir; ?>" name="currentDir" id="currentDirHidden">
			<tr id="fileManButtons">
			<td><div id="fileManDiv"><select name="colour">
				<option value="none">No Colour</option>
				<option value="blue">Blue</option>
				<option value="red">Red</option>
				<option value="green">Green</option>
				<option value="yellow">Yellow</option>
				<option value="pink">Pink</option>
				</select></div></td>
				<td><div id="fileManDiv"><input type="submit" value="Change Colour" name="colour_select"></div></td>
			</tr>
            <tr id="fileManButtons">
                <td><div id="fileManDiv"><input type="submit" value="Download" name="download" id="fileManButton"></div></td>
                <td><div id="fileManDiv"><input type="submit" value="Edit" name="edit" id="fileManButton"></div></td>
            </tr>
            <tr id="fileManButtons">
                <td><div id="fileManDiv"><input type="submit" value="Share" name="share" id="fileManButton"></div></td>
                <td><div id="fileManDiv"><input type="submit" value="Delete" name="delete" id="fileManButton"></div></td>
            </tr>
            <tr id="fileManButtons">
                <td><div id="fileManDiv"><input type="submit" value="New Folder" name="newFolder" id="newFolderButton" class="active"></div></td>
                <td><div id="fileManDiv"><input type="submit" value="Move To..." name="moveTo" id="fileManButton"></div></td>
            </tr>
            <script> setFileManButtons(<?php echo JSON_encode(!$isSelected); ?>); </script>
        </form>

    </table>
</div>

<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>