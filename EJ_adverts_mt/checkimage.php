<?php
	$split = explode("\\",$_POST['img']);
	foreach($split as $filename){}
	if (file_exists("images/{$_REQUEST['adid']}/".$filename)) {
		print("An image called ".$filename." already exists!");
	} else {
		print("OK");
	}
?>