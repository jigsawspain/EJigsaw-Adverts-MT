<?php
session_start();
if ($_SESSION['key'] != $_POST['key'] or $_POST['key']=="")
{
	echo "<p class=\"EJ_user_error\"><strong>AUTHORISATION ERROR</strong>: Unable to verify key!</p>";
} else
{
	$EJ_initPage ='ajax';
	require('../../init.inc.php');
	if (empty($_POST['id']))
	{
		$query = "INSERT INTO {$EJ_mysql->prefix}module_EJ_adverts_mt_locs SET locName='".urldecode($_POST['locName'])."'";
	} else
	{
		$query = "UPDATE {$EJ_mysql->prefix}module_EJ_adverts_mt_locs SET locName='".urldecode($_POST['locName'])."' WHERE locId = ".$_POST['id'];
	}
	$EJ_mysql->query($query);
}
?>