<?php

/*
*** EJigsaw Adverts Module
**
*** By Jigsaw Spain - www.jigsawspain.com
**
*** Business directory script using micro-transactions for eJigsaw SAS
*/

if (!class_exists("EJ_adverts_mt"))
{
class EJ_adverts_mt
{
	public $version = "0.2";
	public $creator = "Jigsaw Spain";
	public $name = "EJigsaw Adverts MT";
	private $EJ_mysql;
	private $vars;
	private $moduleloc;
	private $EJ_settings;
	
	function EJ_adverts_mt ($EJ_mysql, $_vars, $_settings)
	{
		$this->EJ_mysql = $EJ_mysql;
		$this->vars = $_vars;
		$this->moduleloc = "modules/".get_class($this)."/";
		$this->EJ_settings = $_settings;
	}
	
	function install()
	{
		echo "
			<p class=\"EJ_instText\">
			&gt; EJ Adverts MT Install Procedure
			</p>";
		// Check for / create table
		echo "
			<p class=\"EJ_instText\">
			&gt; Checking and Creating Tables...
			</p>";
		$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_adverts_mt%'");
		if ($this->EJ_mysql->numRows() == 2)
		{
			echo "
			<p class=\"EJ_instText\">
				&gt;&gt; EJ_adverts_mt tables already found!<br/>
				&gt;&gt; Checking default settings
			</p>";
		} else
		{
			// Main Adverts Table
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt (
				EJ_advertId INT(11) NOT NULL AUTO_INCREMENT ,
				EJ_advertDate DATE NOT NULL ,
				EJ_advertLoc TEXT NOT NULL ,
				EJ_advertTitle VARCHAR(100) NOT NULL ,
				EJ_advertTag VARCHAR(150) ,
				EJ_advertText TEXT ,
				EJ_advertImages TEXT ,
				EJ_advertHidden TINYINT(1) NOT NULL DEFAULT 1 ,
				EJ_advertPoster VARCHAR(20) NOT NULL ,
				EJ_advertCat TEXT NOT NULL ,
				EJ_advertAddress1 VARCHAR(150) NOT NULL DEFAULT 'No Address Provided' ,
				EJ_advertAddress2 VARCHAR(150) NOT NULL DEFAULT 'Please Use Enquiry Form' ,
				EJ_advertAddress3 VARCHAR(150) ,
				EJ_advertAddress4 VARCHAR(150) ,
				EJ_advertAddress5 VARCHAR(150) ,
				EJ_advertPhone VARCHAR(15) ,
				EJ_advertWebsite VARCHAR(150) ,
				EJ_advertContact VARCHAR(150) NOT NULL ,
				EJ_advertAttributes TEXT NOT NULL ,
				EJ_advertTried TINYINT(1) NOT NULL DEFAULT 0 ,
				EJ_advertExtra TEXT ,
				EJ_advertCredits INT(6) NOT NULL DEFAULT 0 ,
				EJ_advertFeaturedDate DATE NOT NULL DEFAULT '2011-01-01' ,
				EJ_advertTagAllowed TINYINT(1) NOT NULL DEFAULT 0 ,
				EJ_advertMaxLocs INT(2) NOT NULL DEFAULT 1 ,
				EJ_advertMaxCats INT(2) NOT NULL DEFAULT 1 ,
				EJ_advertMaxAtts INT(2) NOT NULL DEFAULT 1 ,
				EJ_advertTextAllowed TINYINT(1) NOT NULL DEFAULT 0 ,
				EJ_advertPhoneAllowed TINYINT(1) NOT NULL DEFAULT 0 ,
				EJ_advertWebAllowed TINYINT(1) NOT NULL DEFAULT 0 ,
				EJ_advertMapAllowed TINYINT(1) NOT NULL DEFAULT 0 ,
				EJ_advertContactAllowed TINYINT(1) NOT NULL DEFAULT 0 ,
				PRIMARY KEY (EJ_advertId)
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_adverts_mt'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			// Advert Settings Table
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_settings (
				setting VARCHAR(20) NOT NULL ,
				value VARCHAR(100) NOT NULL ,
				PRIMARY KEY (setting)
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_adverts_mt_settings'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			// Advert Categories Table
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats (
				catId INT(6) NOT NULL AUTO_INCREMENT,
				subCatOf INT(6) ,
				catName VARCHAR(30) NOT NULL ,
				catDesc TEXT ,
				PRIMARY KEY (catId)
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			// Advert Attributes Table
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts (
				attId INT(6) NOT NULL AUTO_INCREMENT,
				attName VARCHAR(30) NOT NULL ,
				attDesc TEXT ,
				PRIMARY KEY (attId)
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			// Advert Locations Table
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs (
				locId INT(10) NOT NULL AUTO_INCREMENT,
				locName VARCHAR(50) NOT NULL ,
				locLevel TINYINT(1) NOT NULL DEFAULT 0 ,
				PRIMARY KEY (locId)
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits (
				adId INT(11) NOT NULL,
				hitMonth VARCHAR(4) NOT NULL ,
				hits INT(7) NOT NULL DEFAULT 0
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_credits (
				tranId INT(11) NOT NULL AUTO_INCREMENT,
				userId VARCHAR(4) NOT NULL ,
				buysell VARCHAR(1) NOT NULL,
				amount int(6) NOT NULL,
				PRIMARY KEY (tranId)
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_adverts_mt_credits'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_prices (
				priceId VARCHAR(20) NOT NULL ,
				name VARCHAR(50) NOT NULL ,
				basePrice VARCHAR(1) NOT NULL ,
				fixed TINYINT(1) NOT NULL DEFAULT 0
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_adverts_mt_credits'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			// Create initial advert
			$this->EJ_mysql->query("SELECT catId FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats");
			if ($this->EJ_mysql->numRows()==0)
			{
				echo "
				<p class=\"EJ_instText\">
				&gt; Creating initital advert...
				</p>";
				$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}module_EJ_adverts_mt SET EJ_advertDate = DATE(NOW()), EJ_advertTitle = 'Jigsaw Spain', EJ_advertText = 'This advert has been added by the EJ Adverts MT setup procedure to demonstrate how your adverts will display on your site.<br /><br />Please edit or delete this advert when you are happy with your setup.<br /><br />EJ Adverts MT - By Jigsaw Spain - <a href=\"http://www.jigsawspain.com\" target=\"_blank\">http://www.jigsawspain.com</a>', EJ_advertHidden = 0, EJ_advertPoster = 'admin', EJ_advertCat = 1, EJ_advertImages='noimage.png', EJ_advertAttributes = '1', EJ_advertContact='Elliott Bristow', EJ_advertWebsite = 'http://www.jigsawspain.com'");
			}
			// Create initial categories
			$this->EJ_mysql->query("SELECT catId FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats");
			if ($this->EJ_mysql->numRows()==0)
			{
				echo "
				<p class=\"EJ_instText\">
				&gt; Creating initital advert category...
				</p>";
				$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats SET subCatOf = NULL, catname = 'Default Category', catDesc = 'This is the default category set up by EJ_adverts_mt'");
			}
			// Create initial attributes
			$this->EJ_mysql->query("SELECT attId FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts");
			if ($this->EJ_mysql->numRows()==0)
			{
				echo "
				<p class=\"EJ_instText\">
				&gt; Creating initital advert attributes...
				</p>";
				$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts SET attName = 'Default Attribute', attDesc = 'This is the default attribute set up by EJ_adverts_mt'");
			}
			// Create initial locations
			$this->EJ_mysql->query("SELECT locId FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs");
			if ($this->EJ_mysql->numRows()==0)
			{
				echo "
				<p class=\"EJ_instText\">
				&gt; Creating initital advert attributes...
				</p>";
				$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs SET locName = 'Default Location'");
			}
		}
		// Check for / set up user permissions
		echo "
				<p class=\"EJ_instText\">
				&gt; Checking user permissions...
				</p>";
		$this->EJ_mysql->query("SHOW COLUMNS FROM {$this->EJ_mysql->prefix}users LIKE 'perm_EJ_adverts_mt'");
		if ($this->EJ_mysql->numRows()==0)
		{
			$this->EJ_mysql->query("ALTER TABLE {$this->EJ_mysql->prefix}users ADD perm_EJ_adverts_mt TINYINT(1) NOT NULL DEFAULT 0, ADD EJ_adverts_mt_credits INT(6) NOT NULL DEFAULT 0");
		}
		$this->EJ_mysql->query("UPDATE {$this->EJ_mysql->prefix}users SET perm_EJ_adverts_mt = 1 WHERE userid = 'admin'");
		// Check / create initial settings
		echo "
			<p class=\"EJ_instText\">
			&gt; Creating initital settings...
			</p>";
		$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}module_EJ_adverts_mt_settings (setting, value) VALUES
			('curr', 'Euros') ,
			('curr_symbol', '€') ,
			('credit_in_curr', '1')
			ON DUPLICATE KEY UPDATE setting = setting");
		/*
			curr > Currency Name
			curr_symbol > Currency Symbol
			credit_in_curr > What is 1 credit worth in currency
		*/
		$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}module_EJ_adverts_mt_prices (priceId, name, basePrice, fixed) VALUES
			('unlockFeatured', 'Make Advert Featured', 24, 0) ,
			('addLoc', 'Add Extra Location', 24, 0) ,
			('addAtt', 'Add Extra Attribute', 12, 0) ,
			('addCat', 'Add Extra Category', 24, 0) ,
			('unlockTag', 'Add Tag Line/Subtitle', 12, 0) ,
			('unlockMap', 'Add Interactive Map', 12, 0) ,
			('unlockContact', 'Add Contact Form', 12, 0) ,
			('unlockWeb', 'Add Website Address', 12, 0) ,
			('unlockPhone', 'Add Phone Number', 12, 0) ,
			('unlockText', 'Add Text Description', 12, 0)
			ON DUPLICATE KEY UPDATE priceId = priceId");
		/*
			curr > Currency Name
			curr_symbol > Currency Symbol
			credit_in_curr > What is 1 credit worth in currency
		*/
		// Update module registry
		echo "
			<p class=\"EJ_instText\">
			&gt; Updating Module Registry...
			</p>";
		$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}modules (moduleid, version, name, creator) VALUES
			('".get_class()."', '{$this->version}', '{$this->name}', '{$this->creator}')
			ON DUPLICATE KEY UPDATE moduleid = moduleid");
		echo "
			<p class=\"EJ_instText\">
			&gt; Install Successful!
			</p>";
		return true;
	}
	
	function update()
	{
		echo "
			<p class=\"EJ_instText\">
			&gt; EJ Adverts MT Update Procedure
			</p>";
		switch ($this->vars['oldversion'])
		{
			default:
			break;
		}
		echo "
			<p class=\"EJ_instText\">
			&gt; Updating Module Registry...
			</p>";
		$this->EJ_mysql->query("UPDATE {$this->EJ_mysql->prefix}modules SET version = '{$this->version}' WHERE moduleid = '".get_class($this)."'");
		echo "
			<p class=\"EJ_instText\">
			&gt; Update to Version {$this->version} Successful!
			</p>";
	}
	
	function uninstall()
	{
		echo "
			<p class=\"EJ_instText\">
			&gt; Checking and Removing Tables...
			</p>";
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt");
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_settings");
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats");
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts");
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs");
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits");
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_credits");
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_adverts_mt_prices");
		echo "
			<p class=\"EJ_instText\">
			&gt; Removing User Permissions...
			</p>";
		$this->EJ_mysql->query("SHOW COLUMNS FROM {$this->EJ_mysql->prefix}users LIKE 'perm_EJ_adverts_mt'");
		if ($this->EJ_mysql->numRows()!=0)
		{
			$this->EJ_mysql->query("ALTER TABLE {$this->EJ_mysql->prefix}users DROP perm_EJ_adverts_mt, DROP EJ_adverts_mt_credits");
		}
		echo "
			<p class=\"EJ_instText\">
			&gt; Updating Module Registry...
			</p>";
		$this->EJ_mysql->query("DELETE FROM {$this->EJ_mysql->prefix}modules WHERE moduleid = '".get_class()."'");
		echo "
			<p class=\"EJ_instText\">
			&gt; Uninstall Successful...
			</p>";
		return true;
	}
	
	function admin_page()
	{
		$content = "";
		$content .= '<a class="button" style="background-image: url('.$this->moduleloc.'add_icon.png)" href="./?module=EJ_adverts_mt&action=addadvert">Add Advert</a><a class="button" style="background-image: url('.$this->moduleloc.'search_icon.png)" href="./?module=EJ_adverts_mt&action=search">Advert Search</a><a class="button" style="background-image: url('.$this->moduleloc.'cats_icon.png)" href="./?module=EJ_adverts_mt&action=cats">Categories</a><a class="button" style="background-image: url('.$this->moduleloc.'atts_icon.png)" href="./?module=EJ_adverts_mt&action=atts">Attributes</a><a class="button" style="background-image: url('.$this->moduleloc.'locs_icon.png)" href="./?module=EJ_adverts_mt&action=locs">Locations</a>';
		echo $content;
	}
	
	function search()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_adverts_mt&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to adverts" title="Back to adverts" style="border:0;" /></a></div>';
		$content .= '<h2><img src="'.$this->moduleloc.'search_icon_small.png" alt="Advert Filter" /> Advert Filter</h2>';
		$results = array();
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE 1=1";
		$advertdate = time();
		$adverttext = "";
		$advertcat = "";
		$advertposter = "";
		$anycheck = ' checked="checked"';
		if (!empty($this->vars['search']))
		{
			$s1 = explode(":::",$this->vars['search']);
			foreach ($s1 as $s2)
			{
				$s2 = explode("::", $s2);
				$search[$s2[0]] = $s2[1];
			}
			foreach ($search as $key => $value)
			{
				$skip = 0;
				$vtype = 0;
				switch ($key)
				{
					case 'EJ_advertDate' :
						$anycheck = "";
						$advertdate = strtotime($value);
						$skip=1;
						$query .= " AND EJ_advertDate <= '".date("Y-m-d",$advertdate)."'";
					break;
					case 'EJ_advertText' :
						$skip=1;
						$words = explode(" ", $value);
						$query .= " AND (";
						foreach($words as $word)
						{
							if (!empty($word))
							{
								$query .= "(EJ_advertText LIKE '%$word%' OR EJ_advertTitle LIKE '%$word%') AND ";
							}
						}
						$query = substr($query, 0, -5).")";
						$adverttext = " value=\"$value\"";
					break;
					case 'EJ_advertCat' :
						$skip=1;
						$query .= " AND $key LIKE '%($value)%'";
						$advertcat = $value;
					break;
					case 'EJ_advertAttributes' :
						$advertatts = explode(":",$value);
						$skip = 1;
						$query .= " AND (";
						$i=0;
						foreach ($advertatts as $att)
						{
							if ($i != 0)
								$query .= " OR ";
							else
								$i=1;
							$query .= "$key LIKE '%($att)%'";
						}
						$query .= ")";
					break;
					case 'EJ_advertLoc' :
						$advertlocs = explode(":",$value);
						$skip = 1;
						$query .= " AND (";
						$i=0;
						foreach ($advertlocs as $loc)
						{
							if ($i != 0)
								$query .= " OR ";
							else
								$i=1;
							$query .= "$key LIKE '%($loc)%'";
						}
						$query .= ")";
					break;
					case 'EJ_advertPoster' :
						$advertposter = $value;
					break;
				}
				if (!is_numeric($value)) $value = "'".$value."'";
				if ($skip==0) $query .= " AND $key = $value";
			}
		}
		$query .= " ORDER BY ";
		switch ($this->vars['order'])
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
			default:
				$query .= "EJ_advertTitle ASC";
			break;
		}
		if (isset($this->vars['items']))
		{
			$items = $this->vars['items'];
		} else
		{
			$items = 10;
		}
		if (isset($this->vars['page']))
			$limitstart = (($this->vars['page']-1)*$items).",";
		else 
			$limitstart = "";
		$query .= " LIMIT ".$limitstart.$items;
		$this->EJ_mysql->query("SELECT *, (SELECT catName FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats as t1 WHERE t1.catId = t2.subCatOf) as parent FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats as t2 ORDER BY parent ASC, catName ASC");
		$categories = "";
		while ($cat = $this->EJ_mysql->getRow())
		{
			$selected = "";
			if ($advertcat == $cat['catId']) $selected = ' selected="selected"';
			$categories .= "<option value=\"{$cat['catId']}\"$selected>{$cat['parent']}&gt;{$cat['catName']}</option>\n						";
		}
		$this->EJ_mysql->query("SELECT EJ_advertPoster FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt GROUP BY EJ_advertPoster ORDER BY EJ_advertPoster");
		$posters = "";
		while ($post = $this->EJ_mysql->getRow())
		{
			$selected = "";
			if ($advertposter == $post['EJ_advertPoster']) $selected = ' selected="selected"';
			$posters .= "<option value=\"{$post['EJ_advertPoster']}\"$selected>{$post['EJ_advertPoster']}</option>\n						";
		}
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs ORDER BY locName");
		$locations = "";
		while ($loc = $this->EJ_mysql->getRow())
		{
			
			if (isset($search['EJ_advertLoc']))
			{
				$checked[$loc['locId']] = "";
			} else
			{
				$checked[$loc['locId']] = " checked=\"checked\"";
			}
			if (!empty($advertlocs))
			{
				foreach ($advertlocs as $adloc)
				{
					$selected = "";
					if ($adloc == $loc['locId']) $checked[$loc['locId']] = ' checked="checked"';
				}
			}
			$locations .= "<input type=\"checkbox\" name=\"loc\" id=\"loc{$loc['locId']}\" value=\"{$loc['locId']}\"{$checked[$loc['locId']]} onchange=\"updateFilter('{$_SESSION['key']}')\"/> <label for=\"loc{$loc['locId']}\">{$loc['locName']}</label>\n						";
		}
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts ORDER BY attName");
		$attributes = "";
		while ($att = $this->EJ_mysql->getRow())
		{
			
			if (isset($search['EJ_advertAttributes']))
			{
				$checked[$att['attId']] = "";
			} else
			{
				$checked[$att['attId']] = " checked=\"checked\"";
			}
			if (!empty($advertatts))
			{
				foreach ($advertatts as $adatt)
				{
					$selected = "";
					if ($adatt == $att['attId']) $checked[$att['attId']] = ' checked="checked"';
				}
			}
			$attributes .= "<input type=\"checkbox\" name=\"att\" id=\"att{$att['attId']}\" value=\"{$att['attId']}\"{$checked[$att['attId']]} onchange=\"updateFilter('{$_SESSION['key']}')\"/> <label for=\"att{$att['attId']}\">{$att['attName']}</label>\n						";
		}
		$content .= '
			<script src="'.$this->moduleloc.'EJ_adverts_mt.js" language="javascript" type="text/javascript"></script>
			<script src="'.$this->moduleloc.'calendar.js" language="javascript" type="text/javascript"></script>
			<form name="search_form" id="search_form" method="post" action="./?module=EJ_adverts_mt&action=search&search=go">
				<div style="float:right;">
					<strong>Include Hidden</strong>:	<input type="checkbox" name="hidden" id="hidden" onchange="updateFilter(\''.$_SESSION['key'].'\')" checked="checked" /><br/>
					Show
					<select name="limit" id="limit"onchange="updateFilter(\''.$_SESSION['key'].'\')">
						<option value="10" selected="selected">10</option>
						<option value="20">20</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
					Results
				</div>
				<div style="float:left;">
					<strong>Expired</strong>: Any Date <input type="checkbox" name="anydate" id="anydate" value="true"'.$anycheck.' onchange="updateFilter(\''.$_SESSION['key'].'\')"/><script>DateInput(\'date\', true, \'DD-MON-YYYY\', \''.date("d-M-Y", $advertdate).'\' , \''.$_SESSION['key'].'\');</script>
				</div>
				<div style="float:left; margin-right: 10px;">
					<strong>Title/Text Search</strong>:<br/>
					<input type="text" name="search_text" id="search_text" onkeyup="updateFilter(\''.$_SESSION['key'].'\')"'.$adverttext.' />
				</div>
				<div style="float:left; margin-right: 10px;">
					<strong>Category</strong>:<br/>
					<select name="category" id="category" onchange="updateFilter(\''.$_SESSION['key'].'\')" />
						<option value="0">Any Category</option>
						'.$categories.'
					</select>
				</div>
				<div style="float:left; margin-right: 10px;">
					<strong>Posted By</strong>:<br/>
					<select name="poster" id="poster" onchange="updateFilter(\''.$_SESSION['key'].'\')" >
					<option value="0">Any Poster</option>
					'.$posters.'
					</select>
				</div>
				<div style="clear: left; float:left; margin-right: 10px;">
					<strong>Locations</strong>:<br/>
					'.$locations.'
				</div>
				<div style="clear: left; float:left; margin-right: 10px;">
					<strong>Attributes</strong>:<br/>
					'.$attributes.'
				</div>
				<div style="clear:both;"></div>
			</form>
			';
		ob_start();
		$this->EJ_mysql->query($query);
		$content .= ob_get_contents();
		ob_end_clean();
		$result_count = $this->EJ_mysql->numRows();
		$content .= '<h2><div style="float:right; margin-right: 5px;">Results Found: <span id="result_count">';
		$content2 = '</span></div><img src="'.$this->moduleloc.'search_icon_small.png" alt="Search Results" /> Search Results (click result to show/hide details)</h2>
			<div id="advert_message"></div>
			<div id="search_results">';
		$sorts = array('TitleD'=>'Title (Z-A)', 'PosterA'=>'Posted By (A-Z)', 'PosterD'=>'Posted By (Z-A)', 'ExpiryA'=>'Expiring Soonest', 'ExpiryD'=>'Expiring Latest');
		$content3 .= "<div style=\"float: left;\"><strong>Sort By:</strong> <select name=\"order\" id=\"order\" onchange=\"updateFilter('{$_SESSION['key']}')\">";
		$content3 .= "<option value=\"TitleA\" selected=\"selected\">Title (A-Z)</option>";
		foreach ($sorts as $sortitem => $sortname)
		{
			if ($this->vars['order']==$sortitem)
				$selected=" selected=\"selected\"";
			else
				$selected="";
			$content3 .= "<option value=\"$sortitem\"$selected>$sortname</option>";
		}
		$content3 .= "</select></div><div style=\"clear:both;\"></div></div>";
		while ($advert = $this->EJ_mysql->getRow())
		{
			$date = date("d/m/Y", strtotime($advert['EJ_advertDate']));
			if ($advert['EJ_advertDate'] < date("Y-m-d", time()))
				$date = "<span style=\"color:#F00\">".$date."</span>";
			if (empty($advert['EJ_advertImages']) or !file_exists($this->moduleloc."images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}"))
			{
				$img = "noimage.png";
			} else
			{
				$img = "{$advert['EJ_advertId']}/".$advert['EJ_advertImages'];
			}
			$content3 .= "
				<div class=\"advert_result\" id=\"{$advert['EJ_advertId']}\">
					<div style=\"float: right;\"><img src=\"".$this->moduleloc."recycle.png\" alt=\"delete\" title=\"Delete advert\" style=\"cursor: pointer; border: 0;\" onclick=\"deleteadvert('{$advert['EJ_advertId']}', '{$_SESSION['key']}')\" /> <a href=\"?module=EJ_adverts_mt&action=editadvert&advertid={$advert['EJ_advertId']}\"><img src=\"".$this->moduleloc."edit.png\" alt=\"edit\" title=\"Edit advert\" style=\"cursor: pointer; border: 0;\" /></a> <img src=\"".$this->moduleloc."blue_down.png\" alt=\"show/hide details\" title=\"Show/Hide Details\"style=\"cursor: pointer;\" onclick=\"Slide(this.parentNode.parentNode, 16, 150)\" /></div>
					<p><strong>{$advert['EJ_advertTitle']}</strong> posted by: {$advert['EJ_advertPoster']} - <strong>Renewal:</strong> $date</p>
					<p><img src=\"{$this->moduleloc}images/$img\" alt=\"{$advert['EJ_advertTitle']}\" class=\"advertImage\" />{$advert['EJ_advertText']}</p>
				</div>";
		}
		$content3 .= '
			</div>
';
		$this->EJ_mysql->query("SELECT FOUND_ROWS() as results");
		$result_count = $this->EJ_mysql->getRow();
		$pages = "<div style=\"margin: 10px 10px 0 10px;\"><div style=\"float:right;\">Page ";
		$pages .= "<select name=\"page\" id=\"page\" onchange=\"updateFilter('{$_SESSION['key']}')\">";
		$pages .= "<option value=\"1\" selected=\"selected\">1</option>";
		for ($i=2; $i<=ceil($result_count['results']/$items); $i++)
		{
			if ($this->vars['page'] == $i)
				$selected = " selected=\"selected\"";
			else
				$selected = "";
			$pages .= "<option value=\"$i\"$selected>$i</option>";
		}
		$pages .= "</select>";
		$pages .= " of ".ceil($result_count['results']/$items)."</div>";
		echo $content.$result_count['results'].$content2.$pages.$content3;
	}
	
	function addadvert()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_adverts_mt&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Adverts" title="Back to Adverts" style="border:0;" /></a></div>
				<h2><img src="'.$this->moduleloc.'add_icon_small.png" alt="Add Advert" /> Add Advert</h2>';
		$content .= '
				<script src="'.$this->moduleloc.'EJ_adverts_mt.js" language="javascript" type="text/javascript"></script>
				<script src="'.$this->moduleloc.'addcalendar.js" language="javascript" type="text/javascript"></script>
				<div id="addadvert">
					<form name="add_form" id="add_form" action="?module=EJ_adverts_mt&action=addadvert" method="post">
						<div id="addLeft">
							Click Image To Change<br/>
							<img id="advertimage" src="'.$this->moduleloc.'images/noimage.png" alt="Add An Image" title="Click to Add an Image" onclick="changepic(\''.$_SESSION['key'].'\')" style="width:200px; height:200px;" /><br/>
							<input type="hidden" name="image" id="image" />
							<input type="button" name="save" id="save" value="Save Changes" onclick="saveadvert(\''.$_SESSION['key'].'\')"/><br/>
							<input type="button" name="cancel" id="cancel" value="Cancel Changes" onclick="cancel_ad(\''.$_SESSION['key'].'\')"/>';
						$content .='<br/><br/>
							<strong>Posted By:</strong><br/>
							<select name="poster" id="poster">';
						if ($_SESSION['usertype']==9)
						{
							$usertype = 10;
						} else
						{
							$usertype = $_SESSION['usertype'];
						}
						$this->EJ_mysql->query("SELECT userid FROM {$this->EJ_mysql->prefix}users WHERE type < ".$usertype." OR userid = '".$_SESSION['userid']."'");
						while ($user = $this->EJ_mysql->getRow())
						{
							if ($user['userid']==$_SESSION['userid'])
							{
								$selected = " selected=\"selected\"";
							} else
							{
								$selected = "";
							}
							$content .= '
									<option value="'.$user['userid'].'"'.$selected.'>'.$user['userid'].'</option>';
						}
						$content .= '
							</select><br/>
							<strong>Visibility:</strong><br/>
							<select name="hidden" id="hidden">
								<option value="1" selected="selected">Hidden</option>
								<option value="0">Visible</option>
							</select>
							<div id="advert_message"></div>';
						$content .='	
						</div>
						<div id="addRight">
							<strong>Advert Title:</strong><br/><input type="text" name="title" id="title" maxlength="100" size="40" /><br/>
							<strong>Tag Line:</strong><br/><input type="text" name="tag" id="tag" maxlength="150" size="40" /><br/>
							<strong>Advert Description:</strong><br/>
							<textarea name="desc" id="desc" rows="5" cols="40" /></textarea><br/>
							<strong>Categories:</strong><br/>';
		$this->EJ_mysql->query("SELECT catId, subCatOf, catName, (SELECT catName FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats cats2 WHERE cats2.catId = cats1.subCatOf) AS parent FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats cats1 ORDER BY parent ASC, catName ASC");
		$parent="";
		while ($cat = $this->EJ_mysql->getRow())
		{
			if ($cat['parent']!=$parent)
			{
				$parent = $cat['parent'];
				$content .= "<br/>
							<em>".$parent."</em>&gt;<br/>";
			}
			$content .= "
							<span style=\"width: 240px; display:inline-block;\"><input type=\"checkbox\" name=\"cat\" id=\"cat{$cat['catId']}\" value=\"{$cat['catId']}\" /> <label for=\"cat{$cat['catId']}\">{$cat['catName']}</label></span>";
			/*if (!empty($cat['parent']))
			{
			$content .= '
								<option value="'.$cat['catId'].'">'.$cat['parent'].'&gt;'.$cat['catName'].' ('.$cat['subCatOf'].'&gt;'.$cat['catId'].')</option>';
			} else
			{
				$content .= '
								<option value="'.$cat['catId'].'">'.$cat['catName'].' ('.$cat['catId'].')</option>';
			}*/
		}
		$content .= '
							<br/><br/>
							<strong>Locations:</strong><br/>';
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs");
		while ($loc = $this->EJ_mysql->getRow())
		{
			$content .= "
							<span style=\"width: 240px; display:inline-block;\"><input type=\"checkbox\" name=\"loc\" id=\"loc{$loc['locId']}\" value=\"{$loc['locId']}\" /> <label for=\"loc{$loc['locId']}\">{$loc['locName']}</label></span>";
		}
		$content .= '<br/><br/>
							<strong>Attributes:</strong><br/>';
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts");
		while ($att = $this->EJ_mysql->getRow())
		{
			$content .= "
							<span style=\"width: 240px; display:inline-block;\"><input type=\"checkbox\" name=\"att\" id=\"att{$att['attId']}\" value=\"{$att['attId']}\" /> <label for=\"att{$att['attId']}\">{$att['attName']}</label></span>";
		}
		$content .= '<br/><br/><strong>Advertiser Address:</strong><br/><input type="text" name="address1" id="address1" maxlength="150" size="40" /><br/><input type="text" name="address2" id="address2" maxlength="150" size="40" /><br/><input type="text" name="address3" id="address3" maxlength="150" size="40" /><br/><input type="text" name="address4" id="address4" maxlength="150" size="40" /><br/><strong>Post Code:</strong><br/><input type="text" name="address5" id="address5" maxlength="100" size="40" /><br/>
							<strong>Advertiser Phone:</strong><br/><input type="text" name="phone" id="phone" maxlength="15" size="15" /><br/>
							<strong>Contact Email:</strong><br/><input type="text" name="contact" id="contact" maxlength="150" size="40" /><br/>
							<strong>Advertiser Website:</strong> (incl. http://)<br/><input type="text" name="website" id="website" maxlength="150" size="40" />';
		$content .= '<br/><br/>
							<strong>Advert Renewal Date:</strong><br/>
							<script>DateInput(\'date\', true, \'DD-MON-YYYY\', \''.date("d-M-Y").'\' , \''.$_SESSION['key'].'\');</script>';
		$content .= '
							<br/><strong>Extra Info:</strong><br/><textarea name="extra" id="extra" rows="5" cols="40"></textarea>
							<br/><br/><strong>Tried and Tested:</strong> <input type="checkbox" name="tried" id="tried" value="true" />
						</div>
						<div style="clear: left;"></div>
					</form>
				</div>';
		echo $content;
	}
	
	function editadvert()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_adverts_mt&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to adverts" title="Back to adverts" style="border:0;" /></a></div>
				<h2><img src="'.$this->moduleloc.'edit.png" alt="Edit advert"/> Edit advert</h2>';
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertId = ".$this->vars['advertid']);
		if ($this->EJ_mysql->numRows()!=1)
		{
			$content .= '
				<div class="EJ_user_error"><strong>ERROR</strong>: advert Id Not Found!<br/>Please try again.</div>';
		} else
		{
			$advert = $this->EJ_mysql->getRow();
			if (empty($advert['EJ_advertImages']) or !file_exists($this->moduleloc."images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}")) 
				$img = "noimage.png"; 
			else 
				$img = "{$advert['EJ_advertId']}/".$advert['EJ_advertImages'];
			$content .= '
				<script src="'.$this->moduleloc.'EJ_adverts_mt.js" language="javascript" type="text/javascript"></script>
				<script src="'.$this->moduleloc.'addcalendar.js" language="javascript" type="text/javascript"></script>
				<div id="addadvert">
					<form name="add_form" id="add_form" action="?module=EJ_adverts_mt&action=editadvert" method="post">
						<div id="addLeft">
							Click Image To Change<br/>
							<img id="advertimage" src="'.$this->moduleloc.'images/'.$img.'" alt="Change Image" title="Click to Change Image" onclick="changepic('.$advert['EJ_advertId'].')"  style="width:200px; height:200px;" /><br/>
							<input type="hidden" name="image" id="image" value="'.$advert['EJ_advertImages'].'" />
							<input type="button" name="save" id="save" value="Save Changes" onclick="saveadvert(\''.$_SESSION['key'].'\','.$this->vars['advertid'].')"/><br/>
							<input type="button" name="cancel" id="cancel" value="Cancel Changes" onclick="document.location=\'?module=EJ_adverts_mt&action=admin_page\'"/>';
						$content .= '<br/><br/>
							<strong>Posted By:</strong><br/>
							<select name="poster" id="poster">';
			if ($_SESSION['usertype']==9)
			{
				$usertype = 10;
			} else
			{
				$usertype = $_SESSION['usertype'];
			}
			$this->EJ_mysql->query("SELECT userid FROM {$this->EJ_mysql->prefix}users WHERE type < ".$usertype."");
			while ($user = $this->EJ_mysql->getRow())
			{
				if ($user['userid']==$advert['EJ_advertPoster'])
				{
					$selected = " selected=\"selected\"";
				} else
				{
					$selected = "";
				}
				$content .= '
								<option value="'.$user['userid'].'"'.$selected.'>'.$user['userid'].'</option>';
			}
			if ($advert['EJ_advertHidden']==0)
			{
				$selectedvisible = " selected=\"selected\"";
				$selectedhidden = "";
			} else
			{
				$selectedvisible = "";
				$selectedhidden = " selected=\"selected\"";
			}
			$this->EJ_mysql->query("SELECT hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$this->vars['advertid']}' and hitMonth = '".date("my")."'");
			if ($this->EJ_mysql->numRows() == 0)
			{
				$hits[0] = 0;
			}
			else
			{
				$adhits = $this->EJ_mysql->getRow();
				$hits[0] = $adhits['hits'];
			}
			$this->EJ_mysql->query("SELECT hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$this->vars['advertid']}' and hitMonth = '".date("my", strtotime('-1 Month'))."'");
			if ($this->EJ_mysql->numRows() == 0)
			{
				$hits[1] = 0;
			}
			else
			{
				$adhits = $this->EJ_mysql->getRow();
				$hits[1] = $adhits['hits'];
			}
			$this->EJ_mysql->query("SELECT hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$this->vars['advertid']}' and hitMonth = '".date("my", strtotime('-2 Month'))."'");
			if ($this->EJ_mysql->numRows() == 0)
			{
				$hits[2] = 0;
			}
			else
			{
				$adhits = $this->EJ_mysql->getRow();
				$hits[2] = $adhits['hits'];
			}
			$this->EJ_mysql->query("SELECT SUM(hits) as hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$this->vars['advertid']}' GROUP BY adId");
			if ($this->EJ_mysql->numRows() == 0)
			{
				$hits[3] = 0;
			}
			else
			{
				$adhits = $this->EJ_mysql->getRow();
				$hits[3] = $adhits['hits'];
			}
			$hittxt = '
							<p style="margin-top: 5px;">Hits this month: '.$hits[0].'<br/>
							Hits last month: '.$hits[1].'<br/>
							Previous Month: '.$hits[2].'<br/>
							All Time: '.$hits[3].'</p>';
			$content .= '
							</select><br/>
							<strong>Visibility:</strong><br/>
							<select name="hidden" id="hidden">
								<option value="1"'.$selectedhidden.'>Hidden</option>
								<option value="0"'.$selectedvisible.'>Visible</option>
							</select>
							'.$hittxt.'
							<div id="advert_message"></div>
						</div>
						<div id="addRight">
							<strong>Advert Title:</strong><br/><input type="text" name="title" id="title" maxlength="100" size="40" value="'.str_replace('"',"&quot;", $advert['EJ_advertTitle']).'" /><br/>
							<strong>Tag Line:</strong><br/><input type="text" name="tag" id="tag" maxlength="150" size="40" value="'.str_replace('"',"&quot;", $advert['EJ_advertTag']).'" /><br/>
							<strong>advert Description:</strong><br/>
							<textarea name="desc" id="desc" rows="5" cols="40" />'.str_replace(array("<br/>","<br />"), "\n", $advert['EJ_advertText']).'</textarea><br/>
							<strong>Categories:</strong><br/>';
			$this->EJ_mysql->query("SELECT catId, subCatOf, catName, (SELECT catName FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats cats2 WHERE cats2.catId = cats1.subCatOf) AS parent FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats cats1 ORDER BY parent ASC, catName ASC");
			$cats = explode(":", $advert['EJ_advertCat']);
			$parent = "";
			while ($cat = $this->EJ_mysql->getRow())
			{
				if ($cat['parent']!=$parent)
				{
					$parent = $cat['parent'];
					$content .= "<br/>
								<em>".$parent."</em>&gt;<br/>";
				}
				$checked = "";
				foreach ($cats as $c)
				{
					if ($c == "(".$cat['catId'].")")
					{
						$checked = " checked=\"checked\"";
					}
				}
				$content .= "
								<span style=\"width: 240px; display:inline-block;\"><input type=\"checkbox\" name=\"cat\" id=\"cat{$cat['catId']}\" value=\"{$cat['catId']}\"$checked /> <label for=\"cat{$cat['catId']}\">{$cat['catName']}</label></span>";
			}
			/*while ($cat = $this->EJ_mysql->getRow())
			{
				if ($advert['EJ_advertCat']==$cat['catId'])
				{
					$selected = " selected=\"selected\"";
				} else
				{
					$selected = "";
				}
				if (!empty($cat['parent']))
				{
				$content .= '
								<option value="'.$cat['catId'].'"'.$selected.'>'.$cat['parent'].'&gt;'.$cat['catName'].' ('.$cat['subCatOf'].'&gt;'.$cat['catId'].')</option>';
				} else
				{
					$content .= '
								<option value="'.$cat['catId'].'"'.$selected.'>'.$cat['catName'].' ('.$cat['catId'].')</option>';
				}
			}
			*/
			$content .= '
							<br/><br/>
							<strong>Locations:</strong><br/>';
			$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs");
			$locs = explode(":", $advert['EJ_advertLoc']);
			while ($loc = $this->EJ_mysql->getRow())
			{
				$checked = "";
				foreach ($locs as $l)
				{
					if ($l == "(".$loc['locId'].")")
					{
						$checked = " checked=\"checked\"";
					}
				}
				$content .= "
								<span style=\"width: 240px; display:inline-block;\"><input type=\"checkbox\" name=\"loc\" id=\"loc{$loc['locId']}\" value=\"{$loc['locId']}\"$checked /> <label for=\"loc{$loc['locId']}\">{$loc['locName']}</label></span>";
			}
			$content .= '<br/><br/>
								<strong>Attributes:</strong><br/>';
			$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts");
			$atts = explode(":", $advert['EJ_advertAttributes']);
			while ($att = $this->EJ_mysql->getRow())
			{
				$checked = "";
				foreach ($atts as $a)
				{
					if ($a == "(".$att['attId'].")")
					{
						$checked = " checked=\"checked\"";
					}
				}
				$content .= "
								<span style=\"width: 240px; display:inline-block;\"><input type=\"checkbox\" name=\"att\" id=\"att{$att['attId']}\" value=\"{$att['attId']}\"$checked /> <label for=\"att{$att['attId']}\">{$att['attName']}</label></span>";
			}
			$content .= '<br/><br/><strong>Advertiser Address:</strong><br/><input type="text" name="address1" id="address1" maxlength="150" size="40" value="'.$advert['EJ_advertAddress1'].'" /><br/><input type="text" name="address2" id="address2" maxlength="150" size="40" value="'.$advert['EJ_advertAddress2'].'" /><br/><input type="text" name="address3" id="address3" maxlength="150" size="40" value="'.$advert['EJ_advertAddress3'].'" /><br/><input type="text" name="address4" id="address4" maxlength="150" size="40" value="'.$advert['EJ_advertAddress4'].'" /><br/><strong>Post Code:</strong><br/><input type="text" name="address5" id="address5" maxlength="100" size="40" value="'.$advert['EJ_advertAddress5'].'" /><br/>
								<strong>Advertiser Phone:</strong><br/><input type="text" name="phone" id="phone" maxlength="15" size="15" value="'.$advert['EJ_advertPhone'].'" /><br/>
								<strong>Contact Email:</strong><br/><input type="text" name="contact" id="contact" maxlength="150" size="40" value="'.$advert['EJ_advertContact'].'" /><br/>
								<strong>Advertiser Website:</strong> (incl. http://)<br/><input type="text" name="website" id="website" maxlength="150" size="40" value="'.$advert['EJ_advertWebsite'].'" />';
			$content .= '<br/><br/>
							<strong>Advert Renewal Date:</strong><br/>
							<script>DateInput(\'date\', true, \'DD-MON-YYYY\', \''.date("d-M-Y", strtotime($advert['EJ_advertDate'])).'\' , \''.$_SESSION['key'].'\');</script>';
			if ($advert['EJ_advertTried']==1)
			{
				$checked = ' checked="checked"';
			} else
			{
				$checked = "";
			}
			$content .= '
							<br/><strong>Extra Info:</strong><br/><textarea name="extra" id="extra" rows="5" cols="40">'.$advert['EJ_advertExtra'].'</textarea>							
							<br/><br/><strong>Tried and Tested:</strong> <input type="checkbox" name="tried" id="tried" value="true"'.$checked.' />
							<br/><br/><strong>Preview Advert:</strong> <a href="../?module=EJ_adverts_mt&action=show_advert&adId='.$advert['EJ_advertId'].'&draft=true" target="_blank" style="color:#F00">Click Here</a> (opens in new window)
						</div>
						<div style="clear: left;"></div>
					</form>';
		}
		$content .= '
				</div>';
		echo $content;
	}
	
	function cats()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_adverts_mt&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to adverts" title="Back to adverts" style="border:0;" /></a></div>
				<h2><img src="'.$this->moduleloc.'cats_icon_small.png" alt="Categories" /> Categories</h2>
				<script src="'.$this->moduleloc.'EJ_adverts_mt.js" language="javascript" type="text/javascript"></script>';
		$this->EJ_mysql->query("SELECT *,(SELECT COUNT(*) FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertCat = catId) AS adverts FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats ORDER BY subCatOf ASC, catName ASC");
		while ($cat = $this->EJ_mysql->getRow())
		{
			$cats[$cat['catId']] = $cat;
		}
		foreach ($cats as $cat)
		{
			$count = 0;
			$content .= '<div id="advert_message"></div>';
			if (empty($cat['subCatOf']))
			{
				$content .= "<div class=\"cat_result\" id=\"{$cat['catId']}\">";
				foreach ($cats as $subcat)
				{
					if ($subcat['subCatOf'] == $cat['catId'])
					{
						$count += $subcat['adverts'];
						$subcats[$subcat['catId']] = $subcat;
					}
				}
				$content .= "<div style=\"float: right;\">";
				if (count($subcats)==0 and $cat['adverts'] == 0)
				{
					$content .= "<img src=\"".$this->moduleloc."recycle.png\" alt=\"delete\" title=\"Delete Category\" style=\"cursor: pointer; border: 0;\" onclick=\"deleteCat('{$cat['catId']}','{$_SESSION['key']}')\" /> ";
				}
				$content .= "<a href=\"?module=EJ_adverts_mt&action=editcat&catid={$cat['catId']}\"><img src=\"".$this->moduleloc."edit.png\" alt=\"edit\" title=\"Edit Category\" style=\"cursor: pointer; border: 0;\" /></a>";
				if (count($subcats)!=0)
				{
					$content .= " <img src=\"".$this->moduleloc."blue_down.png\" alt=\"show/hide details\" title=\"Show/Hide Details\"style=\"cursor: pointer;\" onclick=\"Slide(this.parentNode.parentNode, 16, ".((count($subcats)+2)*17).")\" />";
				}
				$content .= "</div>";
				if (count($subcats)==0)
				{
					$content .= "<img class=\"advert_cat_img\"src=\"{$this->moduleloc}cat_no_sub.png\" alt=\"\" />";
				} else
				{
					$content .= "<img class=\"advert_cat_img\" src=\"{$this->moduleloc}cat_with_sub.png\" alt=\"\" style=\"cursor: pointer;\" onclick=\"Slide(this.parentNode, 16, ".((count($subcats)+2)*16).")\" />";
				}
				$content .= " {$cat['catName']} ({$cat['adverts']}";
				if ($count != 0)
				{
					$content .= " + $count in Sub-Categories";
				}
				$content .= ")";
				if ($cat['adverts']!=0) $content .= " <a href=\"?module=EJ_adverts_mt&action=search&search=EJ_advertCat::{$cat['catId']}\"><img src=\"".$this->moduleloc."search_icon_small.png\" alt=\"Find\" title=\"Show adverts in this category\" /></a>";
				$i = 1;
				if (count($subcats) != 0)
				{
					foreach($subcats as $subcat)
					{
						if ($i == count($subcats))
						{
							$content .= "<br/><img class=\"advert_cat_img\" src=\"{$this->moduleloc}sub_last.png\" alt=\"\" />";
						} else
						{
							$content .= "<br/><img class=\"advert_cat_img\" src=\"{$this->moduleloc}sub_middle.png\" alt=\"\" />";
						}
						$content .= " {$subcat['catName']} ({$subcat['adverts']})";
						if ($subcat['adverts']!=0) $content .= " <a href=\"?module=EJ_adverts_mt&action=search&search=EJ_advertCat::{$subcat['catId']}\"><img src=\"".$this->moduleloc."search_icon_small.png\" alt=\"Find\" title=\"Show adverts in this category\" /></a>";
						$content .= " <a href=\"?module=EJ_adverts_mt&action=editcat&catid={$subcat['catId']}\"><img src=\"".$this->moduleloc."edit.png\" alt=\"edit\" title=\"Edit Category\" style=\"cursor: pointer; border: 0;\" /></a>";
						if ($subcat['adverts']==0)
						{
							$content .= "<img src=\"".$this->moduleloc."recycle.png\" alt=\"delete\" title=\"Delete Category\" style=\"cursor: pointer; border: 0;\" onclick=\"deleteCat('{$subcat['catId']}','{$_SESSION['key']}')\" />";
						}
						$i++;
					}
				}
				$content .= "</div>";
				unset($subcats);
			}
		}
		$content .= "
		<div>
			<h2><img src=\"{$this->moduleloc}cats_icon_small.png\" alt=\"Categories\" /> Add New Category</h2>
			<form name=\"new_cat_form\" id=\"new_cat_form\" method=\"post\" action=\"#\" style=\"margin: 10px;\">
				<div style=\"float:left; margin-right: 5px;\">
					<strong>Category Name:</strong><br/>
					<input type=\"text\" name=\"new_name\" id=\"new_name\" maxlength=\"30\" />
				</div>
				<div style=\"float:left; margin-right: 5px;\">
					<strong>Sub-Category Of:</strong><br/>
					<select name=\"new_sub\" id=\"new_sub\">
						<option value=\"NONE\">None - Main Category</option>
						";
			foreach ($cats as $cat)
			{
				if (empty($cat['subCatOf'])) $content .= "<option value=\"{$cat['catId']}\">{$cat['catName']}</option>";
			}
			$content .= "
					</select>
				</div>
				<div style=\"float:left; margin-right: 5px;\">
					<strong>Description: (optional)</strong><br/>
					<textarea name=\"new_desc\" id=\"new_desc\" rows=\"3\" cols=\"40\" /></textarea>
				</div>
				<div style=\"float:left; margin-right: 5px;\">
					<input type=\"hidden\" name=\"catid\" id=\"catid\" value=\"\"/><input type=\"button\" name=\"save\" id=\"save\" value=\"Add Category\" onclick=\"addCat('{$_SESSION['key']}')\" style=\"margin-top: 15px; height: 52px; width: 150px;\" />
				</div>
				<div style=\"clear:left;\" id=\"new_cat_message\"></div>
			</form>
		</div>";
		echo $content;
	}
	
	function editcat()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_adverts_mt&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to adverts" title="Back to adverts" style="border:0;" /></a></div>
				<h2><img src="'.$this->moduleloc.'edit.png" alt="Edit Category" /> Edit Category</h2>';
		$this->EJ_mysql->query("SELECT * FROM ".$this->EJ_mysql->prefix."module_EJ_adverts_mt_cats WHERE catId = ".$this->vars['catid']);
		if ($this->EJ_mysql->numRows()!=1)
		{
			$content .= '
				<div class="EJ_user_error"><strong>ERROR</strong>: Category Id Not Found!<br/>Please try again.</div>';
		} else
		{
			$cat = $this->EJ_mysql->getRow();
			$selected = "";
			if (empty($cat['subCatOf'])) $selected = ' selected="selected"';
			$content .= "
				<script src=\"{$this->moduleloc}EJ_adverts_mt.js\" language=\"javascript\" type=\"text/javascript\"></script>
				<div>
					<form name=\"new_cat_form\" id=\"new_cat_form\" method=\"post\" action=\"#\" style=\"margin: 10px;\">
						<div style=\"float:left; margin-right: 5px;\">
							<strong>Category Name:</strong><br/>
							<input type=\"text\" name=\"new_name\" id=\"new_name\" maxlength=\"30\" value=\"{$cat['catName']}\" />
						</div>
						<div style=\"float:left; margin-right: 5px;\">
							<strong>Sub-Category Of:</strong><br/>
							<select name=\"new_sub\" id=\"new_sub\">
								<option value=\"NONE\"$selected>None - Main Category</option>
								";
					$this->EJ_mysql->query("SELECT * FROM ".$this->EJ_mysql->prefix."module_EJ_adverts_mt_cats WHERE (ISNULL(subCatOf) OR subCatOf = '') AND catId != ".$cat['catId']);
					while ($cat1 = $this->EJ_mysql->getRow())
					{
						$cats[$cat1['catId']] = $cat1;
					}
					if (isset($cats))
					{
						foreach ($cats as $cat1)
						{
							$selected = "";
							if ($cat1['catId'] == $cat['subCatOf']) $selected= ' selected="selected"';
							$content .= "<option value=\"{$cat1['catId']}\"$selected>{$cat1['catName']}</option>";
						}
					}
					$desc = nl2br($cat['catDesc']);
					$content .= "
							</select>
						</div>
						<div style=\"float:left; margin-right: 5px;\">
							<strong>Description: (optional)</strong><br/>
							<textarea name=\"new_desc\" id=\"new_desc\" rows=\"3\" cols=\"40\" />{$desc}</textarea>
						</div>
						<div style=\"float:left; margin-right: 5px;\">
							<input type=\"hidden\" name=\"catid\" id=\"catid\" value=\"{$cat['catId']}\"/><input type=\"button\" name=\"save\" id=\"save\" value=\"Save Changes\" onclick=\"addCat('{$_SESSION['key']}')\" style=\"margin-top: 15px; height: 52px; width: 150px;\" />
						</div>
						<div style=\"clear:left;\" id=\"new_cat_message\"></div>
					</form>";
		}
		$content .= '
				</div>';
		echo $content;
	}
	
	function atts()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_adverts_mt&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Adverts" title="Back to Adverts" style="border:0;" /></a></div>
				<h2><img src="'.$this->moduleloc.'atts_icon_small.png" alt="Attributes" /> Attributes</h2>
				<script src="'.$this->moduleloc.'EJ_adverts_mt.js" language="javascript" type="text/javascript"></script>';
		$this->EJ_mysql->query("SELECT *,(SELECT COUNT(*) FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertAttributes LIKE CONCAT('%(', attId, ')%')) AS adverts FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts ORDER BY attName ASC");
		while ($cat = $this->EJ_mysql->getRow())
		{
			$cats[$cat['attId']] = $cat;
		}
		foreach ($cats as $cat)
		{
			$count = 0;
			$content .= '<div id="advert_message"></div>';
			$content .= "<div class=\"cat_result\" id=\"{$cat['attId']}\">";
			$content .= "<div style=\"float: right;\">";
			if ($cat['adverts']==0)
			{
				$content .= "<img src=\"".$this->moduleloc."recycle.png\" alt=\"delete\" title=\"Delete Attribute\" style=\"cursor: pointer; border: 0;\" onclick=\"deleteAtt('{$cat['attId']}','{$_SESSION['key']}')\" /> ";
			}
			$content .= "<a href=\"?module=EJ_adverts_mt&action=editatt&attid={$cat['attId']}\"><img src=\"".$this->moduleloc."edit.png\" alt=\"edit\" title=\"Edit Attribute\" style=\"cursor: pointer; border: 0;\" /></a>";
			$content .= "</div>";
			$content .= "<img class=\"advert_cat_img\"src=\"{$this->moduleloc}att.png\" alt=\"\" />";
			$content .= " {$cat['attName']} ({$cat['adverts']}";
			$content .= ")";
			if ($cat['adverts']!=0) $content .= " <a href=\"?module=EJ_adverts_mt&action=search&search=EJ_advertAttributes::{$cat['attId']}\"><img src=\"".$this->moduleloc."search_icon_small.png\" alt=\"Find\" title=\"Show adverts with this attribute\" /></a>";
			$i = 1;
			$content .= "</div>";
		}
		$content .= "
		<div>
			<h2><img src=\"{$this->moduleloc}atts_icon_small.png\" alt=\"Attributes\" /> Add New Attribute</h2>
			<form name=\"new_att_form\" id=\"new_att_form\" method=\"post\" action=\"#\" style=\"margin: 10px;\">
				<div style=\"float:left; margin-right: 5px;\">
					<strong>Attribute Name:</strong><br/>
					<input type=\"text\" name=\"new_name\" id=\"new_name\" maxlength=\"30\" />
				</div>
				<div style=\"float:left; margin-right: 5px;\">
					<strong>Description: (optional)</strong><br/>
					<textarea name=\"new_desc\" id=\"new_desc\" rows=\"3\" cols=\"40\" /></textarea>
				</div>
				<div style=\"float:left; margin-right: 5px;\">
					<input type=\"hidden\" name=\"attid\" id=\"attid\" value=\"\"/><input type=\"button\" name=\"save\" id=\"save\" value=\"Add Attribute\" onclick=\"addAtt('{$_SESSION['key']}')\" style=\"margin-top: 15px; height: 52px; width: 150px;\" />
				</div>
				<div style=\"clear:left;\" id=\"new_cat_message\"></div>
			</form>
		</div>";
		echo $content;
	}
	
	function editatt()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_adverts_mt&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Adverts" title="Back to Adverts" style="border:0;" /></a></div>
				<h2><img src="'.$this->moduleloc.'edit.png" alt="Edit Attribute" /> Edit Attribute</h2>';
		$this->EJ_mysql->query("SELECT * FROM ".$this->EJ_mysql->prefix."module_EJ_adverts_mt_atts WHERE attId = ".$this->vars['attid']);
		if ($this->EJ_mysql->numRows()!=1)
		{
			$content .= '
				<div class="EJ_user_error"><strong>ERROR</strong>: Attribute Id Not Found!<br/>Please try again.</div>';
		} else
		{
			$cat = $this->EJ_mysql->getRow();
			$selected = "";
			$desc = nl2br($cat['attDesc']);
			$content .= "
				<script src=\"{$this->moduleloc}EJ_adverts_mt.js\" language=\"javascript\" type=\"text/javascript\"></script>
				<div>
					<form name=\"new_att_form\" id=\"new_att_form\" method=\"post\" action=\"#\" style=\"margin: 10px;\">
						<div style=\"float:left; margin-right: 5px;\">
							<strong>Attribute Name:</strong><br/>
							<input type=\"text\" name=\"new_name\" id=\"new_name\" maxlength=\"30\" value=\"{$cat['attName']}\" />
						</div>
						<div style=\"float:left; margin-right: 5px;\">
							<strong>Description: (optional)</strong><br/>
							<textarea name=\"new_desc\" id=\"new_desc\" rows=\"3\" cols=\"40\" />{$desc}</textarea>
						</div>
						<div style=\"float:left; margin-right: 5px;\">
							<input type=\"hidden\" name=\"attid\" id=\"attid\" value=\"{$cat['attId']}\"/><input type=\"button\" name=\"save\" id=\"save\" value=\"Save Changes\" onclick=\"addAtt('{$_SESSION['key']}')\" style=\"margin-top: 15px; height: 52px; width: 150px;\" />
						</div>
						<div style=\"clear:left;\" id=\"new_cat_message\"></div>
					</form>";
		}
		$content .= '
				</div>';
		echo $content;
	}
	
	function locs()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_adverts_mt&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Adverts" title="Back to Adverts" style="border:0;" /></a></div>
				<h2><img src="'.$this->moduleloc.'locs_icon_small.png" alt="Locations" /> Locations</h2>
				<script src="'.$this->moduleloc.'EJ_adverts_mt.js" language="javascript" type="text/javascript"></script>';
		$this->EJ_mysql->query("SELECT *,(SELECT COUNT(*) FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertLoc LIKE CONCAT('%(', locId, ')%')) AS adverts FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs ORDER BY locName ASC");
		while ($cat = $this->EJ_mysql->getRow())
		{
			$cats[$cat['locId']] = $cat;
		}
		foreach ($cats as $cat)
		{
			$count = 0;
			$content .= '<div id="advert_message"></div>';
			$content .= "<div class=\"cat_result\" id=\"{$cat['locId']}\">";
			$content .= "<div style=\"float: right;\">";
			if ($cat['adverts']==0)
			{
				$content .= "<img src=\"".$this->moduleloc."recycle.png\" alt=\"delete\" title=\"Delete Location\" style=\"cursor: pointer; border: 0;\" onclick=\"deleteLoc('{$cat['locId']}','{$_SESSION['key']}')\" /> ";
			}
			$content .= "<a href=\"?module=EJ_adverts_mt&action=editloc&locid={$cat['locId']}\"><img src=\"".$this->moduleloc."edit.png\" alt=\"edit\" title=\"Edit Location\" style=\"cursor: pointer; border: 0;\" /></a>";
			$content .= "</div>";
			$content .= "<img class=\"advert_cat_img\"src=\"{$this->moduleloc}loc.png\" alt=\"\" />";
			$content .= " {$cat['locName']} ({$cat['adverts']}";
			$content .= ")";
			if ($cat['adverts']!=0) $content .= " <a href=\"?module=EJ_adverts_mt&action=search&search=EJ_advertLoc::{$cat['locId']}\"><img src=\"".$this->moduleloc."search_icon_small.png\" alt=\"Find\" title=\"Show adverts in this location\" /></a>";
			$i = 1;
			$content .= "</div>";
		}
		$content .= "
		<div>
			<h2><img src=\"{$this->moduleloc}locs_icon_small.png\" alt=\"Locations\" /> Add New Location</h2>
			<form name=\"new_loc_form\" id=\"new_loc_form\" method=\"post\" action=\"#\" style=\"margin: 10px;\">
				<div style=\"float:left; margin-right: 5px;\">
					<strong>Location Name:</strong><br/>
					<input type=\"text\" name=\"new_name\" id=\"new_name\" maxlength=\"30\" />
				</div>
				<div style=\"float:left; margin-right: 5px;\">
					<input type=\"hidden\" name=\"locid\" id=\"locid\" value=\"\"/><input type=\"button\" name=\"save\" id=\"save\" value=\"Add Location\" onclick=\"addLoc('{$_SESSION['key']}')\" style=\"margin-top: 15px; height: 52px; width: 150px;\" />
				</div>
				<div style=\"clear:left;\" id=\"new_cat_message\"></div>
			</form>
		</div>";
		echo $content;
	}
	
	function editloc()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_adverts_mt&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Adverts" title="Back to Adverts" style="border:0;" /></a></div>
				<h2><img src="'.$this->moduleloc.'edit.png" alt="Edit Location" /> Edit Location</h2>';
		$this->EJ_mysql->query("SELECT * FROM ".$this->EJ_mysql->prefix."module_EJ_adverts_mt_locs WHERE locId = ".$this->vars['locid']);
		if ($this->EJ_mysql->numRows()!=1)
		{
			$content .= '
				<div class="EJ_user_error"><strong>ERROR</strong>: Location Id Not Found!<br/>Please try again.</div>';
		} else
		{
			$cat = $this->EJ_mysql->getRow();
			$selected = "";
			$desc = nl2br($cat['locDesc']);
			$content .= "
				<script src=\"{$this->moduleloc}EJ_adverts_mt.js\" language=\"javascript\" type=\"text/javascript\"></script>
				<div>
					<form name=\"new_loc_form\" id=\"new_loc_form\" method=\"post\" action=\"#\" style=\"margin: 10px;\">
						<div style=\"float:left; margin-right: 5px;\">
							<strong>Location Name:</strong><br/>
							<input type=\"text\" name=\"new_name\" id=\"new_name\" maxlength=\"30\" value=\"{$cat['locName']}\" />
						</div>
						<div style=\"float:left; margin-right: 5px;\">
							<input type=\"hidden\" name=\"locid\" id=\"locid\" value=\"{$cat['locId']}\"/><input type=\"button\" name=\"save\" id=\"save\" value=\"Save Changes\" onclick=\"addLoc('{$_SESSION['key']}')\" style=\"margin-top: 15px; height: 52px; width: 150px;\" />
						</div>
						<div style=\"clear:left;\" id=\"new_cat_message\"></div>
					</form>";
		}
		$content .= '
				</div>';
		echo $content;
	}
	
	function advert_categories($showcontent = true, $forceclass = NULL)
	{
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_".get_class($this)."_cats ORDER BY subCatOf, catName");
		$forceclass!=NULL ? $class = ' class="'.$forceclass.'"' : $class = "";
		if ($this->EJ_mysql->numRows()==0)
		{
			$content .= '
				<li'.$class.'> No Categories Found!</li>';
		} else
		{
			while ($cat = $this->EJ_mysql->getRow())
			{
				if (!empty($cat['subCatOf']))
				{
					$cats[$cat['subCatOf']]['subcats'][$cat['catId']] = $cat;
				}
				else
				{
					$cats[$cat['catId']]['thiscat'] = $cat;
				}
			}
			foreach ($cats as $cat)
			{
				$query = "SELECT DISTINCT EJ_advertId FROM {$this->EJ_mysql->prefix}module_".get_class($this)." WHERE EJ_advertHidden=0 AND (EJ_advertCat LIKE '%(".$cat['thiscat']['catId'].")%'";
				foreach ($cat['subcats'] as $subcat)
				{
					$query .= " OR EJ_advertCat LIKE '%(".$subcat['catId'].")%'";
				}
				$query .= ")";
				$this->EJ_mysql->query($query);
				$cats[$cat['thiscat']['catId']]['thiscat']['catCount'] += $this->EJ_mysql->numRows();
			}
			$count = 0;
			foreach ($cats as $cat)
			{
				$content .= '
					<li'.$class;
					if ($class=="") $content.=' style="float:left; margin-bottom: 10px; width: 23%;"';
					$content.= '><a href="?module='.get_class($this).'&action=show_ads&category='.$cat['thiscat']['catId'].'">'.$cat['thiscat']['catName'].'</a> ('.$cat['thiscat']['catCount'].')';
				$content .= '</li>';
			}
		}
		echo $content;
	}
	
	function search_box()
	{
		if (isset($_REQUEST['page']))
			$page = $_REQUEST['page'];
		else
			$page = 1;
		$filter .= "<div id=\"EJ_advertFilter\"><form name=\"advert_filter\" id=\"advert_filter\" method=\"get\" action=\"?gosearch=go\">";
		$filter .= "
				<input type=\"hidden\" name=\"module\" id=\"module\" value=\"EJ_adverts_mt\" />
				<input type=\"hidden\" name=\"action\" id=\"action\" value=\"show_ads\" />
				<input type=\"hidden\" name=\"page\" id=\"page\" value=\"".$page."\" />
				<p>
					<strong>Category:</strong><br/><select name=\"category\" id=\"category\">
						<option value=\"0\">Any Category</option>";
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats WHERE (SELECT COUNT(*) FROM {$this->EJ_mysql->prefix}module_".get_class($this)." WHERE EJ_advertCat LIKE CONCAT('%(',catId,')%') AND ISNULL(subCatOf) GROUP BY catId) != 0 ORDER BY catName");
		while ($cat = $this->EJ_mysql->getRow())
		{
			if ($this->vars['category']==$cat['catId'])
				$selected = " selected=\"selected\"";
			else
				$selected = "";
			$filter .= "
						<option value=\"{$cat['catId']}\"$selected>{$cat['catName']}</option>";
		}
		$filter .= "
					</select>
				</p>
				<p><strong>Location:</strong><br/>";
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs ORDER BY locName");
		$filter .= '<select name="loc[]" id="loc" onchange="updateAdvertFilter(\''.$_SESSION['key'].'\',\''.$this->EJ_settings['instloc'].'\')" style="width:190px;">';
		$filter .= '<option value="ANY" selected="selected">Any Location</option>';
		while ($loc = $this->EJ_mysql->getRow())
		{
			if (isset($search['EJ_advertLoc']) or (isset($this->vars['loc']) and count($this->vars['loc'])==1 and $this->vars['loc'][0]!=$loc['locId']))
			{
				$checked[$loc['locId']] = "";
			} elseif (!isset($this->vars['loc'])) 
			{
				$checked[$loc['locId']] = "";
			} else
			{
				if (count($this->vars['loc'])==1 and $this->vars['loc'][0]==$loc['locId'])
				{
					$checked[$loc['locId']] = " selected=\"selected\"";
				} elseif (count($this->vars['loc'])!=1)
				{
					foreach ($this->vars['loc'] as $locid)
					{
						if ($locid == $loc['locId'])
						{
							$checked[$loc['locId']] = " selected=\"selected\"";
						}
					}
				}
			}
			if (!empty($advertlocs))
			{
				foreach ($advertlocs as $adloc)
				{
					$selected = "";
					if ($adloc == $loc['locId']) $checked[$loc['locId']] = ' selected="selected"';
				}
			}
			$filter .= "<option value=\"{$loc['locId']}\"{$checked[$loc['locId']]}>{$loc['locName']}</option>";
		}
		$filter .= "</select></p>
				<p>
					<strong>Name/Text Search:</strong><input type=\"text\" name=\"search_text\" id=\"search_text\" value=\"{$this->vars['search_text']}\" />
				</p>";
				/*
		$filter .= "
				<strong>Location:</strong>";
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs ORDER BY locName");
		while ($loc = $this->EJ_mysql->getRow())
		{
			if (isset($search['EJ_advertLoc']) or (isset($_REQUEST['loc']) and $_REQUEST['loc']!=$loc['locId']))
			{
				$checked[$loc['locId']] = "";
			} else
			{
				$checked[$loc['locId']] = " checked=\"checked\"";
			}
			if (!empty($advertlocs))
			{
				foreach ($advertlocs as $adloc)
				{
					$selected = "";
					if ($adloc == $loc['locId']) $checked[$loc['locId']] = ' checked="checked"';
				}
			}
			$filter .= "<input type=\"checkbox\" name=\"loc\" id=\"loc{$loc['locId']}\" value=\"{$loc['locId']}\"{$checked[$loc['locId']]} /> <label for=\"loc{$loc['locId']}\">{$loc['locName']}</label>";
		}
		*/
		
		/*
		$filter .= "
		<strong>Attributes:</strong>";
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts ORDER BY attName");
		while ($att = $this->EJ_mysql->getRow())
		{
			if (isset($search['EJ_advertAttributes']))
			{
				$checked[$att['attId']] = "";
			} else
			{
				$checked[$att['attId']] = " checked=\"checked\"";
			}
			if (!empty($advertatts))
			{
				foreach ($advertatts as $adatt)
				{
					$selected = "";
					if ($adatt == $att['attId']) $checked[$att['attId']] = ' checked="checked"';
				}
			}
			$filter .= "<input type=\"checkbox\" name=\"att\" id=\"att{$att['attId']}\" value=\"{$att['attId']}\"{$checked[$att['attId']]} /> <label for=\"att{$att['attId']}\">{$att['attName']}</label>";
		}
		*/
		$filter .= "
				<p>
					<input type=\"submit\" name=\"search\" id=\"search\" value=\"Search\" />
				</p>
			</form></div>";
		echo $filter;
	}
	
	function show_ads()
	{
		if (isset($this->vars['page']))
			$page = $this->vars['page'];
		else
			$page = 1;
		$filter .= "<div id=\"EJ_advertFilter\"><div class=\"EJ_advertResult_header\">Search Filter</div><form name=\"advert_filter\" id=\"advert_filter\" method=\"post\" action=\"?module=EJ_adverts_mt&action=show_ads\">";
		$filter .= "
				<input type=\"hidden\" name=\"page\" id=\"page\" value=\"".$page."\" />
				<p>
					<strong>Category:</strong><select name=\"category\" id=\"category\" onchange=\"update_subcats(this.value,'{$this->EJ_settings['instloc']}', '{$_SESSION['key']}'); updateAdvertFilter('{$_SESSION['key']}','{$this->EJ_settings['instloc']}');\">
						<option value=\"0\">Any Category</option>";
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats WHERE (SELECT COUNT(*) FROM {$this->EJ_mysql->prefix}module_".get_class($this)." WHERE EJ_advertCat LIKE CONCAT('%(',catId,')%') AND ISNULL(subCatOf) GROUP BY catId) != 0 ORDER BY catName");
		$catfound=0;
		while ($cat = $this->EJ_mysql->getRow())
		{
			if ($this->vars['category']==$cat['catId'])
			{
				$selected = " selected=\"selected\"";
				$catfound=$cat['catId'];
			}
			else
				$selected = "";
			$filter .= "
						<option value=\"{$cat['catId']}\"$selected>{$cat['catName']}</option>";
		}
		$filter .= "
					</select>
				</p>
				<p id=\"subcat\" style=\"display:none;\">
					<strong>Sub-Category:</strong><select name=\"subcategory\" id=\"subcategory\" onchange=\"updateAdvertFilter('{$_SESSION['key']}','{$this->EJ_settings['instloc']}');\">
						<option value=\"0\">Any Sub-Category</option>";
		if (!empty($catfound))
		{
			$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats WHERE subCatOf = $catfound AND (SELECT COUNT(*) FROM {$this->EJ_mysql->prefix}module_".get_class($this)." WHERE EJ_advertCat LIKE CONCAT('%(',catId,')%')) !=0 ORDER BY catName");
			while ($cat = $this->EJ_mysql->getRow())
			{
				if ($this->vars['subcategory']==$cat['catId'])
					$selected = " selected=\"selected\"";
				else
					$selected = "";
				$filter .= "
							<option value=\"{$cat['catId']}\"$selected>{$cat['catName']}</option>";
			}
		}
		$filter .= "
					</select>
				</p>
				<script>
					document.getElementById('subcat').style.display = 'inline-block';
				</script>
				<p>
					<strong>Name/Text Search:</strong><input type=\"text\" name=\"search_text\" id=\"search_text\" value=\"{$this->vars['search_text']}\" onkeyup=\"updateAdvertFilter('{$_SESSION['key']}','{$this->EJ_settings['instloc']}');\" />
				</p>
				<p><strong>Location:</strong>";
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs ORDER BY locName");
		$filter .= '<select name="loc[]" id="loc" onchange="updateAdvertFilter(\''.$_SESSION['key'].'\',\''.$this->EJ_settings['instloc'].'\')" style="width:190px;">';
		$filter .= '<option value="ANY" selected="selected">Any Location</option>';
		while ($loc = $this->EJ_mysql->getRow())
		{
			if (isset($search['EJ_advertLoc']) or (isset($this->vars['loc']) and count($this->vars['loc'])==1 and $this->vars['loc'][0]!=$loc['locId']))
			{
				$checked[$loc['locId']] = "";
			} elseif (!isset($this->vars['loc'])) 
			{
				$checked[$loc['locId']] = "";
			} else
			{
				if (count($this->vars['loc'])==1 and $this->vars['loc'][0]==$loc['locId'])
				{
					$checked[$loc['locId']] = " selected=\"selected\"";
				} elseif (count($this->vars['loc'])!=1)
				{
					foreach ($this->vars['loc'] as $locid)
					{
						if ($locid == $loc['locId'])
						{
							$checked[$loc['locId']] = " selected=\"selected\"";
						}
					}
				}
			}
			if (!empty($advertlocs))
			{
				foreach ($advertlocs as $adloc)
				{
					$selected = "";
					if ($adloc == $loc['locId']) $checked[$loc['locId']] = ' selected="selected"';
				}
			}
			$filter .= "<option value=\"{$loc['locId']}\"{$checked[$loc['locId']]}>{$loc['locName']}</option>";
		}
		$filter .= "</select></p>
		<p><strong>Attribute:</strong>";
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts ORDER BY attName");
		$filter .= '<select name="att[]" id="att" onchange="updateAdvertFilter(\''.$_SESSION['key'].'\',\''.$this->EJ_settings['instloc'].'\')" style="width:190px;">';
		$filter .= '<option value="ANY" selected="selected">Any Attribute</option>';
		while ($att = $this->EJ_mysql->getRow())
		{
			if (isset($search['EJ_advertAttributes']) or (isset($this->vars['att']) and count($this->vars['att'])==1 and $this->vars['att'][0]!=$att['attId']))
			{
				$checked[$att['attId']] = "";
			} elseif (!isset($this->vars['att'])) 
			{
				$checked[$att['attId']] = "";
			} else
			{
				if (count($this->vars['att'])==1 and $this->vars['att'][0]==$att['attId'])
				{
					$checked[$att['attId']] = " selected=\"selected\"";
				} elseif (count($this->vars['att'])!=1)
				{
					foreach ($this->vars['att'] as $locid)
					{
						if ($attid == $att['attId'])
						{
							$checked[$att['attId']] = " selected=\"selected\"";
						}
					}
				}
			}
			if (!empty($advertatts))
			{
				foreach ($advertatts as $adatt)
				{
					$selected = "";
					if ($adatt == $att['attId']) $checked[$att['attId']] = ' selected="selected"';
				}
			}
			$filter .= "<option value=\"{$att['attId']}\"{$checked[$att['attId']]}>{$att['attName']}</option>";
		}
		$filter .= "</select></p>
				<noscript>
				<p>
					<input type=\"submit\" name=\"update\" id=\"update\" value=\"Update\" />
				</p>
				</noscript>
			</form></div>";
		$locfind = "SELECT locName FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs WHERE locID = SUBSTRING_INDEX(SUBSTR(EJ_advertLoc,2),')',1)";
		$query = "SELECT SQL_CALC_FOUND_ROWS *, (SELECT catName FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats WHERE catId = EJ_advertCat) as catName, ($locfind) as locName FROM {$this->EJ_mysql->prefix}module_".get_class($this)." WHERE EJ_advertHidden = 0";
		if (isset($this->vars['category']) and $this->vars['category']!="0" and empty($this->vars['subcategory']))
		{
			$this->EJ_mysql->query("SELECT catId FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats WHERE catId = {$this->vars['category']} OR subCatOf = {$this->vars['category']}");
			$query .= " AND (";
			while ($catid = $this->EJ_mysql->getRow())
			{
				$query .= "EJ_advertCat LIKE '%({$catid['catId']})%' OR ";
			}
			$query = substr($query,0,-4);
			$query .= ")";
		}
		if (isset($this->vars['subcategory']) and $this->vars['subcategory']!="0")
		{
			$this->EJ_mysql->query("SELECT catId FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats WHERE catId = {$this->vars['subcategory']}");
			$query .= " AND (";
			while ($catid = $this->EJ_mysql->getRow())
			{
				$query .= "EJ_advertCat LIKE '%({$catid['catId']})%' OR ";
			}
			$query = substr($query,0,-4);
			$query .= ")";
		}
		if (isset($this->vars['loc']) and $this->vars['loc'][0]!='ANY')
		{
			$query .= " and (";
			foreach ($this->vars['loc'] as $loc)
			{
				$query .= "EJ_advertLoc LIKE '%($loc)%' OR ";
			}
			$query = substr($query,0,-4);
			$query .= ")";
		}
		if (isset($this->vars['att']) and $this->vars['att'][0] != 'ANY')
		{
			$query .= " and (";
			foreach ($this->vars['att'] as $att)
			{
				$query .= "EJ_advertAttributes LIKE '%($att)%' OR ";
			}
			$query = substr($query,0,-4);
			$query .= ")";
		}
		if (!empty($this->vars['search_text']))
		{
			$words = explode(" ", $this->vars['search_text']);
			$query .= " AND (";
			foreach($words as $word)
			{
				if (!empty($word))
				{
					$query .= "(EJ_advertText LIKE '%$word%' OR EJ_advertTitle LIKE '%$word%') AND ";
				}
			}
			$query = substr($query, 0, -5).")";
			$adverttext = " value=\"$value\"";
		}
		$query .= " ORDER BY ";
		switch ($_REQUEST['order'])
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
		$query .= " LIMIT ".(($page-1)*10).", 10";
		$this->EJ_mysql->query($query);
		if ($this->EJ_mysql->numRows() == 0)
		{
			$content .= '<p style="text-align: center;"><strong>No Adverts Found!<br/>Please try a broader search filter.</strong></p>';
		} else
		{
			while($advert = $this->EJ_mysql->getRow())
			{
				if (strrpos($advert['EJ_advertLoc'],"(")!=0)
				{
					$advert['locName'] = "Multiple Locations";
				}
				if (!empty($advert['EJ_advertImages']) and file_exists(dirname(__FILE__)."/EJ_adverts_mt/images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}"))
				{
					$image = "<img class=\"EJ_advertResult_img\" src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}image.php/{$advert['EJ_advertImages']}?image={$this->EJ_settings['instloc']}{$this->moduleloc}images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}&amp;height=100&amp;width=100\" alt=\"{$advert['EJ_advertTitle']}\"/>";
				} else
				{
					$image = "<img class=\"EJ_advertResult_img\" src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}image.php/noimage.png?image={$this->EJ_settings['instloc']}{$this->moduleloc}images/noimage.png&amp;height=100&amp;width=100\" alt=\"{$advert['EJ_advertTitle']}\"/>";
				}
				if ($advert['EJ_advertTried']==1)
					$tried = " <a href=\"category/news\"><img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}tried.png\" style=\"vertical-align: middle; margin-bottom: 0.3em; border:0;\" /></a>";
				else
					$tried = "";
				$content .= "<div class=\"EJ_advertResult\" id=\"{$advert['EJ_advertId']}\"><div class=\"EJ_advertResult_header\"><a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">{$advert['EJ_advertTitle']}</a>$tried</div><div class=\"EJ_advertResult_left\"><a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">$image</a>".substr(str_replace(array("<br>","<br/>","<br />","\n","\r")," ", $advert['EJ_advertText']),0,150)."... <a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">more</a></div><div class=\"EJ_advertResult_right\">{$advert['locName']}<br/>{$advert['catName']}<br/>{$advert['EJ_advert']}</div><div style=\"clear: left;\"></div></div>";
			}
		}
		$this->EJ_mysql->query("SELECT FOUND_ROWS() as results");
		$result = $this->EJ_mysql->getRow();
		$results = "<div id=\"results_count\"><span id=\"result_count\" style=\"font-weight: bold; font-size: 1.5em;\">{$result['results']}</span> results match your filter</div>";
		$top = "
			<div id=\"search_results\">";
		for ($i=1; $i<=ceil($result['results']/10); $i++)
		{
			if ($page == $i)
			{
				$selected = "<strong>";
				$endselected = "</strong> | ";
				$pages .= $selected.$i.$endselected;
			}
			elseif (($i>($page-3) and $i<($page+3)) or $i == 1 or $i == ceil($result['results']/10))
			{
				if ($i == ceil($result['results']/10) and $i>($page+3)) {
					$startselected = ".. ";
					$pages = substr($pages,0,-2);
				} else {
					$startselected = "";
				}
				$selected = $startselected."<a href=\"javascript: setPage($i,'{$_SESSION['key']}','{$this->EJ_settings['instloc']}')\">";
				if ($i == 1 and $i<($page-3)) {
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
		$pages1 .= "
				<div id=\"pages\">Page ".$pages;
		$pages2 = "
				<div id=\"pages\" style=\"margin-top:0;\">Page ".$pages;
		echo $results.$filter.$top.$pages1.$content.$pages2."</div><div style=\"clear: both;\"></div>";
	}
	
	function show_advert()
	{
		if (!isset($_REQUEST['adId']))
		{
			$content .= '<p style="text-align: center;"><strong>Listing Not Found!<br/>Please go back and try again.</strong></p>';
		}
		else
		{
			$query = "SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertId = {$_REQUEST['adId']}";
			$this->EJ_mysql->query($query);
			if ($this->EJ_mysql->numRows() == 0)
			{
				$content .= '<p style="text-align: center;"><strong>Listing Not Found!<br/>Please go back and try again.</strong></p>';
			} else
			{
				$advert = $this->EJ_mysql->getRow();
				if ($advert['EJ_advertHidden']==1 and $this->vars['draft']!=true)
				{
					$content .= '<p style="text-align: center;"><strong>Listing Not Approved!<br/>Please go back and try again or contact us for more details.</strong></p>';
				}
				else
				{
					$this->EJ_mysql->query("SELECT hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$advert['EJ_advertId']}' and hitMonth = '".date("my")."'");
					if ($this->EJ_mysql->numRows() == 0)
					{
						$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits SET adId = '{$advert['EJ_advertId']}', hitMonth = '".date("my")."', hits = 1");
					}
					else
					{
						$this->EJ_mysql->query("UPDATE {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits SET hits = hits + 1 WHERE adId = '{$advert['EJ_advertId']}' and hitMonth = '".date("my")."'");
					}
					if ($advert['EJ_advertTried']==1)
					$tried = " <a href=\"category/news\"><img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}tried.png\" style=\"vertical-align: middle; margin-bottom: 0.3em; border:0;\" /></a>";
				else
					$tried = "";
					$content .= "<div class=\"EJ_advertResult_header\">{$advert['EJ_advertTitle']}$tried</div><div id=\"EJ_advertResult_right\"><div id=\"EJ_advertResult_address\"><strong>{$advert['EJ_advertTitle']}</strong><br/>{$advert['EJ_advertAddress1']}<br/>{$advert['EJ_advertAddress2']}";
					if (!empty($advert['EJ_advertAddress3'])) $content .= "<br/>{$advert['EJ_advertAddress3']}";
					if (!empty($advert['EJ_advertAddress4'])) $content .= "<br/>{$advert['EJ_advertAddress4']}";
					if (!empty($advert['EJ_advertAddress5'])) $content .= "<br/>{$advert['EJ_advertAddress5']}";
					$content .= "<br/>";
					if (!empty($advert['EJ_advertPhone'])) $content .= "<br/><img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}phone.png\" alt=\"Phone\"/> {$advert['EJ_advertPhone']}";
					if (!empty($advert['EJ_advertWebsite'])) $content .= "<br/><img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}globe.png\" alt=\"Website\"/> <a href=\"{$advert['EJ_advertWebsite']}\" target=\"_blank\">Visit Our Website</a>";
					if (!empty($advert['EJ_advertAddress5']))
					{
						$content .= '<iframe width="190" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.co.uk/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q='.str_replace(" ", "+", $advert['EJ_advertAddress5']).',United+Kindom&amp;aq=&amp;ie=UTF8&amp;hq=&amp;hnear='.str_replace(" ", "+", $advert['EJ_advertAddress5']).',United+Kingdom&amp;z=13&amp;output=embed&iwloc=null"></iframe><br /><small><a href="http://maps.google.co.uk/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q='.str_replace(" ", "+", $advert['EJ_advertAddress5']).',United+Kingdom&amp;aq=&amp;ie=UTF8&amp;hq=&amp;hnear='.str_replace(" ", "+", $advert['EJ_advertAddress5']).',United+Kingdom&amp;z=13" style="color:#0000FF;text-align:left">View Larger Map</a></small>';
					}
					$content .= "</div>";
					$content .= "<div class=\"EJ_advertResult_header\">Send Enquiry</div>";
					$formerror = "";
					if ($_REQUEST['enquiry']=='go')
					{
						if (empty($_REQUEST['EJ_enqName']) or empty($_REQUEST['EJ_enqEmail']) or empty($_REQUEST['EJ_enqDetails']))
						{
							$formerror = "<p style=\"color:#F00; font-size: 0.8em; padding: 5px;\">Please complete ALL fields.</p>";
						}
					}
					if ($_REQUEST['enquiry']!='go' or $formerror!="")
					{
						$form = "<form name=\"enquiry_form\" id=\"EJ_advertEnquiryForm\" action=\"?module=EJ_adverts_mt&action=show_advert&adId={$_REQUEST['adId']}&enquiry=go\" method=\"post\"><p style=\"font-size:0.8em\" >Please complete your details below and click \"Submit\" to send an enquiry to {$advert['EJ_advertTitle']}.</p>$formerror<div>Name:<input type=\"text\" name=\"EJ_enqName\" id=\"EJ_enqName\" value=\"{$_REQUEST['EJ_enqName']}\"/><br/>Email:<input type=\"text\" name=\"EJ_enqEmail\" id=\"EJ_enqEmail\" value=\"{$_REQUEST['EJ_enqEmail']}\"/><br/>Enquiry Details:<br/><textarea name=\"EJ_enqDetails\" id=\"EJ_enqDetails\" rows=\"4\">{$_REQUEST['EJ_enqDetails']}</textarea><input type=\"submit\" name=\"EJ_enqSubmit\" id=\"EJ_enqSubmit\" value=\"Submit\" style=\"width: 100%;\"/></div></form>";
					}
					else
					{
						$to = $advert['EJ_advertContact'];
						$subject = "Enquiry Received via ".$this->EJ_settings['sitename'];
						$from = $_POST['EJ_enqName']." <".$_POST['EJ_enqEmail'].">";
						$details = nl2br($_POST['EJ_enqDetails']);
						$bcc = $this->EJ_settings['siteemail'];
						$message="<html><p>You have received an enquiry from ".htmlentities($from)." via the {$this->EJ_settings['sitename']} website.</p><p><strong>Message:</strong><br/>$details</p></html>";
						$headers = "From: $from" . "\r\n" .
						"Reply-To: $from" . "\r\n" .
						"Bcc: $bcc" . "\r\n" .
						"Content-Type: text/html; charset=\"iso-8859-1\"" . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
						if (mail($to,$subject,$message,$headers))
						{
							$form = "<p style=\"padding: 5px;\"><strong style=\"font-size: 1.2em;\">Thank You!</strong> your enquiry has been sent to {$advert['EJ_advertTitle']}.</p>";
							$to = $_POST['EJ_enqEmail'];
							$from = $this->EJ_settings['sitename']." <".$this->EJ_settings['siteemail'].">";
							$message = "<html><p>Thank you for submitting an enquiry via the {$this->EJ_settings['sitename']} website.</p><p>This email is to confirm that your message has been successfully forwarded to {$advert['EJ_advertTitle']}.</p><p>Kind Regards</p><p>{$this->EJ_settings['sitename']}</p><hr/><p><strong>Your Message:</strong><br/>$details</p></html>";
							$headers = "From: $from" . "\r\n" .
							"Reply-To: $from" . "\r\n" .
							"Content-Type: text/html; charset=\"iso-8859-1\"" . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
							mail($to,$subject,$message,$headers);
						}
						else
						{
							$form = "<p style=\"padding: 5px;\"><strong style=\"font-size: 1.2em;\">Sorry!</strong><br/>There was an error sending your request. We are investigating the problem and will fix it as soon as possible. Please try again later or try an alternative contact method.</p>";
						}
					}
					$content .= $form."</div>";
					if (!empty($advert['EJ_advertExtra']))
					{
						$extra = "<div id=\"EJ_advertResult_extra\">".str_replace(array('£','%u2019'), array('&pound;',"'"), $advert['EJ_advertExtra'])."</div>";
					}
					$content .= "<div id=\"EJ_advertResult_left\"><div id=\"EJ_advertResult_mainLeft\"><div style=\"margin-bottom: 10px; font-weight: bold;\">{$advert['EJ_advertTag']}</div>".str_replace(array('£','%u2019'), array('&pound;',"'"), $advert['EJ_advertText']).$extra;
					$content .= "</div><div id=\"EJ_advertResult_mainRight\">";
					if (!empty($advert['EJ_advertImages']) and file_exists(dirname(__FILE__)."/EJ_adverts_mt/images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}"))
					{
						$image = "<img id=\"EJ_advertImage\" src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}image.php/{$advert['EJ_advertImages']}?image={$this->EJ_settings['instloc']}{$this->moduleloc}images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}&amp;height=298&amp;width=298\" alt=\"{$advert['EJ_advertTitle']}\"/>";
					} else
					{
						$image = "<img id=\"EJ_advertImage\" src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}image.php/noimage.png?image={$this->EJ_settings['instloc']}{$this->moduleloc}images/noimage.png&amp;height=298&amp;width=298\" alt=\"{$advert['EJ_advertTitle']}\"/>";
					}
					$content .= "<div id=\"EJ_advertResultImageHolder\">".$image."</div>";
					$imgdir = dirname(__FILE__)."/EJ_adverts_mt/images/{$advert['EJ_advertId']}/";
					if (is_dir($imgdir))
					{
						$dir = opendir($imgdir);
						$i=0;
						while(($file = readdir($dir)) !== false and $i < 6)
						{
							if (substr($file,-4)=='.jpg' or substr($file,-4)=='.gif' or substr($file,-4)=='.png' or substr($file,-4)=='.JPG' or substr($file,-4)=='.GIF' or substr($file,-4)=='.PNG')
							{
								if ($i==0)
									$content.= '<div style="display: table; margin: 5px auto; text-align: center;">';
								elseif ($i==3)
									$content.= '<br/>';
								$content .= '<div style="height:73px; width:98px; display:inline-block; line-height: 73px; border: #AAA 1px solid; text-align: center;"><img src="'.$this->EJ_settings['instloc'].$this->moduleloc.'image.php/'.$file.'?image='.$this->EJ_settings['instloc'].$this->moduleloc.'images/'.$advert['EJ_advertId'].'/'.$file.'&amp;height=73&amp;width=98" onmouseover="swap_image(\'EJ_advertImage\', this)" style="cursor:pointer; vertical-align: middle;" /></div>';
								$i++;
							}
						}
					}
					if ($i!=0)
					{
						$content.='</div>';
					}
					$content .= "<ul id=\"EJ_advertResult_atts\">";
					$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts ORDER BY attName");
					while ($att = $this->EJ_mysql->getRow())
					{
						$atts[$att['attId']] = $att;
						if (strpos($advert['EJ_advertAttributes'], "(".$att['attId'].")")!==false)
						{
							$atts[$att['attId']]['found'] = "yes";
						}
						else
						{
							$atts[$att['attId']]['found'] = "no";
						}
					}
					foreach ($atts as $att)
					{
						$content .= "<li><img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}att{$att['found']}.png\" alt=\"{$att['found']}\" /> {$att['attName']}</li>";
					}
					$content .= "<div style=\"clear: left;\"></div></ul>";
					$content .= "</div><div style=\"clear:both;\"></div></div><div style=\"clear:both;\"></div>";
				}
			}
		}
		echo $content;
	}
	
	function show_popular()
	{
		$this->EJ_mysql->query("SELECT adId, hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE (SELECT EJ_advertHidden FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertId = adId) != 1 GROUP BY adId ORDER BY SUM(hits) DESC LIMIT 5");
		$count = 0;
		while ($hit = $this->EJ_mysql->getRow())
		{
			$hits[] = $hit;
		}
		foreach ($hits as $hit)
		{
			$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertId = '{$hit['adId']}'");
			$advert = $this->EJ_mysql->getRow();
			if (strrpos($advert['EJ_advertLoc'],"(")!=0)
			{
				$advert['locName'] = "Multiple Locations";
			}
			if (!empty($advert['EJ_advertImages']) and file_exists(dirname(__FILE__)."/EJ_adverts_mt/images/{$advert['EJ_advertId']}/".$advert['EJ_advertImages']))
			{
				$image = "<img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}image.php/{$advert['EJ_advertImages']}?image={$this->EJ_settings['instloc']}{$this->moduleloc}images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}&amp;height=60&amp;width=80\" alt=\"{$advert['EJ_advertTitle']}\"/>";
			} else
			{
				$image = "<img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}image.php/noimage.png?image={$this->EJ_settings['instloc']}{$this->moduleloc}images/noimage.png&amp;height=60&amp;width=80\" alt=\"{$advert['EJ_advertTitle']}\"/>";
			}
			$content .= "<div class=\"EJ_advertPopular\" id=\"{$advert['EJ_advertId']}\"><div class=\"header\"><a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">{$advert['EJ_advertTitle']}</a></div><div style=\"float: left; margin-right: 5px;\"><div class=\"EJ_advertPopularImageHolder\"><a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">$image</a></div></div><p>".str_replace(array("<br />", "<br>","<br/>", "\n")," ", substr($advert['EJ_advertText'],0,150))."... <a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">more</a></p><div style=\"clear: left;\"></div></div>";
			$count ++;
		}
		if ($count < 5)
		{
			for ($i = $count; $i<5; $i++)
			{
				$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertHidden = 0 ORDER BY RAND()");
				$advert = $this->EJ_mysql->getRow();
				if (strrpos($advert['EJ_advertLoc'],"(")!=0)
				{
					$advert['locName'] = "Multiple Locations";
				}
				if (!empty($advert['EJ_advertImages']) and file_exists(dirname(__FILE__)."/EJ_adverts_mt/images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}"))
				{
					$image = "<img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}image.php/{$advert['EJ_advertImages']}?image={$this->EJ_settings['instloc']}{$this->moduleloc}images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}&amp;height=60&amp;width=80\" alt=\"{$advert['EJ_advertTitle']}\"/>";
				} else
				{
					$image = "<img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}image.php/noimage.png?image={$this->EJ_settings['instloc']}{$this->moduleloc}images/noimage.png&amp;height=60&amp;width=80\" alt=\"{$advert['EJ_advertTitle']}\"/>";
				}
				$content .= "<div class=\"EJ_advertPopular\" id=\"{$advert['EJ_advertId']}\"><div class=\"header\"><a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">{$advert['EJ_advertTitle']}</a></div><div style=\"float: left; margin-right: 5px;\"><div class=\"EJ_advertPopularImageHolder\"><a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">$image</a></div></div><p>".str_replace(array("<br />", "<br>","<br/>", "\n")," ", substr($advert['EJ_advertText'],0,150))."... <a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">more</a></p><div style=\"clear:left; \"></div></div>";
			}
		}
		echo $content;
	}
	
	function show_new()
	{
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertHidden = 0 ORDER BY EJ_advertId DESC LIMIT 5");
		while ($advert = $this->EJ_mysql->getRow())
		{
			if (strrpos($advert['EJ_advertLoc'],"(")!=0)
			{
				$advert['locName'] = "Multiple Locations";
			}
			if (!empty($advert['EJ_advertImages']) and file_exists(dirname(__FILE__)."/EJ_adverts_mt/images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}"))
			{
				$image = "<img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}image.php/{$advert['EJ_advertImages']}?image={$this->EJ_settings['instloc']}{$this->moduleloc}images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}&amp;height=60&amp;width=80\" alt=\"{$advert['EJ_advertTitle']}\"/>";
			} else
			{
				$image = "<img src=\"{$this->EJ_settings['instloc']}{$this->moduleloc}image.php/noimage.png?image={$this->EJ_settings['instloc']}{$this->moduleloc}images/noimage.png&amp;height=60&amp;width=80\" alt=\"{$advert['EJ_advertTitle']}\"/>";
			}
			$content .= "<div class=\"EJ_advertPopular\" id=\"{$advert['EJ_advertId']}\"><div class=\"header\"><a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">{$advert['EJ_advertTitle']}</a></div><div style=\"float: left; margin-right: 5px;\"><div class=\"EJ_advertPopularImageHolder\"><a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">$image</a></div></div><p>".str_replace(array('<br>','<br/>','<br />', "\n")," ",substr($advert['EJ_advertText'],0,150))."... <a href=\"?module=EJ_adverts_mt&action=show_advert&adId={$advert['EJ_advertId']}\">more</a></p><div style=\"clear: left;\"></div></div>";
		}
		echo $content;
	}
	
	function profile_page()
	{
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt WHERE EJ_advertPoster = '{$_SESSION['userid']}'");
		switch ($this->EJ_mysql->numRows())
		{
			case 0:
				$select = '';
				$content .= '
				<p>You have no active adverts!</p>';
			break;
			case 1:
				$select = '';
				$advert = $this->EJ_mysql->getRow();
				if (empty($advert['EJ_advertImages']) or !file_exists($this->moduleloc."images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}")) 
					$img = "noimage.png"; 
				else 
					$img = "{$advert['EJ_advertId']}/".$advert['EJ_advertImages'];
				$content .= '
					<script src="'.$this->moduleloc.'EJ_adverts_mt.js" language="javascript" type="text/javascript"></script>
					<link rel="stylesheet" href="modules/EJ_adverts_mt/styles.php" type="text/css" />
					<div id="addadvert">
						<form name="add_form" id="add_form" action="?module=EJ_adverts_mt&action=profile_page" method="post">
							<div id="addLeft">
								Click Image To Change<br/>
								<img id="advertimage" src="'.$this->moduleloc.'images/'.$img.'" alt="Change Image" title="Click to Change Image" onclick="changepic('.$advert['EJ_advertId'].')"  style="width:200px; height:200px;" /><br/>
								<input type="hidden" name="image" id="image" value="'.$advert['EJ_advertImages'].'" />
								<input type="button" name="save" id="save" value="Save Changes" onclick="saveadvertprofile(\''.$_SESSION['key'].'\','.$advert['EJ_advertId'].')"/><br/>
								<input type="button" name="cancel" id="cancel" value="Cancel Changes" onclick="document.location=\'profile.php\'"/>';
				$this->EJ_mysql->query("SELECT hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$advert['EJ_advertId']}' and hitMonth = '".date("my")."'");
				if ($this->EJ_mysql->numRows() == 0)
				{
					$hits[0] = 0;
				}
				else
				{
					$adhits = $this->EJ_mysql->getRow();
					$hits[0] = $adhits['hits'];
				}
				$this->EJ_mysql->query("SELECT hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$advert['EJ_advertId']}' and hitMonth = '".date("my", strtotime('-1 Month'))."'");
				if ($this->EJ_mysql->numRows() == 0)
				{
					$hits[1] = 0;
				}
				else
				{
					$adhits = $this->EJ_mysql->getRow();
					$hits[1] = $adhits['hits'];
				}
				$this->EJ_mysql->query("SELECT hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$this->vars['advertid']}' and hitMonth = '".date("my", strtotime('-2 Month'))."'");
			if ($this->EJ_mysql->numRows() == 0)
			{
				$hits[2] = 0;
			}
			else
			{
				$adhits = $this->EJ_mysql->getRow();
				$hits[2] = $adhits['hits'];
			}
			$this->EJ_mysql->query("SELECT SUM(hits) as hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$this->vars['advertid']}' GROUP BY adId");
			if ($this->EJ_mysql->numRows() == 0)
			{
				$hits[3] = 0;
			}
			else
			{
				$adhits = $this->EJ_mysql->getRow();
				$hits[3] = $adhits['hits'];
			}
			$hittxt = '
							<p style="margin-top: 5px;">Hits this month: '.$hits[0].'<br/>
							Hits last month: '.$hits[1].'<br/>
							Previous Month: '.$hits[2].'<br/>
							All Time: '.$hits[3].'</p>';
				$content .= '
								'.$hittxt.'<br/>
								<strong>Advert Renewal Date</strong><br/>'.date("d M Y", strtotime($advert['EJ_advertDate'])).'
								<div id="advert_message"></div>
							</div>
							<div id="addRight">
								<strong>Advert Title:</strong><br/><input type="text" name="title" id="title" maxlength="100" size="40" value="'.str_replace('"',"&quot;", $advert['EJ_advertTitle']).'" /><br/>
								<strong>Tag Line:</strong><br/><input type="text" name="tag" id="tag" maxlength="150" size="40" value="'.str_replace('"',"&quot;", $advert['EJ_advertTag']).'" /><br/>
								<strong>Advert Description:</strong><br/>
								<textarea name="desc" id="desc" rows="5" cols="40" />'.str_replace(array("<br/>","<br />"), "\n", $advert['EJ_advertText']).'</textarea><br/>
								<strong>Categories:</strong><br/>';
				$this->EJ_mysql->query("SELECT catId, subCatOf, catName, (SELECT catName FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats cats2 WHERE cats2.catId = cats1.subCatOf) AS parent FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats cats1 ORDER BY parent ASC, catName ASC");
				$cats = explode(":", $advert['EJ_advertCat']);
				$parent = "";
				while ($cat = $this->EJ_mysql->getRow())
				{
					if ($parent!=$cat['parent'])
					{
						$parent=$cat['parent'];
						$content.="<br/>
									<em>".$parent."</em>&gt;<br/>";
					}
					$checked = "";
					foreach ($cats as $c)
					{
						if ($c == "(".$cat['catId'].")")
						{
							$checked = " checked=\"checked\"";
						}
					}
					$content .= "
									<span style=\"width: 240px; display: inline-block;\"><input type=\"checkbox\" name=\"cat\" id=\"cat{$cat['catId']}\" value=\"{$cat['catId']}\"$checked /> <label for=\"cat{$cat['catId']}\">{$cat['catName']}</label></span>";
				}
				/*
				while ($cat = $this->EJ_mysql->getRow())
				{
					if ($advert['EJ_advertCat']==$cat['catId'])
					{
						$selected = " selected=\"selected\"";
					} else
					{
						$selected = "";
					}
					if (!empty($cat['parent']))
					{
					$content .= '
									<option value="'.$cat['catId'].'"'.$selected.'>'.$cat['parent'].'&gt;'.$cat['catName'].' ('.$cat['subCatOf'].'&gt;'.$cat['catId'].')</option>';
					} else
					{
						$content .= '
									<option value="'.$cat['catId'].'"'.$selected.'>'.$cat['catName'].' ('.$cat['catId'].')</option>';
					}
				}
				*/
				$content .= '
								</select><br/><br/>
								<strong>Locations:</strong><br/>';
				$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs");
				$locs = explode(":", $advert['EJ_advertLoc']);
				while ($loc = $this->EJ_mysql->getRow())
				{
					$checked = "";
					foreach ($locs as $l)
					{
						if ($l == "(".$loc['locId'].")")
						{
							$checked = " checked=\"checked\"";
						}
					}
					$content .= "
									<span style=\"width: 240px; display: inline-block;\"><input type=\"checkbox\" name=\"loc\" id=\"loc{$loc['locId']}\" value=\"{$loc['locId']}\"$checked /> <label for=\"loc{$loc['locId']}\">{$loc['locName']}</label></span>";
				}
				$content .= '<br/><br/>
									<strong>Attributes:</strong><br/>';
				$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts");
				$atts = explode(":", $advert['EJ_advertAttributes']);
				while ($att = $this->EJ_mysql->getRow())
				{
					$checked = "";
					foreach ($atts as $a)
					{
						if ($a == "(".$att['attId'].")")
						{
							$checked = " checked=\"checked\"";
						}
					}
					$content .= "
									<span style=\"width: 240px; display: inline-block;\"><input type=\"checkbox\" name=\"att\" id=\"att{$att['attId']}\" value=\"{$att['attId']}\"$checked /> <label for=\"att{$att['attId']}\">{$att['attName']}</label></span>";
				}
				$content .= '<br/><br/><strong>Advertiser Address:</strong><br/><input type="text" name="address1" id="address1" maxlength="150" size="40" value="'.$advert['EJ_advertAddress1'].'" /><br/><input type="text" name="address2" id="address2" maxlength="150" size="40" value="'.$advert['EJ_advertAddress2'].'" /><br/><input type="text" name="address3" id="address3" maxlength="150" size="40" value="'.$advert['EJ_advertAddress3'].'" /><br/><input type="text" name="address4" id="address4" maxlength="150" size="40" value="'.$advert['EJ_advertAddress4'].'" /><br/><input type="text" name="address5" id="address5" maxlength="100" size="40" value="'.$advert['EJ_advertAddress5'].'" /><br/>
									<strong>Advertiser Phone:</strong><br/><input type="text" name="phone" id="phone" maxlength="15" size="15" value="'.$advert['EJ_advertPhone'].'" /><br/>
									<strong>Contact Email:</strong><br/><input type="text" name="contact" id="contact" maxlength="150" size="40" value="'.$advert['EJ_advertContact'].'" /><br/>
									<strong>Advertiser Website:</strong> (incl. http://)<br/><input type="text" name="website" id="website" maxlength="150" size="40" value="'.$advert['EJ_advertWebsite'].'" />
									<br/><strong>Extra Info:</strong><br/><textarea name="extra" id="extra" rows="5" cols="40">'.$advert['EJ_advertExtra'].'</textarea>';
				$content .= '
							</div>
							<div style="clear: both;"></div>
						</form>';
				$content .= '
					</div>';
			break;
			default:
				$select = '
				<select onchange="document.location=\'?module=EJ_adverts_mt&advertid=\'+this.value" style="margin-bottom: 5px;">';
				$i=0;
				while ($ad = $this->EJ_mysql->getRow())
				{
					if ($i==0)
					{
						if (($_REQUEST['module']=='EJ_adverts_mt' and $_REQUEST['advertid']==$ad['EJ_advertId']) or !isset($_REQUEST['advertid']))
						{
							$advert=$ad;
							$i=1;
						}
					}
					if ($_REQUEST['advertid']==$ad['EJ_advertId'])
						$selected = ' selected="selected"';
					else
						$selected = '';
					$select .= '
					<option value="'.$ad['EJ_advertId'].'"'.$selected.'>'.$ad['EJ_advertId'].' : '.$ad['EJ_advertTitle'].'</option>';
				}
				$select .= '
				</select>';
				if (empty($advert['EJ_advertImages']) or !file_exists($this->moduleloc."images/{$advert['EJ_advertId']}/{$advert['EJ_advertImages']}")) 
					$img = "noimage.png"; 
				else 
					$img = "{$advert['EJ_advertId']}/".$advert['EJ_advertImages'];
				$content .= '
					<script src="'.$this->moduleloc.'EJ_adverts_mt.js" language="javascript" type="text/javascript"></script>
					<link rel="stylesheet" href="modules/EJ_adverts_mt/styles.php" type="text/css" />
					<div id="addadvert">
						<form name="add_form" id="add_form" action="?module=EJ_adverts_mt&action=editadvert" method="post">
							<div id="addLeft">
								Click Image To Change<br/>
								<img id="advertimage" src="'.$this->moduleloc.'images/'.$img.'" alt="Change Image" title="Click to Change Image" onclick="changepic('.$advert['EJ_advertId'].')"  style="width:200px; height:200px;" /><br/>
								<input type="hidden" name="image" id="image" value="'.$img.'" />
								<input type="button" name="save" id="save" value="Save Changes" onclick="saveadvertprofile(\''.$_SESSION['key'].'\','.$advert['EJ_advertId'].')"/><br/>
								<input type="button" name="cancel" id="cancel" value="Cancel Changes" onclick="document.location=\'profile.php\'"/>';
				$this->EJ_mysql->query("SELECT hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$advert['EJ_advertId']}' and hitMonth = '".date("my")."'");
				if ($this->EJ_mysql->numRows() == 0)
				{
					$hits[0] = 0;
				}
				else
				{
					$adhits = $this->EJ_mysql->getRow();
					$hits[0] = $adhits['hits'];
				}
				$this->EJ_mysql->query("SELECT hits FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_hits WHERE adId = '{$advert['EJ_advertId']}' and hitMonth = '".date("my", strtotime('-1 Month'))."'");
				if ($this->EJ_mysql->numRows() == 0)
				{
					$hits[1] = 0;
				}
				else
				{
					$adhits = $this->EJ_mysql->getRow();
					$hits[1] = $adhits['hits'];
				}
				$hittxt = '
								<p style="margin-top: 5px;">Hits this month: '.$hits[0].'<br/>
								Hits last month: '.$hits[1].'</p>';
				$content .= '
								'.$hittxt.'<br/>
								<strong>Advert Renewal Date</strong><br/>'.date("d M Y", strtotime($advert['EJ_advertDate'])).'
								<div id="advert_message"></div>
							</div>
							<div id="addRight">
								<strong>Advert Title:</strong><br/><input type="text" name="title" id="title" maxlength="100" size="40" value="'.$advert['EJ_advertTitle'].'" /><br/>
								<strong>Advert Description:</strong><br/>
								<textarea name="desc" id="desc" rows="5" cols="40" />'.str_replace(array("<br/>","<br />"), "\n", $advert['EJ_advertText']).'</textarea><br/>
								<strong>Category:</strong><br/>
								<select name="cat" id="cat">
									<option value="NONE" selected="selected">Please Select...</option>';
				$this->EJ_mysql->query("SELECT catId, subCatOf, catName, (SELECT catName FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats cats2 WHERE cats2.catId = cats1.subCatOf) AS parent FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_cats cats1 ORDER BY parent ASC, catName ASC");
				while ($cat = $this->EJ_mysql->getRow())
				{
					if ($advert['EJ_advertCat']==$cat['catId'])
					{
						$selected = " selected=\"selected\"";
					} else
					{
						$selected = "";
					}
					if (!empty($cat['parent']))
					{
					$content .= '
									<option value="'.$cat['catId'].'"'.$selected.'>'.$cat['parent'].'&gt;'.$cat['catName'].' ('.$cat['subCatOf'].'&gt;'.$cat['catId'].')</option>';
					} else
					{
						$content .= '
									<option value="'.$cat['catId'].'"'.$selected.'>'.$cat['catName'].' ('.$cat['catId'].')</option>';
					}
				}
				$content .= '
								</select><br/><br/>
								<strong>Locations:</strong><br/>';
				$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_locs");
				$locs = explode(":", $advert['EJ_advertLoc']);
				while ($loc = $this->EJ_mysql->getRow())
				{
					$checked = "";
					foreach ($locs as $l)
					{
						if ($l == "(".$loc['locId'].")")
						{
							$checked = " checked=\"checked\"";
						}
					}
					$content .= "
									<span style=\"width: 200px;\"><input type=\"checkbox\" name=\"loc\" id=\"loc{$loc['locId']}\" value=\"{$loc['locId']}\"$checked /> <label for=\"loc{$loc['locId']}\">{$loc['locName']}</label></span>";
				}
				$content .= '<br/><br/>
									<strong>Attributes:</strong><br/>';
				$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_adverts_mt_atts");
				$atts = explode(":", $advert['EJ_advertAttributes']);
				while ($att = $this->EJ_mysql->getRow())
				{
					$checked = "";
					foreach ($atts as $a)
					{
						if ($a == "(".$att['attId'].")")
						{
							$checked = " checked=\"checked\"";
						}
					}
					$content .= "
									<span style=\"width: 200px;\"><input type=\"checkbox\" name=\"att\" id=\"att{$att['attId']}\" value=\"{$att['attId']}\"$checked /> <label for=\"att{$att['attId']}\">{$att['attName']}</label></span>";
				}
				$content .= '<br/><br/><strong>Advertiser Address:</strong><br/><input type="text" name="address1" id="address1" maxlength="150" size="40" value="'.$advert['EJ_advertAddress1'].'" /><br/><input type="text" name="address2" id="address2" maxlength="150" size="40" value="'.$advert['EJ_advertAddress2'].'" /><br/><input type="text" name="address3" id="address3" maxlength="150" size="40" value="'.$advert['EJ_advertAddress3'].'" /><br/><input type="text" name="address4" id="address4" maxlength="150" size="40" value="'.$advert['EJ_advertAddress4'].'" /><br/><input type="text" name="address5" id="address5" maxlength="100" size="40" value="'.$advert['EJ_advertAddress5'].'" /><br/>
									<strong>Advertiser Phone:</strong><br/><input type="text" name="phone" id="phone" maxlength="15" size="15" value="'.$advert['EJ_advertPhone'].'" /><br/>
									<strong>Contact Email:</strong><br/><input type="text" name="contact" id="contact" maxlength="150" size="40" value="'.$advert['EJ_advertContact'].'" /><br/>
									<strong>Advertiser Website:</strong> (incl. http://)<br/><input type="text" name="website" id="website" maxlength="150" size="40" value="'.$advert['EJ_advertWebsite'].'" />
									<br/><br/><strong>Extra Info:</strong><br/><textarea name="extra" id="extra" rows="5" cols="40">'.$advert['EJ_advertExtra'].'</textarea>';
				$content .= '
							</div>
							<div style="clear: both;"></div>
						</form>';
				$content .= '
					</div>';
			break;
		}
		echo $select.$content;
	}
	
	function main_advert_categories()
	{
		$this->EJ_mysql->query("SELECT catId, catName FROM {$this->EJ_mysql->prefix}module_".get_class($this)."_cats WHERE ISNULL(subCatOf) ORDER BY catName");
		if ($this->EJ_mysql->numRows()==0)
		{
			$content .= '
				<li> No Categories Found!</li>';
		} else
		{
			while ($cat = $this->EJ_mysql->getRow())
			{
				$content .= '
					<li><a href="?module='.get_class($this).'&action=show_ads&category='.$cat['catId'].'">'.$cat['catName'].'</a></li>';
			}
		}
		echo $content;
	}
	
	function advert_locs()
	{
		$this->EJ_mysql->query("SELECT locId, locName FROM {$this->EJ_mysql->prefix}module_".get_class($this)."_locs ORDER BY locName");
		if ($this->EJ_mysql->numRows()==0)
		{
			$content .= '
				<li> No Locations Found!</li>';
		} else
		{
			while ($loc = $this->EJ_mysql->getRow())
			{
				$content .= '
					<li><a href="?module='.get_class($this).'&action=show_ads&loc[]='.$loc['locId'].'">'.$loc['locName'].'</a></li>';
			}
		}
		echo $content;
	}
	
	function advert_atts()
	{
		$this->EJ_mysql->query("SELECT attId, attName FROM {$this->EJ_mysql->prefix}module_".get_class($this)."_atts ORDER BY attName");
		if ($this->EJ_mysql->numRows()==0)
		{
			$content .= '
				<li> No Attributes Found!</li>';
		} else
		{
			while ($att = $this->EJ_mysql->getRow())
			{
				$content .= '
					<li><a href="?module='.get_class($this).'&action=show_ads&att[]='.$att['attId'].'">'.$att['attName'].'</a></li>';
			}
		}
		echo $content;
	}
}
} else
{
	EJ_error(41, basename(__FILE__));
}

?>