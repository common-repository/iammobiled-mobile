<?php

// This file is part of the IamMobiled Mobile Engine/Themes for WordPress
// http://iammobiled.com
//
// Copyright (c) 2009 IamMobiled.com All rights reserved.
// http://iammobiled.com
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (DEBUG) { debug(__FILE__); }

?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php wp_title('&laquo;', true, 'right'); bloginfo('name'); ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" charset="utf-8" />
	<style type="text/css">
		@import url(<?php echo trailingslashit(get_bloginfo('template_url')).'css/advanced.css'; ?>);
	</style>
	<script type="text/javascript">
	<!--
<?php

is_page() ? $page = 'true' : $page = 'false';
echo '	IS_PAGE = '.$page.';';
echo "	PAGES_TAB = '".str_replace("'", "\'", __('Pages', 'iammobiled-theme'))."';";
echo "	POSTS_TAB = '".str_replace("'", "\'", __('Recent Posts', 'iammobiled-theme'))."';";

global $cfmobi_touch_browsers;
if (!isset($cfmobi_touch_browsers) || !is_array($cfmobi_touch_browsers)) {
	$cfmobi_touch_browsers = array(
		'iPhone',
		'iPod',
		'Android',
		'BlackBerry9530',
		'LG-TU915 Obigo', // LG touch browser
		'LGE VX',
		'webOS', // Palm Pre, etc.
	);
}
if (count($cfmobi_touch_browsers)) {
	$touch = array();
	foreach ($cfmobi_touch_browsers as $browser) {
		$touch[] = str_replace('"', '\"', trim($browser));
	}

?>
	var CFMOBI_TOUCH = ["<?php echo implode('","', $touch); ?>"];
	for (var i = 0; i < CFMOBI_TOUCH.length; i++) {
		if (navigator.userAgent.indexOf(CFMOBI_TOUCH[i]) != -1) {
			document.write('<?php echo str_replace('/', '\/', '<link rel="stylesheet" href="'.trailingslashit(get_bloginfo('template_url')).'css/touch.css" type="text/css" media="screen" charset="utf-8" />'); ?>');
			break;
		}
	}
<?php

}

?> 
	document.write('<?php

ob_start();
wp_print_scripts();
$wp_scripts = ob_get_contents();
ob_end_clean();

echo trim(str_replace(
	array("'", "\n", '/'), 
	array("\'", '', '\/'),
	$wp_scripts
));

?>');

	//--></script>
</head>

<body class="list">
	<div id="page">
		<div id="pageHeader">
			<div id="pageBranding">
				<div id="titlebar">
					<div class="logoAndTitle">
						
						
						<span class="icon">
							<a rel="home" href="<?php bloginfo('url'); ?>">
							
							<!--
							<img src="../img/logo.gif"/>
							-->
							<span class="iconText">
							
							<?php bloginfo('name'); ?>
							</span>
							</a>
						</span>
					</div>
				</div>