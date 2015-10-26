<?php
	/**
	 * Construct and echo breadcrumb to the directory page.
	 *
	 * @param str $dir - the directory the user is current in (eg uploads/username/My Folder/Images/)
	 *
	 * @author James Galloway
	 */
function writeBreadcrumb($dir) {
    echo '<ol class="breadcrumb">';
    $folders = explode("/", $dir);
   
    $url = $folders[0];
    for ($i = 1; $i < count($folders); $i++) {
        $url = $url . '/' . $folders[$i];
        echo '<li><a href="directory.php?currentDir=' . $url . '/">' . $folders[$i] . '</a></li>';
    }

	echo '</ol>';
} // end writeBreadcrumb

?>