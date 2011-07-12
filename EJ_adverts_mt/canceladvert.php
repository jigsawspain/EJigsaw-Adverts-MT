<?php
session_start();
if ($_SESSION['key'] != $_POST['key'] or $_POST['key']=="")
{
	echo "<p class=\"EJ_user_error\"><strong>AUTHORISATION ERROR</strong>: Unable to verify key!</p>";
} else
{
	if (is_dir('images/'.$_POST['key']))
	{
		$dir = opendir('images/'.$_POST['key']);
		while ($file = readdir($dir))
		{
			if ($file!='.' and $file!='..')
			{
				unlink('images/'.$_POST['key'].'/'.$file);
			}
		}
		rmdir('images/'.$_POST['key']);
	}
}
?>