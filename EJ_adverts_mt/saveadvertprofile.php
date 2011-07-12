<?php
function to_utf8( $string ) { 
    if ( preg_match('%^(?: 
      [\x09\x0A\x0D\x20-\x7E]            # ASCII 
    | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte 
    | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs 
    | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte 
    | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates 
    | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3 
    | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15 
    | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16 
)*$%xs', $string) ) { 
        return $string; 
    } else {
        return iconv( 'CP1252', 'UTF-8', $string); 
    } 
} 

session_start();
if ($_SESSION['key'] != $_POST['key'] or $_POST['key']=="")
{
	echo "<p class=\"EJ_user_error\"><strong>AUTHORISATION ERROR</strong>: Unable to verify key!</p>";
} else
{
	$EJ_initPage ='ajax';
	require('../../init.inc.php');
	$query = "UPDATE {$EJ_mysql->prefix}module_EJ_adverts_mt SET EJ_advertTitle='".addslashes(urldecode(to_utf8($_POST['title'])))."', EJ_advertTag='".addslashes(urldecode($_POST['tag']))."', EJ_advertText='".addslashes(str_replace(array("\n", "<br>", "<br/>", "£"),array("<br />","<br />","<br />", "&pound;"),urldecode(to_utf8($_POST['desc']))))."', EJ_advertCat = '".$_POST['cat']."', EJ_advertImages='".$_POST['image']."', EJ_advertLoc = '".$_POST['locs']."', EJ_advertAttributes = '".$_POST['atts']."', EJ_advertAddress1 = '".$_POST['address1']."', EJ_advertAddress2 = '".$_POST['address2']."', EJ_advertAddress3 = '".$_POST['address3']."', EJ_advertAddress4 = '".$_POST['address4']."', EJ_advertAddress5 = '".$_POST['address5']."', EJ_advertPhone = '".$_POST['phone']."', EJ_advertWebsite = '".$_POST['website']."', EJ_advertContact = '".$_POST['contact']."', EJ_advertExtra = '".addslashes(str_replace(array("\n", "<br>", "<br/>", "£"),array("<br />","<br />","<br />", "&pound;"),to_utf8(urldecode($_POST['extra']))))."' WHERE EJ_advertId = ".$_POST['id']."";
	$EJ_mysql->query($query);
	if (is_dir('images/'.$_REQUEST['key']) and !isset($_POST['id']))
	{
		$id = mysql_insert_id();
		$dir = opendir('images/'.$_REQUEST['key'].'/');
		while ($file = readdir($dir))
		{
			if ($file != '.' and $file!='..')
			{
				rename('images/'.$_REQUEST['key'].'/'.$file, 'images/'.$id.'/'.$file);
				unlink('images/'.$_REQUEST['key'].'/'.$file);
			}
		}
		rmdir('images/'.$_REQUEST['key']);
	}
	echo "OK";
}
?>