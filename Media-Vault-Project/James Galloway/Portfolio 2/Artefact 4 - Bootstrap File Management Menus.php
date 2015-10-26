<!-- New Folder -->
<div class="dropdown">
	<button id="newFolderBtn" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		New Folder
	</button>
	<ul class="dropdown-menu" aria-labelledby="newFolderMenu">
		<li><h4>New Folder</h4></li>
		<li><input type='text' name='folderName' placeholder="Name"></li>
		<li>
			<input class="btn btn-default dropdown-toggle" type='submit' name='confirmNewFolder' value='Create'>
		</li>
	</ul>
</div>

<!-- Edit -->
<div class="dropdown">
<button id="editBtn" class="btn btn-default dropdown-toggle" type="button" id="editMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
	Edit
</button>
	<ul class='dropdown-menu' aria-labelledby='editButton'>
		<li><h4>Edit</h4></li>
		<li><input type='text' name='newName' placeholder="File Name"></li>
		<li><input type='text' name='newDescription' placeholder="Description"></li>
		<li><input class="btn btn-default dropdown-toggle" type='submit' name='confirmEdit' value='Confirm'></li>
	</ul>
</div>

<!-- Move To... -->
<div class="dropdown">
	<button id="moveToBtn" class="btn btn-default dropdown-toggle" type="button" id="moveToMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		Move To...
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" aria-labelledby="moveToMenu" id="moveToList">
		<li><h4>Move To...</h4></li>
		<?php writeFolders($accountName, $selectedFile); ?>
	</ul>
</div>

<input id="downloadBtn" type="submit" class="btn btn-default" value="Download" name="download" id="fileManButton">

<!-- Delete -->
<div class="dropdown">
	<button id="deleteBtn" class="btn btn-default dropdown-toggle" type="button" id="moveToMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		Delete
	</button>
<ul class="dropdown-menu" aria-labelledby="moveToMenu">
	<li>Are you sure you want to delete this file?</li>
	<li><input class="btn btn-default dropdown-toggle" type="submit" name="confirmDelete" value="Yes"></li>
</div>

<input  id="shareBtn" type="submit" class="btn btn-default" value="Share" name="share">