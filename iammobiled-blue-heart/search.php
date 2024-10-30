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

add('header');
menu();

$s = get_query_var('s');

$search_title = '<a href="'.get_bloginfo('url').'/?s='.attribute_escape($s).'" title="">'.htmlspecialchars($s).'</a>';

?>

	<div class="searchTitle linked"><?php printf(__('Search Results for: %s', 'iammobiled-theme'), $search_title); ?></div>
	<div class="uim"> <!-- Need to add end div-->
					<div class="bd">	
<?php
loop();
get_footer();
