<?php 
session_start();
if ($_POST['key'] != $_SESSION['key'] or $_POST['key']=="")
{
	echo "<p class=\"EJ_user_error\"><strong>AUTHORISATION ERROR</strong>: Unable to verify key!</p>";
} else
{
	$EJ_initPage ='ajax';
	require('../../init.inc.php');
	$locfind = "SELECT locName FROM {$EJ_mysql->prefix}module_EJ_adverts_mt_locs WHERE locID = SUBSTRING_INDEX(SUBSTR(EJ_advertLoc,2),')',1)";
	$query="SELECT SQL_CALC_FOUND_ROWS *, (SELECT catName FROM {$EJ_mysql->prefix}module_EJ_adverts_mt_cats WHERE catId = EJ_advertCat) as catName, ($locfind) as locName FROM {$EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertHidden = 0";
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
	if (!empty($_POST['cat']) and empty($_POST['subcat']))
	{
		$EJ_mysql->query("SELECT catId FROM {$EJ_mysql->prefix}module_EJ_adverts_mt_cats WHERE catId = {$_POST['cat']} OR subCatOf = {$_POST['cat']}");
		$query .= " AND (";
		while ($catid = $EJ_mysql->getRow())
		{
			$query .= "EJ_advertCat LIKE '%({$catid['catId']})%' OR ";
		}
		$query = substr($query,0,-4);
		$query .= ")";
	}
	if (!empty($_POST['subcat']))
	{
		$EJ_mysql->query("SELECT catId FROM {$EJ_mysql->prefix}module_EJ_adverts_mt_cats WHERE catId = {$_POST['subcat']}");
		$query .= " AND (";
		while ($catid = $EJ_mysql->getRow())
		{
			$query .= "EJ_advertCat LIKE '%({$catid['catId']})%' OR ";
		}
		$query = substr($query,0,-4);
		$query .= ")";
	}
	if (!empty($_POST['attributes']))
	{
		$att = $_POST['attributes'];
		$skip = 1;
		if (!empty($att))
		{
			$query .= " AND (";
			$query .= "EJ_advertAttributes LIKE '%($att)%'";
			$query .= ")";
		}
	}
	if (!empty($_POST['locations']))
	{
		$loc = $_POST['locations'];
		$skip = 1;
		if (!empty($loc))
			$query .= " AND (";
			$query .= "EJ_advertLoc LIKE '%($loc)%'";
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
		default:
			$query .= "EJ_advertTitle ASC";
		break;
	}
	/*
	if (!empty($_POST['limit']))
	{
		$startlimit = (($_POST['page']-1)*$_POST['limit']);
		$limit = $_POST['limit'];
	} else
	{
		$startlimit = 0;
		$limit = 10;
	}
	*/
	$limit = 10;
	$startlimit = (($_POST['page']-1)*$limit);
	$query .= " LIMIT ".$startlimit.",".$limit;
	$EJ_mysql->query($query);
	if ($EJ_mysql->numRows() == 0)
		{
			$content .= '<p style="text-align: center;"><strong>No Adverts Found!<br/>';
			if ($_POST['page']!=1)
			{
				$content .= '</strong><br/>Try page 1 by clicking above</p>';
			}
			else
			{
				$content .= 'Please try a broader search filter.</strong></p>';
			}
		} else
		{
			while($advert = $EJ_mysql->getRow())
			{
				if (strrpos($advert['EJ_advertLoc'],"(")!=0)
				{
					$advert['locName'] = "Multiple Locations";
				}
				if (!empty($advert['EJ_advertImages']) and file_exists(dirname(__FILE__)."/images/".$advert['EJ_advertId']."/".$advert['EJ_advertImages']))
				{
					$image = "<img class=\"EJ_advertResult_img\" src=\"{$EJ_settings['instloc']}modules/EJ_adverts_mt/image.php/{$advert['EJ_advertImages']}?image={$EJ_settings['instloc']}modules/EJ_adverts_mt/images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}&amp;height=100&amp;width=100\" alt=\"{$EJ_advertTitle}\"/>";
				} else
				{
					$image = "<img class=\"EJ_advertResult_img\" src=\"{$EJ_settings['instloc']}modules/EJ_adverts_mt/image.php/noimage.png?image={$EJ_settings['instloc']}modules/EJ_adverts_mt/images/noimage.png&amp;height=100&amp;width=100\" alt=\"{$advert['EJ_advertTitle']}\"/>";
				}
				$content .= "<div class=\"EJ_advertResult\" id=\"{$advert['EJ_advertId']}\"><div class=\"EJ_advertResult_header\"><a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">{$advert['EJ_advertTitle']}</a></div><div class=\"EJ_advertResult_left\"><a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">$image</a>".substr(str_replace(array("<br>", "<br/>", "<br />", "\n", "\r")," ", $advert['EJ_advertText']),0,150)."... <a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">more</a></div><div class=\"EJ_advertResult_right\">{$advert['locName']}<br/>{$advert['catName']}<br/>{$advert['EJ_advert']}</div><div style=\"clear: left;\"></div></div>";
			}
		}
	$EJ_mysql->query("SELECT FOUND_ROWS() as results");
	$rows = $EJ_mysql->getRow();
	for ($i=1; $i<=ceil($rows['results']/$limit); $i++)
	{
		if ($_POST['page'] == $i)
		{
			$selected = "<strong>";
			$endselected = "</strong> | ";
			$pages .= $selected.$i.$endselected;
		}
		elseif (($i>($_POST['page']-3) and $i<($_POST['page']+3)) or $i == 1 or $i == ceil($rows['results']/$limit))
		{
			if ($i == ceil($rows['results']/$limit) and $i>($_POST['page']+3)) {
				$startselected = ".. ";
				$pages = substr($pages,0,-2);
			} else {
				$startselected = "";
			}
			$selected = $startselected."<a href=\"javascript: setPage($i,'{$_SESSION['key']}','{$EJ_settings['instloc']}')\">";
			if ($i == 1 and $i<($_POST['page']-3)) {
				$endselected = "</a> .. ";
			} else {
				$endselected = "</a> | ";
			}
			$pages .= $selected.$i.$endselected;
		}
	}
	$pages = substr($pages,0,-3);
	if ($pages=="")
			$pages .= " <strong>1</strong>";
	$pages .= "</div>";
	$pages1 = "<div id=\"pages\">Page ".$pages;
	$pages2 = "<div id=\"pages\" style=\"margin-top: 0;\">Page ".$pages;
	/*
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
	*/
	$content = $pages1.$sort.$content.$pages2.":::".$rows['results'];
	echo $content;
}
?>