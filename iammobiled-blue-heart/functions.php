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
define('DEBUG', false);
define('POST_LIMIT', 15);



function debug($str = '') {
	if (!empty($str)) {
		echo '<div class="cfct_banner">'.$str.'</div>';
	}
}

function add($str = ''){
	if (!empty($str)) {
		include(TEMPLATEPATH . '/'.$str.'.php');
 }
}

function menu($str = ''){
?>
				<div id="pageTabs">
					<table cellspacing="0" border="0" align="center">
						<tr>
							<td class="pageTab <?php if($str == 'default') echo 'ptactive'; ?>  first">
								<a class="inline" href="<?php bloginfo('url'); ?>" id="tab-today" x-bp-load-page="true">
								<span>Home</span>
								</a>
							</td>
						
							<td class="pageTab <?php if($str == '2') echo 'ptactive'; ?>">
<?php
global $wpdb;
$results=$wpdb->get_results("SELECT post_title,guid FROM $wpdb->posts WHERE post_type = 'page' and menu_order = 0");

?>							
								<a class="inline" href="<?php echo ($results[0]->guid); ?>&page=2" id="tab-fav" x-bp-load-page="true">
								<span>
								<?php echo ($results[0]->post_title); ?>
								</span>
								</a>
							</td>
						
							<td class="pageTab last <?php if($str == 'allsites') echo 'ptactive'; ?>">
								<a class="inline" href="<?php bloginfo('url'); ?>/?page=allsites" id="tab-allsites" x-bp-load-page="true">
								<span>All Sites</span>
								</a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		
			<div id="toolbar">
				<div class="oneSearch">
					<form id="search" action="<?php bloginfo('home'); ?>" method="get">
					
						<table class="osContainer">
							<tr>
								
							
								<td class="os-input">
									<span class="searchInput">
									<input type="text" name="s" id="onesearch-9" class="os-text" autocorrect="off" inputmode="predictOn" value="" />
									
									</span>
								</td>
							
								<td class="os-button">
									<input type="image" src = "<?php echo trailingslashit(get_bloginfo('template_url')).'img/search.gif';?>" class="os-button" alt="OneSearch"/>
								</td>
							</tr>
						</table>
					
					
					</form>
				</div>
			</div>
		</div>

									
	</div>

	<div id="content"> <!-- Need to add end div-->
				<hr class="native"/>
				<div class="uim"> <!-- Need to add end div-->
					<div class="bd">

<?php
 }

function loop($str = '', $single = ''){
		if (have_posts()) {
			$counter = POST_LIMIT;
			while (have_posts() & $counter > 0) {
				the_post();
				excerpt($str,$single);
				$counter--;
			}
	
	}
}



function excerpt($str = '', $single = ''){
	
	?>
				<div class="hd <?php if(empty($single)) echo 'linked hd-linked'   ?> ">
									<table class="items">
										<tr>
											<td class="blocks">
												<div class="uic  first">
												<?php if($str != 'page'){ ?>
												<a href="<?php the_permalink();?>">
												<?php } ?>
													<span><?php the_title(); ?> </span>
													</a>
												</div>
											</td>
										</tr>
									</table>
								</div>
							
							
								<div class="uip first">
									<table class="items">
										<tr>
											<td class="blocks">
											<div class="uic  first">
											<?php if($str != 'page'){ ?>
												<a href="<?php the_permalink();?>">
												<?php } ?>
												
												<span class="article"><?php the_excerpt(); ?> </span>
												<?php if($str != 'page'){ ?>
												<span class="subdued small"><?php the_time('M j, Y'); ?></span>
												<?php } ?>
												
												</a>
											</div>
											</td>
										</tr>
									</table>
								</div>
	<?php
	
	
}


function single($str = ''){
	if (have_posts()) {
	while (have_posts()) {
		the_post();
		content($str);
	}
}
}

function content($str = ''){
?>

<div class="hd  hd-linked">
									<table class="items">
										<tr>
											<td class="blocks">
												<div class="uic  first">
													<span><?php the_title(); ?> </span>
													</a>
												</div>
											</td>
										</tr>
									</table>
								</div>
							
							
								<div class="uip first">
									<table class="items">
										<tr>
											<td class="blocks">
											<div class="uic  first">
												<span class="article"><?php the_content(); ?> </span>
												<?php if($str != 'page'){ ?>
												<span class="subdued small">Posted by <?php the_author_posts_link(); ?> on <?php the_date(); ?>.</span>
												<?php } ?>
												</a>
											</div>
											</td>
										</tr>
									</table>
								</div>
<h1></h1>

<?php

$args = array(
	'before' => '<p class="pages-link">'. __('Pages: ', 'iammobiled-theme'),
	'after' => "</p>\n",
	'next_or_number' => 'number'
);
	
wp_link_pages($args);
?>


<?php the_tags('<p class="tags small">Tags: ',', ','</p>'); ?>
<?php if($str != 'page'){ ?>
<p class="categories small"><?php _e('Categories: '); the_category(', '); ?></p>
<?php } ?>
<?php

}

function postLinks($str = ''){
	if($str == 'single'){
?>
	<p id="next-prev-top" class="pagination">
	<<
<?php next_post_link('<span class="next">%link</span>'); ?> 
|
<?php previous_post_link('<span class="prev">%link</span>'); ?> 
>></p>
<?php	
	}
}

function iammobiled_ad(){
$iammobiled_ad_option=get_option('iammobiled_ad_option');
$iammobiled_ad_share=get_option('iammobiled_ad_share');
$ad_network = '';

//First check whehter ads are turned ON
if($iammobiled_ad_option != 'noad'){
	if($iammobiled_ad_option == 'admob'){
		$user_ad_id = get_option('iammobiled_user_admob_id');
		$ad_network = 'admob';
	}
	if($iammobiled_ad_share == 'share'){
	//Here we share the ad
		if($iammobiled_ad_option == 'admob'){
			$iammobiled_ad_id = get_option('iammobiled_admob_id');
		}
	
	
	@include ('ad.php');
	if (function_exists('iammobiledAd')) {
	$iammobiled_rand = rand(0, 1);
		if($iammobiled_rand == 0){
			if(strlen($user_ad_id) > 10)
				iammobiledAd($user_ad_id,$ad_network);
			else
				iammobiledAd($iammobiled_ad_id,$ad_network);
		}
		else{
		iammobiledAd($iammobiled_ad_id,$ad_network);
		}
	}
	
	
	}
	else{
	//Display only users' ad
	@include ('ad.php');
	if (function_exists('iammobiledAd')) {
		iammobiledAd($user_ad_id,$ad_network);
	}
	
	}

}


}

function commentForm($str = ''){

global $post, $user_ID, $user_identity, $comment_author, $comment_author_email, $comment_author_url;

$req = get_option('require_name_email');

// if post is open to new comments
if ('open' == $post->comment_status) {
	// if you need to be regestered to post comments..
	if ( get_option('comment_registration') && !$user_ID ) { ?>

<p id="you-must-be-logged-in-to-comment"><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'iammobiled-theme'), get_bloginfo('wpurl').'/wp-login.php?redirect_to='.get_permalink()); ?></p>

<?php
	}
	else { 
?>

<form id="respond" action="<?php bloginfo('wpurl'); ?>/wp-comments-post.php" method="post">
	<h3><span><?php _e('Leave a Reply', 'iammobiled-theme'); ?></span></h3>
	<?php // if you're logged in...
			if ($user_ID) {
	?>
		<p><?php printf(__('Logged in as <a href="%s">%s</a>. ', 'iammobiled-theme'), get_bloginfo('wpurl').'/wp-admin/profile.php', $user_identity); wp_loginout() ?></p>
	<?php
			} else { 
	?>
		<p>
			<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" />
			<label for="author"><small><?php _e('Name', 'iammobiled-theme'); if ($req) { _e(' (required)', 'iammobiled-theme'); } ?></small></label>
		</p>
		<p>
			<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" />
			<label for="email"><small><?php _e('Email', 'iammobiled-theme');
			if ($req) {
				_e(' (required, but never shared)', 'iammobiled-theme');
			}
			else {
				_e(' (never shared)', 'iammobiled-theme');
			} ?></small></label>
		</p>
		<p>
			<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" />
			<label title="<?php _e('Your website address', 'iammobiled-theme'); ?>" for="url"><small><?php _e('Web', 'iammobiled-theme'); ?></small></label>	
		</p>
	<?php 
			} 
	?>
	<p><textarea name="comment" id="comment" rows="8" cols="40"></textarea></p>
	<p>
		<input name="submit" type="submit" id="submit" value="<?php _e('Submit comment', 'iammobiled-theme'); ?>" tabindex="5" />
		<input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>" />
	</p>
<?php
do_action('comment_form', $post->ID);
?>
</form>
<?php 
	} // If registration required and not logged in 
} // If you delete this the sky will fall on your head

}

function comments($str = ''){

global $post, $wp_query, $comments, $comment;

if ($comments || 'open' == $post->comment_status) {
	if (empty($post->post_password) || $_COOKIE['wp-postpass_' . COOKIEHASH] == $post->post_password) {
		$comments = $wp_query->comments;
				
		$comment_count = count($comments);
		$comment_count == 1 ? $comment_title = __('One Response', 'iammobiled-theme') : $comment_title = sprintf(__('%d Responses', 'iammobiled-theme'), $comment_count);
	}

?>

<span class="subdued small"><?php echo $comment_title; ?></span>

<?php 

	if ($comments) {
?>
	<ol class="commentlist">
<?php
		foreach ($comments as $comment) {
?>
		<li id="comment-<?php comment_ID() ?>">
<?php
			
			if ($comment->comment_approved == '0') : ?>
		<p><em>Your comment is awaiting moderation.</em></p>
<?php endif; ?>

<?php comment_text() ?>

<p class="comment-info">by <cite><?php comment_author_link() ?></cite> on <a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('M j, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('e','',''); ?></small></p>

		</li>
<?php
		}
?>
	</ol>
<?php
	}
}

commentForm();

}


function page($page_id = '', $page = ''){
// passed arg1 is the page_id
echo $page_id.$page;
}


function footer($str = ''){
?>
	<div class="navbar uic last navbar-last navbar-tabs navbar-tabs-last">
									
								</div>

				<hr class="native"/>
				<div class="small center">
								<?php
								iammobiled_ad();
								
							?>
								</div>
					<div class="first center">
									
									<?php 
									echo '<span>View: Mobile | <a class = "inline" href="'.trailingslashit(get_bloginfo('home')).'?cf_action=reject_mobile">Desktop</a></span>';
									?>
								</div>
								
									<div class="small center last">
							<span> Theme by <a class= "inline" href="http://iammobiled.com"><strong>IamMobiled.</strong></a>Powered by <a class= "inline" href="http://wordpress.org"><strong>Wordpress</strong></a></span>
							
							</div>
						
								<div class="uic small last">
								<br/>
								</div>
								
							
				
			</div> <!-- bd -->
			</div></div>
			</body>
			</html>

<?php
}