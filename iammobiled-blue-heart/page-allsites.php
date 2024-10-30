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
menu('allsites');

?>

<?php


?>
<!--<div class="hd  hd-linked">-->
<?php
$pages = get_pages('sort_column=post_title'); 
foreach ($pages as $pagg) {
	?>
	
	<div class="hd linked hd-linked">
									<table class="items">
										<tr>
											<td class="blocks">
												<div class="uic  first">
													<span>
													<a href="<?php echo get_page_link($pagg->ID); ?>">
													<?php echo $pagg->post_title; ?></a> 
													
													</span>
													</a>
												</div>
											</td>
										</tr>
									</table>
								</div>
								
<?php								
	
  }	
?>



<?php 
get_footer();
?>