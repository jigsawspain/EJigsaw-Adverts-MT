<?php
header("Content-Type: text/css");
require('../../config.inc.php');
?>
@charset "utf-8";

/*
*** EJigsaw Site Administration Suite
**
*** EJ_adverts_mt Module
**
*** By Jigsaw Spain - www.jigsawspain.com
**
*** EJ_adverts_mt Styles - File Build 0.1
*/

/*
* Common Tags
*/

// None Defined

/*
* Classes and IDs
*/

#addLeft
{
	float: left;
	margin: 5px;
	padding: 5px;
	text-align: center;
	width: 200px;
}

#addLeft input
{
	width: 100%;
}

#addLeft img
{
	cursor: pointer;
	margin: 5px;
}

#addRight
{
	float: left;
	margin: 5px;
	padding: 5px;
	width: 736px;
}

.button
{
	cursor: pointer;
	display: inline-block;
	height: 100px;
	margin: 15px;
	overflow: hidden;
	text-indent: -1000px;
	width: 100px;
}

.button:hover
{
	background-position: 0 -100px;
}

.cat_result
{
	background-color: #FFF;
	border: #42769B 1px solid;
	height: 16px;
	line-height: 16px;
	margin: 10px;
	overflow: hidden;
	padding: 5px;
}

.cat_result img
{
	vertical-align: middle;
	margin-top: -0.2em;
}

#container
{
	color: #42769B;
	font-size: 0.9em;
}

#advert_filter #category
{
	width: 100%;
}

#advert_filter #search_text
{
	width: 100%;
}

#advert_filter #EJmt_search
{
	width: 100%;
}

#advert_filter #subcategory
{
	width: 100%;
}

.advert_result
{
	background-color: #FFF;
	border: #42769B 1px solid;
	height: 16px;
	margin: 10px;
	overflow: hidden;
	padding: 5px;
}

.advertImage
{
	float: left;
	height: 120px;
	margin: 0 5px 5px 0;
	width: 120px;
}

.advert_result p
{
	margin-bottom: 5px;
}

#advert_message
{
	text-align: center;
}

#search_form
{
	background-color: #FFF;
	border: #42769B 1px solid;
	margin: 10px;
	padding: 5px;
}

/* Advert Display Styles */

.EJ_adverts_mt_catList
{
	background: url("icons-8-arrow.png") no-repeat scroll left -102px transparent;
	padding-left: 14px;
	margin-left: 0.2em;
}

#EJ_advertEnquiryForm
{
	margin: 5px;
}

#EJ_advertFilter
{
	margin: 0 5px;
}

#EJ_advertFilter p
{
	font-size: 0.9em;
}

.EJ_advertModified
{
	font-size: 10px;
	margin: 3px 0;
	width: 100%;
}

.EJ_advertModified .header a
{
	font-weight: bold;
	font-size: 12px !important;
}

.EJ_advertModified a
{
	color: #009ACA;
}

.EJ_advertModifiedImageHolder
{
	display: table-cell;
	height: 60px;
	text-align: center;
	vertical-align: middle;
	width: 80px;
}

.EJ_advertModifiedImageHolder img
{
	display: block;
	margin: 0 auto;
}

.EJ_advertNew
{
	font-size: 10px;
	margin: 3px 0;
	width: 100%;
}

.EJ_advertNew .header a
{
	font-weight: bold;
	font-size: 12px !important;
}

.EJ_advertNew a
{
	color: #009ACA;
}

.EJ_advertNewImageHolder
{
	display: table-cell;
	height: 60px;
	text-align: center;
	vertical-align: middle;
	width: 80px;
}

.EJ_advertNewImageHolder img
{
	display: block;
	margin: 0 auto;
}

.EJ_advertPopular
{
	font-size: 10px;
	margin: 3px 0;
	width: 100%;
}

.EJ_advertPopular .header a
{
	font-weight: bold;
	font-size: 12px !important;
}

.EJ_advertPopular a
{
	color: #009ACA;
}

.EJ_advertPopularImageHolder
{
	display: table-cell;
	height: 60px;
	text-align: center;
	vertical-align: middle;
	width: 80px;
}

.EJ_advertPopularImageHolder img
{
	display: block;
	margin: 0 auto;
}

.EJ_advertResult
{
	border: #CCC 1px solid;
	margin-bottom: 5px;
	margin-right: 205px;
}

#EJ_advertResult_address
{
	font-size: 0.9em;
	margin: 5px;
}

#EJ_advertResult_atts
{
	background: #93F;
	border: #AAA 1px solid;
	color: #FFF;
	font-size: 0.9em;
	margin: 10px 0 10px 0;
	padding: 5px;
	width: 288px;
}

#EJ_advertResult_atts li
{
	float: left;
	width: 140px;
}

#EJ_advertResult_atts li img
{
	margin-bottom: 0.2em;
	vertical-align: middle;
}

.EJ_advertResult_header
{
	background: #93F url(adheaderback.png) repeat-x;
	border-bottom: #CCC 1px solid;
	color: #FFF;
	font-weight: bold;
	height: 25px;
	line-height: 25px;
	padding: 0 5px;
	font-size: 18px;
}

.EJ_advertResult_header a
{
	color: #FFF;
}

.EJ_advertResult_img
{
	border: 0;
	float: left;
	margin-right: 5px;
}

#EJ_advertResult_left
{
	float: left;
	font-size: 0.9em;
	margin: 5px;
	overflow: hidden;
	padding: 3px;
	width: 769px;
}

.EJ_advertResult_left
{
	float: left;
	font-size: 0.8em;
	height: 100px;
	margin: 5px;
	overflow: hidden;
	padding: 3px;
	width: 553px;
}

#EJ_advertResult_right
{
	background: #CCC;
	float: right;
	margin-left: 5px;
	width: 200px;
}

#EJ_advertResult_right img
{
	vertical-align: middle;
	margin-bottom: 0.2em;
}

.EJ_advertResult_right
{
	border-left: #CCC 1px solid;
	float: right;
	font-size: 0.9em;
	height: 100px;
	margin: 5px;
	padding: 3px 0 3px 5px;
	width: 195px;
}

#EJ_advertResultImageHolder
{
	/*background: #CCC;*/
	border: #AAA 1px solid;
	display: table-cell;
	height: 298px;
	text-align: center;
	vertical-align: middle;
	width: 298px;
}

#EJ_advertResultImageHolder img
{
	display: block;
	margin: 0 auto;
}


#EJ_advertResult_mainLeft
{
	float: left;
	width: 459px;
}

#EJ_advertResult_mainRight
{
	float: right;
	width: 300px;
}

#search_results #pages
{
	font-size: 0.9em;
	height: 25px;
	line-height: 25px;
	margin-top: -25px;
	margin-right: 207px;
	text-align: right;
}