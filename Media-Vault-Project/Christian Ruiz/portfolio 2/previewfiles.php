<?php

	$FileSelected = 'Christian Ruiz.pdf';
	$FileName = 'Christian Ruiz.pdf';
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="' . $FileName . '"');
	header('Content-Transfer-Encoding; binary');
	header('Accept-Ranges: bytes');
	@readfile($FileSelected);

	$FileSelected = 'video.mp4';
	$FileName = 'video.mp4';
	header('Content-type: video/mp4');
	header('Content-Disposition: inline; filename="' . $FileName . '"');
	header('Content-Transfer-Encoding; binary');
	header('Accept-Ranges: bytes');
	@readfile($FileSelected);
	
	$FileSelected = 'song.mp3';
	$FileName = 'song.mp3';
	header('Content-type: audio/mp3');
	header('Content-Disposition: inline; filename="' . $FileName . '"');
	header('Content-Transfer-Encoding; binary');
	header('Accept-Ranges: bytes');
	@readfile($FileSelected);
	
	

?>