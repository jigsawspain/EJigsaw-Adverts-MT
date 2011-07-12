<?php
session_start();
if ($_SESSION['key'] != $_POST['key'] or $_POST['key']=="")
{
	echo "<p class=\"EJ_user_error\"><strong>AUTHORISATION ERROR</strong>: Unable to verify key!</p>";
} else
{
	$EJ_initPage ='ajax';
	require('../../init.inc.php');
	if ($_POST['subCatOf']=="NONE")
	{
		$subcatof = 'NULL';
	} else
	{
		$subcatof = $_POST['subCatOf'];
	}
	if (empty($_POST['id']))
	{
		$query = "INSERT INTO {$EJ_mysql->prefix}module_EJ_adverts_mt_cats SET catName='".urldecode($_POST['catName'])."', catDesc='".nl2br(urldecode($_POST['catDesc']))."', subCatOf = $subcatof";
	} else
	{
		$query = "UPDATE {$EJ_mysql->prefix}module_EJ_adverts_mt_cats SET catName='".urldecode($_POST['catName'])."', catDesc='".nl2br(urldecode($_POST['catDesc']))."', subCatOf = $subcatof WHERE catId = ".$_POST['id'];
	}
	$EJ_mysql->query($query);
}
?>