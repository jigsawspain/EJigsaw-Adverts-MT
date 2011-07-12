<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php
if (!isset($_POST['imagefind']) or empty($_POST['imagefind'])) {
	if (isset($_POST['save'])) {
		$message="ERROR: Upload error or no picture selected.";
	}
	if ($_REQUEST['action']=='delete' and !empty($_REQUEST['image']))
	{
		if (file_exists('images/'.$_REQUEST['id'].'/'.$_REQUEST['image']))
			unlink('images/'.$_REQUEST['id'].'/'.$_REQUEST['image']);
		else
			echo "ERROR: File '{$_REQUEST['image']}' Not Found! (It may have already been deleted)";
	}
?>
<script src="../EJ_adverts_mt/EJ_adverts_mt.js" language="javascript" type="text/javascript"></script>
<style>
* {
	font-family: Verdana, Geneva, sans-serif;
}
</style>
<form name="imageform" id="imageform" method="post" enctype="multipart/form-data" action="../EJ_adverts_mt/changepic.php?id=<?=$_REQUEST['id']?>" style="font-size:12px;">
	<p><strong>Select an existing picture to use</strong><br/>
	<div id="message" style="margin:0; padding:0;"><?=$message?></div>
		<?php
			if (!is_dir('/images/'.$_REQUEST['id'].'/'))
			{
				mkdir('/images/'.$_REQUEST['id'].'/',0777,true);
			}
			$directory = opendir('images/'.$_REQUEST['id'].'/');
			$imgcount=0;
			while ($file = readdir($directory)) {
				if ($file!='..' and $file!='.' and !is_dir($file) and (substr($file,-4)==".gif" or substr($file,-4)==".jpg" or substr($file,-4)==".png" or substr($file,-4)==".GIF" or substr($file,-4)==".JPG" or substr($file,-4)==".PNG")){
					echo "<div style=\"display:inline-block; text-align: center; font-size: 0.8em;\"><img style=\"width: 90px; height: 90px; margin: 5px; cursor: pointer;\" src=\"images/{$_REQUEST['id']}/$file\" alt=\"$file\" title=\"$file\" onclick=\"selectImage('$file')\" /><br/><a href=\"images/{$_REQUEST['id']}/$file\" target=\"_blank\">See Full Image</a><br/><a href=\"javascript:if (confirm('Are You sure you want to delete $file?')) { document.location='?id={$_REQUEST['id']}&action=delete&image=$file'; }\">Delete Image</a></div></div>";
					$imgcount++;
				}
			}
		?>
		<input type="hidden" name="imagefind" id="imagefind"/>
	</p>
	<?php
		if ($imgcount<6)
		{
	?>
	<p style="text-align: center; font-size: 1.2em;">
		<strong>OR</strong>
	</p>
	<p>
		<input type="button" name="new" id="new" value="Upload New Image" style="width:100%;" onclick="document.location='newpic.php?adid=<?=$_REQUEST['id']?>'"/>
	</p>
	<?php
		}
		else
		{
		?>
	<p style="text-align: center; font-size: 1.2em;">
		Maximum 6 Images Reached
	</p>
		<?php
		}
	?>
</form>
<?php
} else {
	print("<script src=\"EJ_adverts_mt.js\" language=\"javascript\" type=\"text/javascript\" onload=\"updateimage('".$_POST['imagefind']."','{$_REQUEST['id']}');\"></script>");
	print("Image Updated!");
}
?>