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
add('header');
menu();
?>

<div id="content">
<div class="hd  hd-linked">
									<table class="items">
										<tr>
											<td class="blocks">
												<div class="uic  first">
													<span>Posts: <?php //cfct_archive_title(); 
													wp_title('', true, 'right'); ?></span>
													</a>
												</div>
											</td>
										</tr>
									</table>
								</div>

<?php
	loop('','single');

?>
</div><!--#content-->

<?php

get_footer();

?>