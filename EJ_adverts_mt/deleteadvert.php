<?php
session_start();
if ($_SESSION['key'] != $_POST['key'] or $_POST['key']=="")
{
	echo "<p class=\"EJ_user_error\"><strong>AUTHORISATION ERROR</strong>: Unable to verify key!</p>";
} else
{
	$EJ_initPage ='ajax';
	require('../../init.inc.php');
	$query = "DELETE FROM {$EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertId = ".$_POST['id'];
	$EJ_mysql->query($query);
	if (is_dir('images/'.$_POST['id']))
	{
		$dir = opendir('images/'.$_POST['id']);
		while ($file = readdir($dir))
		{
			unlink('images/'.$_POST['id'].'/'.$file);
		}
		rmdir('images/'.$_POST['id']);
	}
}
?>