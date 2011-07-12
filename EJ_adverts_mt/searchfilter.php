<?php 
session_start();
if ($_POST['key'] != $_SESSION['key'] or $_POST['key']=="")
{
	echo "<p class=\"EJ_user_error\"><strong>AUTHORISATION ERROR</strong>: Unable to verify key!</p>";
	echo "<p>{$_SESSION['key']}::{$_POST['key']}</p>";
} else
{
	$EJ_initPage ='ajax';
	require('../../init.inc.php');
	$query="SELECT SQL_CALC_FOUND_ROWS * FROM {$EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertId != 0";
	if ($_POST['date']!=0)
	{
		$date = date('Y-m-d', strtotime($_POST['date']));
		$query .= " AND EJ_advertDate <= '$date'";
	}
	if (!empty($_POST['text']))
	{
		$words = explode(" ", $_POST['text']);
		$query .= " AND (";
		foreach($words as $word)
		{
			if (!empty($word))
			{
				$query .= "(EJ_advertText LIKE '%$word%' OR EJ_advertTitle LIKE '%$word%') AND ";
			}
		}
		$query = substr($query, 0, -5).")";
	}
	if ($_POST['category']!=0)
	{
		$query .= " AND EJ_advertCat LIKE '%(".$_POST['category'].")%'";
	}
	if ($_POST['poster']!="0")
	{
		$query .= " AND EJ_advertPoster = '".$_POST['poster']."'";
	}
	if ($_POST['hidden']==1)
	{
		$query .= " AND EJ_advertHidden = 0";
	}
	if (!empty($_POST['attributes']))
	{
		$advertatts = explode(":",$_POST['attributes']);
		$query .= " AND (";
		$i=0;
		foreach ($advertatts as $att)
		{
			if ($i != 0)
				$query .= " OR ";
			else
				$i=1;
			$query .= "EJ_advertAttributes LIKE '%($att)%'";
		}
		$query .= ")";
	}
	if (!empty($_POST['locations']))
	{
		$advertlocs = explode(":",$_POST['locations']);
		$query .= " AND (";
		$i=0;
		foreach ($advertlocs as $loc)
		{
			if ($i != 0)
				$query .= " OR ";
			else
				$i=1;
			$query .= "EJ_advertLoc LIKE '%($loc)%'";
		}
		$query .= ")";
	}
	$query .= " ORDER BY ";
	switch ($_POST['order'])
	{
		case 'TitleA':
			$query .= "EJ_advertTitle ASC";
		break;
		case 'TitleD':
			$query .= "EJ_advertTitle DESC";
		break;
		case 'PosterA':
			$query .= "EJ_advertPoster ASC";
		break;
		case 'PosterD':
			$query .= "EJ_advertPoster DESC";
		break;
		case 'ExpiryA':
			$query .= "EJ_advertDate ASC";
		break;
		case 'ExpiryD':
			$query .= "EJ_advertDate DESC";
		break;
	}
	$startlimit = (($_POST['page']-1)*$_POST['limit']);
	$query .= " LIMIT ".$startlimit.",".$_POST['limit'];
	$EJ_mysql->query($query);
	if ($EJ_mysql->numRows() == 0)
	{
		$content = '<div class="advert_result" style="text-align: center;"><p><strong>No Results Found! Please try a broader search.</strong></p></div>';
	} else
	{
		while ($result = $EJ_mysql->getRow())
		{
			if (empty($result['EJ_advertImages']) or !file_exists("images/".$result['EJ_advertId']."/".$result['EJ_advertImages']))
			{
				$img = "noimage.png";
			} else
			{
				$img = $result['EJ_advertId']."/".$result['EJ_advertImages'];
			}
			$date = date("d/m/Y", strtotime($result['EJ_advertDate']));
			if ($result['EJ_advertDate'] < date("Y-m-d", time()))
				$date = "<span style=\"color:#F00\">".$date."</span>";
			$content .= '
				<div class="advert_result" id="'.$result['EJ_advertId'].'">
					<div style="float: right;"><img src="modules/EJ_adverts_mt/recycle.png" alt="delete" title="Delete advert" style="cursor: pointer;" onclick="deleteadvert(\''.$result['EJ_advertId'].'\', \''.$_SESSION['key'].'\')" /> <a href="?module=EJ_adverts_mt&action=editadvert&advertid='.$result['EJ_advertId'].'"><img src="modules/EJ_adverts_mt/edit.png" alt="edit" title="Edit advert" style="cursor: pointer;" /></a> <img src="modules/EJ_adverts_mt/blue_down.png" alt="show/hide details" title="Show/Hide Details" style="cursor: pointer;" onclick="Slide(this.parentNode.parentNode, 16, 150)" /></div>
					<p><strong>'.$result['EJ_advertTitle'].'</strong> posted by: '.$result['EJ_advertPoster'].' - <strong>Renewal:</strong> '.$date.'</p>
					<p><img class="advertImage" src="modules/EJ_adverts_mt/images/'.$img.'" alt="'.$advert['EJ_advertTitle'].'" />'.$result['EJ_advertText'].'</p>
				</div>';
		}
	}
	$EJ_mysql->query("SELECT FOUND_ROWS() as results");
	$rows = $EJ_mysql->getRow();
	$pages = "<div style=\"margin: 10px 10px 0 10px;\"><div style=\"float:right;\">Page ";
		$pages .= "<select name=\"page\" id=\"page\" onchange=\"updateFilter('{$_SESSION['key']}')\">";
		$pages .= "<option value=\"1\" selected=\"selected\">1</option>";
		for ($i=2; $i<=ceil($rows['results']/$_POST['limit']); $i++)
		{
			if ($_POST['page'] == $i)
				$selected = " selected=\"selected\"";
			else
				$selected = "";
			$pages .= "<option value=\"$i\"$selected>$i</option>";
		}
		$pages .= "</select>";
		$pages .= " of ".ceil($rows['results']/$_POST['limit'])."</div>";
		$sorts = array('TitleD'=>'Title (Z-A)', 'PosterA'=>'Posted By (A-Z)', 'PosterD'=>'Posted By (Z-A)', 'ExpiryA'=>'Expiring Soonest', 'ExpiryD'=>'Expiring Latest');
		$sort = "<div style=\"float:left;\"><strong>Sort By:</strong> <select name=\"order\" id=\"order\" onchange=\"updateFilter('{$_SESSION['key']}')\">";
		$sort .= "<option value=\"TitleA\" selected=\"selected\">Title (A-Z)</option>";
		foreach ($sorts as $sortitem => $sortname)
		{
			if ($_POST['order']==$sortitem)
				$selected=" selected=\"selected\"";
			else
				$selected="";
			$sort .= "<option value=\"$sortitem\"$selected>$sortname</option>";
		}
		$sort .= "</select></div><div style=\"clear:both;\"></div></div>";
	$content = $pages.$sort.$content.":::".$rows['results'];
	echo $content;
}
?>