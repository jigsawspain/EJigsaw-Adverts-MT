/*
*** EJigsaw adverts Admin
**
*** By Jigsaw Spain - www.jigsawspain.com
**
*** JS/Ajax Functions - File Build 0.1
*/

/* Setup AJAX Functionality */
function ajaxRequest(){
	var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"]
	if (window.ActiveXObject){
		for (var i=0; i<activexmodes.length; i++){
			try{
				return new ActiveXObject(activexmodes[i])
			}
			catch(e){
			}
		}
	}
	else if (window.XMLHttpRequest)
		return new XMLHttpRequest()
	else
		return false
}


/**************************
** AJAX Filter Functions **
**************************/


function updateFilter(key)
{
	var form = document.getElementById('search_form');
	if (form.anydate.checked != 1)
	{
		var date = form.date.value;
	} else
	{
		var date = 0;
	}
	var text = form.search_text.value;
	var cat = form.category.value;
	var poster = form.poster.value;
	var limit = form.limit.value;
	var page = document.getElementById('page').value;
	var order = document.getElementById('order').value;
	if (form.hidden.checked != 1)
	{
		var hidden = 1;
	} else
	{
		var hidden = 0;
	}
	var attributes = "";
	if (empty(form.att.length))
	{
		if (form.att.checked==true)
		{
			attributes = form.att.value;
		}
	} else
	{
		for (i=0; i<form.att.length; i++)
		{
			if (form.att[i].checked==true)
				attributes = attributes + form.att[i].value + ":";
		}
		attributes = attributes.substr(0, attributes.length -1);
	}
	var locations = "";
	if (empty(form.loc.length))
	{
		if (form.loc.checked==true)
		{
			locations = form.loc.value;
		}
	} else
	{
		for (i=0; i<form.loc.length; i++)
		{
			if (form.loc[i].checked==true)
				locations = locations + form.loc[i].value + ":";
		}
		locations = locations.substr(0, locations.length -1);
	}
	var ajax = ajaxRequest();
	ajax.onreadystatechange = function()
	{
		if (ajax.readyState==4 && ajax.status==200)
		{
			var response = ajax.responseText.split(":::");
			document.getElementById("result_count").innerHTML = response[1];
			document.getElementById("search_results").innerHTML = response[0];
		}
	}
	ajax.open("POST","modules/EJ_adverts_mt/searchfilter.php",true);
	ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ajax.send("date="+date+"&text="+text+"&category="+cat+"&poster="+poster+"&hidden="+hidden+"&limit="+limit+"&page="+page+"&order="+order+"&attributes="+attributes+"&locations="+locations+"&key="+key);
}

function updateAdvertFilter(key,instloc, page)
{
	var form = document.getElementById('advert_filter');
	var text = form.search_text.value;
	var cat = form.category.value;
	var subcat = form.subcategory.value;
	if (empty(page))
		var page = 1;
	var attributes = "";
	if (form.att.value!="ANY")
	{
		attributes = "&attributes="+form.att.value;
	}
	var locations = "";
	if (form.loc.value!="ANY")
	{
		locations = "&locations="+form.loc.value;
	}
	var ajax = ajaxRequest();
	ajax.onreadystatechange = function()
	{
		if (ajax.readyState==4 && ajax.status==200)
		{
			var response = ajax.responseText.split(":::");
			document.getElementById("result_count").innerHTML = response[1];
			document.getElementById("search_results").innerHTML = response[0];
		}
	}
	ajax.open("POST",instloc+"modules/EJ_adverts_mt/advertfilter.php",true);
	ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	//ajax.send("date="+date+"&text="+text+"&cat="+cat+"&poster="+poster+"&hidden="+hidden+"&limit="+limit+"&page="+page+"&order="+order+"&attributes="+attributes+"&locations="+locations+"&key="+key);
	ajax.send("text="+text+"&cat="+cat+"&subcat="+subcat+"&page="+page+attributes+locations+"&key="+key);
}

function update_subcats(cat, instloc, key)
{
	var form = document.getElementById('advert_filter');
	form.subcategory.options[0].selected = 'selected';
	var ajax = ajaxRequest();
	ajax.onreadystatechange = function()
	{
		if (ajax.readyState==4 && ajax.status==200)
		{
			document.getElementById("subcategory").innerHTML = ajax.responseText;
		}
	}
	ajax.open("POST",instloc+"modules/EJ_adverts_mt/getsubcats.php",true);
	ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ajax.send("cat="+cat+"&key="+key);
}

function uncheckAny()
{
	var form = document.getElementById('search_form');
	form.anydate.checked = false;
}


/**********************
** General Functions **
***********************/


// Empty checker
function empty(mixed_var)
{
    var key;
    if (mixed_var === "" || mixed_var === 0 || mixed_var === "0" || mixed_var === null || mixed_var === false || typeof mixed_var === 'undefined') {
        return true;
    }
    if (typeof mixed_var == 'object') {
        for (key in mixed_var) {
            return false;
        }
        return true;
    }
    return false;
}

// DIV slider
var sliderIntervalId = 0;
var sliding = false;
var slideSpeed = 10;

function Slide(obj, start, finish)
{
	slideStart = start;
	slideStop = finish;
   if(sliding)
      return;
   sliding = true;
   if(parseInt(obj.style.height) == slideStop)
	{
      sliderIntervalId = setInterval('SlideUpRun(\''+obj.id+'\')', 30);
	}
   else
	{
		obj.style.height = slideStart + 'px';
      sliderIntervalId = setInterval('SlideDownRun(\''+obj.id+'\')', 30);
	}
}

function SlideUpRun(id)
{
   slider = document.getElementById(id);
   if(parseInt(slider.style.height) <= slideStart)
   {
      sliding = false;
      slider.style.height = slideStart + 'px';
      clearInterval(sliderIntervalId);
   }
   else
   {
      height = parseInt(slider.style.height);
		height -= slideSpeed;
		slider.style.height = height + 'px';
      if(parseInt(slider.style.height) < slideStart)
         slider.style.height = slideStart + 'px';
   }
}

function SlideDownRun(id)
{
	slider = document.getElementById(id);
   if(parseInt(slider.style.height) >= slideStop)
   {
      sliding = false;
      slider.style.height = slideStop + 'px';
      clearInterval(sliderIntervalId);
   }
   else
   {
		height = parseInt(slider.style.height);
		height += slideSpeed;
		slider.style.height = height + 'px';
      if(parseInt(slider.style.height) > slideStop)
         slider.style.height = slideStop + 'px';
   }
}

// Save Advert
function saveadvert(key, id)
{
	document.getElementById('advert_message').innerHTML = '';
	message = "";
	form = document.add_form;
	if (empty(form.title.value))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">'Title' cannot be empty!</p>";
	}
	if ((empty(form.cat.length) && empty(form.cat.checked)))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Category'!</p>";
	} else if (!empty(form.cat.length))
	{
		found=0;
		for (i=0; i<form.cat.length; i++)
		{
			if (form.cat[i].checked==true)
				found = 1;
		}
		if (found==0)
			message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Category'!</p>";
	}
	if (form.poster.value == 'NONE')
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select 'Posted By'!</p>";
	}
	if ((empty(form.loc.length) && empty(form.loc.checked)))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Location'!</p>";
	} else if (!empty(form.loc.length))
	{
		found=0;
		for (i=0; i<form.loc.length; i++)
		{
			if (form.loc[i].checked==true)
				found = 1;
		}
		if (found==0)
			message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Location'!</p>";
	}
	if ((empty(form.att.length) && empty(form.att.checked)))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Attribute'!</p>";
	} else if (!empty(form.att.length))
	{
		found=0;
		for (i=0; i<form.att.length; i++)
		{
			if (form.att[i].checked==true)
				found = 1;
		}
		if (found==0)
			message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Attribute'!</p>";
	}
	if (empty(form.contact.value))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">'Contact Email' cannot be empty!</p>";
	}
	if (message !="")
	{
		document.getElementById('advert_message').innerHTML = message;
	} else
	{
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="OK") {
					form.title.value = "";
					form.tag.value = "";
					form.desc.value = "";
					form.hidden.options[0].selected = true;
					form.image.value = "";
					form.address1.value = "";
					form.address2.value = "";
					form.address3.value = "";
					form.address4.value = "";
					form.address5.value = "";
					form.phone.value = "";
					form.contact.value = "";
					form.website.value = "";
					form.extra.value = "";
					form.tried.checked = false;
					document.getElementById('advertimage').src = 'modules/EJ_adverts_mt/images/noimage.png';
					document.location="?module=EJ_adverts_mt&action=search";
				} else {
					document.getElementById('advert_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_adverts_mt/saveadvert.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		title = form.title.value;
		tag = form.tag.value;
		desc = form.desc.value;
		date = form.date.value;
		poster = form.poster.value;
		hidden = form.hidden.value;
		address1 = form.address1.value;
		address2 = form.address2.value;
		address3 = form.address3.value;
		address4 = form.address4.value;
		address5 = form.address5.value;
		phone = form.phone.value;
		contact = form.contact.value;
		website = form.website.value;
		image = form.image.value;
		cats = "";
		if (empty(form.cat.length))
		{
			if (form.cat.checked == true)
			{
				cats = "(" + form.cat.value + ")";
			}
		} else
		{
			for (i=0; i<form.cat.length; i++)
			{
				if (form.cat[i].checked == true)
				{
					cats = cats + "(" + form.cat[i].value + "):";
				}
			}
			cats = cats.substr(0,cats.length -1);
		}
		locs = "";
		if (empty(form.loc.length))
		{
			if (form.loc.checked == true)
			{
				locs = "(" + form.loc.value + ")";
			}
		} else
		{
			for (i=0; i<form.loc.length; i++)
			{
				if (form.loc[i].checked == true)
				{
					locs = locs + "(" + form.loc[i].value + "):";
				}
			}
			locs = locs.substr(0,locs.length -1);
		}
		atts = "";
		if (empty(form.att.length))
		{
			if (form.att.checked == true)
			{
				atts = "(" + form.att.value + ")";
			}
		} else
		{
			for (i=0; i<form.att.length; i++)
			{
				if (form.att[i].checked == true)
				{
					atts = atts + "(" + form.att[i].value + "):";
				}
			}
			atts = atts.substr(0,atts.length -1);
		}
		if (!empty(form.extra.value))
		{
			extra = "&extra="+escape(form.extra.value);
		}
		else
		{
			extra = "";
		}
		if (form.tried.checked)
		{
			tried = "&tried=1";
		} else
		{
			tried = "&tried=0";
		}
		if (id)
		{
			sendid = "&id="+id;
		} else
		{
			sendid = "";
		}
		ajax.send("title="+escape(title)+"&tag="+escape(tag)+"&desc="+escape(desc)+"&cat="+cats+"&date="+date+"&poster="+poster+"&hidden="+hidden+"&address1="+address1+"&address2="+address2+"&address3="+address3+"&address4="+address4+"&address5="+address5+"&phone="+phone+"&contact="+contact+"&website="+website+"&image="+image+"&locs="+locs+"&atts="+atts+tried+extra+sendid+"&key="+key);
	}
}

function saveadvertprofile(key, id)
{
	document.getElementById('advert_message').innerHTML = '';
	message = "";
	form = document.add_form;
	if (empty(form.title.value))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">'Title' cannot be empty!</p>";
	}
	if ((empty(form.cat.length) && empty(form.cat.checked)))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Category'!</p>";
	} else if (!empty(form.cat.length))
	{
		found=0;
		for (i=0; i<form.cat.length; i++)
		{
			if (form.cat[i].checked==true)
				found = 1;
		}
		if (found==0)
			message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Category'!</p>";
	}
	if ((empty(form.loc.length) && empty(form.loc.checked)))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Location'!</p>";
	} else if (!empty(form.loc.length))
	{
		found=0;
		for (i=0; i<form.loc.length; i++)
		{
			if (form.loc[i].checked==true)
				found = 1;
		}
		if (found==0)
			message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Location'!</p>";
	}
	if ((empty(form.att.length) && empty(form.att.checked)))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Attribute'!</p>";
	} else if (!empty(form.att.length))
	{
		found=0;
		for (i=0; i<form.att.length; i++)
		{
			if (form.att[i].checked==true)
				found = 1;
		}
		if (found==0)
			message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select at least one 'Attribute'!</p>";
	}
	if (empty(form.contact.value))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">'Contact Email' cannot be empty!</p>";
	}
	if (message !="")
	{
		document.getElementById('advert_message').innerHTML = message;
	} else
	{
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="OK") {
					form.title.value = "";
					form.tag.value="";
					form.desc.value = "";
					form.image.value = "";
					form.address1.value = "";
					form.address2.value = "";
					form.address3.value = "";
					form.address4.value = "";
					form.address5.value = "";
					form.phone.value = "";
					form.contact.value = "";
					form.website.value = "";
					form.extra.value = "";
					document.getElementById('advertimage').src = 'modules/EJ_adverts_mt/images/noimage.png';
					document.location="?module=EJ_adverts_mt&advertid="+id;
				} else {
					document.getElementById('advert_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_adverts_mt/saveadvertprofile.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		title = form.title.value;
		tag = form.tag.value;
		desc = form.desc.value;
		address1 = form.address1.value;
		address2 = form.address2.value;
		address3 = form.address3.value;
		address4 = form.address4.value;
		address5 = form.address5.value;
		phone = form.phone.value;
		contact = form.contact.value;
		website = form.website.value;
		image = form.image.value;
		cats = "";
		if (empty(form.cat.length))
		{
			if (form.cat.checked == true)
			{
				cats = "(" + form.cat.value + ")";
			}
		} else
		{
			for (i=0; i<form.cat.length; i++)
			{
				if (form.cat[i].checked == true)
				{
					cats = cats + "(" + form.cat[i].value + "):";
				}
			}
			cats = cats.substr(0,cats.length -1);
		}
		locs = "";
		if (empty(form.loc.length))
		{
			if (form.loc.checked == true)
			{
				locs = "(" + form.loc.value + ")";
			}
		} else
		{
			for (i=0; i<form.loc.length; i++)
			{
				if (form.loc[i].checked == true)
				{
					locs = locs + "(" + form.loc[i].value + "):";
				}
			}
			locs = locs.substr(0,locs.length -1);
		}
		atts = "";
		if (empty(form.att.length))
		{
			if (form.att.checked == true)
			{
				atts = "(" + form.att.value + ")";
			}
		} else
		{
			for (i=0; i<form.att.length; i++)
			{
				if (form.att[i].checked == true)
				{
					atts = atts + "(" + form.att[i].value + "):";
				}
			}
			atts = atts.substr(0,atts.length -1);
		}
		if (!empty(form.extra.value))
		{
			extra = "&extra="+escape(form.extra.value);
		}
		else
		{
			extra = "";
		}
		ajax.send("title="+escape(title)+"&tag="+escape(tag)+"&desc="+escape(desc)+"&cat="+cats+"&address1="+address1+"&address2="+address2+"&address3="+address3+"&address4="+address4+"&address5="+address5+"&phone="+phone+"&contact="+contact+"&website="+website+"&image="+image+"&locs="+locs+"&atts="+atts+extra+"&id="+id+"&key="+key);
	}
}

function cancel_ad(key)
{
	ajax = ajaxRequest();
	ajax.onreadystatechange = function() {
		if (ajax.readyState==4 && ajax.status==200) {
			document.location='?module=EJ_adverts_mt&action=admin_page';
		}
	}
	ajax.open("POST","modules/EJ_adverts_mt/canceladvert.php",true);
	ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ajax.send("key="+key);
}

function clear_message(id)
{
	document.getElementById(id).innerHTML = "";
}

// Delete Advert
function deleteadvert(advertid,key)
{
	if (confirm('Are you sure you want to delete this advert?'))
	{
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="") {
					updateFilter(key);
					document.getElementById('advert_message').innerHTML='<p style="font-weight: bold; color: #090;">advert Deleted Successfully!</p>';
					document.getElementById('advert_message').style.height = '20px';
					setTimeout("Slide(document.getElementById('advert_message'), 0, 20)", 4000);
					setTimeout("clear_message('advert_message')", 3990);
				} else {
					document.getElementById('advert_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_adverts_mt/deleteadvert.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("id="+advertid+"&key="+key);
	}
}

// Add Attribute
function addAtt(key)
{
	document.getElementById('new_cat_message').innerHTML="";
	message="";
	form = document.getElementById('new_att_form');
	if (empty(form.new_name.value))
	{
		message = message + "<p class=\"EJ_user_error\">'Attribute Name' cannot be empty!</p>";
	}
	if (message=="")
	{
		attname = form.new_name.value;
		attdesc = form.new_desc.value;
		if (form.attid.value != "")
			attid = "&id="+form.attid.value;
		else
			attid = "";
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="") {
					document.location = '?module=EJ_adverts_mt&action=atts';
				} else {
					document.getElementById('new_cat_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_adverts_mt/addatt.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("attName="+escape(attname)+"&attDesc="+escape(attdesc)+attid+"&key="+key);
	} else
	{
		document.getElementById('new_cat_message').innerHTML=message;
	}
}

// Delete Attribute
function deleteAtt(attid,key)
{
	if (confirm('Are you sure you want to delete this attribute?'))
	{
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="") {
					document.location = '?module=EJ_adverts_mt&action=atts';
				} else {
					document.getElementById('advert_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_adverts_mt/deleteatt.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("id="+attid+"&key="+key);
	}
}

// Add Location
function addLoc(key)
{
	document.getElementById('new_cat_message').innerHTML="";
	message="";
	form = document.getElementById('new_loc_form');
	if (empty(form.new_name.value))
	{
		message = message + "<p class=\"EJ_user_error\">'Location Name' cannot be empty!</p>";
	}
	if (message=="")
	{
		locname = form.new_name.value;
		if (form.locid.value != "")
			locid = "&id="+form.locid.value;
		else
			locid = "";
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="") {
					document.location = '?module=EJ_adverts_mt&action=locs';
				} else {
					document.getElementById('new_cat_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_adverts_mt/addloc.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("locName="+escape(locname)+locid+"&key="+key);
	} else
	{
		document.getElementById('new_cat_message').innerHTML=message;
	}
}

// Delete Location
function deleteLoc(locid,key)
{
	if (confirm('Are you sure you want to delete this location?'))
	{
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="") {
					document.location = '?module=EJ_adverts_mt&action=locs';
				} else {
					document.getElementById('advert_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_adverts_mt/deleteloc.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("id="+locid+"&key="+key);
	}
}

// Add Category
function addCat(key)
{
	document.getElementById('new_cat_message').innerHTML="";
	message="";
	form = document.getElementById('new_cat_form');
	if (empty(form.new_name.value))
	{
		message = message + "<p class=\"EJ_user_error\">'Category Name' cannot be empty!</p>";
	}
	if (message=="")
	{
		catname = form.new_name.value;
		subcatof = form.new_sub.value;
		catdesc = form.new_desc.value;
		if (form.catid.value != "")
			catid = "&id="+form.catid.value;
		else
			catid = "";
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="") {
					document.location = '?module=EJ_adverts_mt&action=cats';
				} else {
					document.getElementById('new_cat_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_adverts_mt/addcat.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("catName="+escape(catname)+"&subCatOf="+subcatof+"&catDesc="+escape(catdesc)+catid+"&key="+key);
	} else
	{
		document.getElementById('new_cat_message').innerHTML=message;
	}
}

// Delete Category
function deleteCat(catid,key)
{
	if (confirm('Are you sure you want to delete this category?'))
	{
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="") {
					document.location = '?module=EJ_adverts_mt&action=cats';
				} else {
					document.getElementById('advert_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_adverts_mt/deletecat.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("id="+catid+"&key="+key);
	}
}

// Image Picker
function changepic(adid) {
    if ( !document.getElementById("picoverlay") ) {
        pic = document.createElement("div");
        pic.style.cssText = "position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); /*filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#CC000000, endColorstr=#CC000000); -ms-filter: \"progid:DXImageTransform.Microsoft.gradient(startColorstr=#CC000000, endColorstr=#CC000000)\";*/";
        pic.setAttribute('id',"picoverlay");
        pic.onclick = destroy;
        document.body.appendChild(pic);
        picframe = document.createElement("div");
        picframe.style.cssText = "margin: 200px auto 0 auto; height: 300px; width: 500px; background-color: #69C; border: #69C 2px solid;";
        picframe.setAttribute('id',"picinternal");
        picframe.innerHTML = "<div style=\"text-align:right; height: 15px; cursor:pointer;\">CLOSE</div><iframe src =\"modules/EJ_adverts_mt/changepic.php?id="+adid+"\" style=\"border:0; height: 285px; width: 500px;\"><p>Your browser does not support iframes.</p></iframe>";
        document.getElementById("picoverlay").appendChild(picframe);
    }
}

function destroy() {
		destroydiv = document.getElementById("picoverlay");
		document.body.removeChild(destroydiv);
}

function sendimage(id) {
    document.getElementById('message').innerHTML="";
    if(document.getElementById('imagefind').value!=""){
        ajax = ajaxRequest();
        ajax.onreadystatechange = function() {
            if (ajax.readyState==4 && ajax.status==200) {
                text=ajax.responseText;
                if (text=="OK") {
                    document.imageform.submit();
                } else {
                    if (confirm(document.getElementById('imagefind').value.substr(12)+" already exists! Do you want to replace it?")){
                        document.imageform.submit();
                    } else {
                        document.getElementById('message').innerHTML=text;
                    }
                }
            }
        }
        ajax.open("POST","checkimage.php",true);
        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        filename = document.getElementById('imagefind').value;
        ajax.send("adid="+id+"&img="+filename);
    } else {
        document.getElementById('message').innerHTML="You must select a file to upload!";
    }
}

function updateimage(img, id) {
	parent.document.getElementById('advertimage').src = "modules/EJ_adverts_mt/images/"+id+"/"+img;
	if (img != 'noimage.png')
	{
		parent.document.getElementById('image').value = img;
	} else
	{
		parent.document.getElementById('image').value = '';
	}
}

function selectImage(img)
{
	document.imageform.imagefind.value = img;
	document.imageform.submit();
}

function setPage(page,key,loc)
{
	document.advert_filter.page.value = page;
	updateAdvertFilter(key,loc,page);
}

function swap_image(imgid, newimg)
{
	var img = document.getElementById(imgid);
	var newsrc = newimg.src.split('&');
	img.src = newsrc[0]+"&height=298&width=298";
}