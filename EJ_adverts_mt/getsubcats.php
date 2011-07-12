<?php 
session_start();
if ($_POST['key'] != $_SESSION['key'] or $_POST['key']=="")
{
	echo "<p class=\"EJ_user_error\"><strong>AUTHORISATION ERROR</strong>: Unable to verify key!</p>";
} else
{
	$EJ_initPage ='ajax';
	require('../../init.inc.php');
	$content = "<option value=\"0\" selected=\"selected\">Any Sub-Category</option>";
	if (!empty($_POST['cat']))
	{
		$EJ_mysql->query("SELECT * FROM {$EJ_mysql->prefix}module_EJ_adverts_mt_cats WHERE subCatOf = {$_POST['cat']} AND (SELECT COUNT(*) FROM {$EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertCat LIKE CONCAT('%(',catId,')%')) !=0 ORDER BY catName");
		while ($cat = $EJ_mysql->getRow())
		{
			$content .= "
						<option value=\"{$cat['catId']}\">{$cat['catName']}</option>";
		}
	}
	echo $content;
}
?>