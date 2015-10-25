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
<li><a  class="bottom" href="#" data-toggle="tooltip" data-placement="bottom" title="Current Storage Space: <?php echo $space; ?>"><?php echo $accountName ?></a></li>

<li class="list-group-item">Colour tag:<br>
						<button type="submit" name="colour" value="red"><img src="images/red.png"></button> 
						<button type="submit" name="colour" value="aqua"><img src="images/aqua.png"></button> 
						<button type="submit" name="colour" value="lime"><img src="images/lime.png"></button> 
						<button type="submit" name="colour" value="yellow"><img src="images/yellow.png"></button> 
						<button type="submit" name="colour" value="pink"><img src="images/pink.png"></button> 
						<button type="submit" name="colour" value=NULL><img src="images/none.png"></button> 
					</li>
					
