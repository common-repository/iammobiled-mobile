<?php
/*
Plugin Name: IamMobiled Mobile
Plugin URI: http://iammobiled.com 
Description: IamMobiled Mobile Plugin enables your mobile users to see a special version of your website created specially for mobile phones. It comes with two stunning mobile themes to choose. Key features include 1) Automatic mobile detection 2) Images are optimized for mobile view 3) Make money through integrated ad system. 4) Optimized for touch screen phones 5) Mobile users can even leave comments 6) Mobile users can search for contents.
Version: 1.0
Author: Haress Das
Author URI: http://iammobiled.com
*/

// IamMobiled Mobile
//
// Copyright (c) 2009 IamMobiled.com
// http://iammobiled.com
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// *****************************************************************

define('CF_MOBILE_THEME',get_option('iammobiled_mobile_template'));


if (!defined('PLUGINDIR')) {
	define('PLUGINDIR','wp-content/plugins');
}

load_plugin_textdomain('cf-mobile');

if (is_file(trailingslashit(ABSPATH.PLUGINDIR).'iammobiled-mobile.php')) {
	define('CFMOBI_FILE', trailingslashit(ABSPATH.PLUGINDIR).'iammobiled-mobile.php');
}
else if (is_file(trailingslashit(ABSPATH.PLUGINDIR).'iammobiled-mobile/iammobiled-mobile.php')) {
	define('CFMOBI_FILE', trailingslashit(ABSPATH.PLUGINDIR).'iammobiled-mobile/iammobiled-mobile.php');
}

register_activation_hook(CFMOBI_FILE, 'cfmobi_install');

function cfmobi_default_browsers($type = 'mobile') {
	$mobile = array(
		'2.0 MMP',
		'240x320',
		'400X240',
		'AvantGo',
		'BlackBerry',
		'Blazer',
		'Cellphone',
		'Danger',
		'DoCoMo',
		'Elaine/3.0',
		'EudoraWeb',
		'Googlebot-Mobile',
		'hiptop',
		'IEMobile',
		'KYOCERA/WX310K',
		'LG/U990',
		'MIDP-2.',
		'MMEF20',
		'MOT-V',
		'NetFront',
		'Newt',
		'Nintendo Wii',
		'Nitro', // Nintendo DS
		'Nokia',
		'Opera Mini',
		'Palm',
		'PlayStation Portable',
		'portalmmm',
		'Proxinet',
		'ProxiNet',
		'SHARP-TQ-GX10',
		'SHG-i900',
		'Small',
		'SonyEricsson',
		'Symbian OS',
		'SymbianOS',
		'TS21i-10',
		'UP.Browser',
		'UP.Link',
		'webOS', // Palm Pre, etc.
		'Windows CE',
		'WinWAP',
		'YahooSeeker/M1A1-R2D2',
	);
	$touch = array(
		'iPhone',
		'iPod',
		'Android',
		'BlackBerry9530',
		'LG-TU915 Obigo', // LG touch browser
		'LGE VX',
		'webOS', // Palm Pre, etc.
		'Nokia5800',
	);
	switch ($type) {
		case 'mobile':
		case 'touch':
			return $$type;
	}
}

add_action('admin_menu', 'mypageorder_js_libs'); 
$mobile = explode("\n", trim(get_option('cfmobi_mobile_browsers')));
$cfmobi_mobile_browsers = apply_filters('cfmobi_mobile_browsers', $mobile);
$touch = explode("\n", trim(get_option('cfmobi_touch_browsers')));
$cfmobi_touch_browsers = apply_filters('cfmobi_touch_browsers', $touch);

function cfmobi_install() {
	global $cfmobi_default_mobile_browsers;
	add_option('cfmobi_mobile_browsers', implode("\n", cfmobi_default_browsers('mobile')));
	global $cfmobi_default_touch_browsers;
	add_option('cfmobi_touch_browsers', implode("\n", cfmobi_default_browsers('touch')));
	add_option('iammobiled_mobile_theme',get_option('current_theme'));
	update_option('iammobiled_mobile_theme',get_option('current_theme'));
	add_option('iammobiled_mobile_template',get_option('template'));
	update_option('iammobiled_mobile_template',get_option('template'));
	add_option('iammobiled_admob_id','a14aeb283189d2b');
	update_option('iammobiled_admob_id','a14aeb283189d2b');
	add_option('iammobiled_ad_option','admob');
	update_option('iammobiled_ad_option','admob');
	add_option('iammobiled_user_admob_id','');
	add_option('iammobiled_ad_share','share');
	update_option('iammobiled_ad_share','share');
}

function cfmobi_init() {
	global $cfmobi_mobile_browsers, $cfmobi_touch_browsers;
	if (is_admin() && !cfmobi_installed()) {
		global $wp_version;
		if (isset($wp_version) && version_compare($wp_version, '2.5', '>=')) {
			add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>IamMobile Mobile is incorrectly installed. Please check the <a href=\"http://iammobiled.com/\">README</a>.</p></div>';" ) );
		}
	}
	if (isset($_COOKIE['cf_mobile']) && $_COOKIE['cf_mobile'] == 'false') {
		add_action('the_content', 'cfmobi_mobile_available');
	}
}
add_action('init', 'cfmobi_init');

function cfmobi_check_mobile() {
	global $cfmobi_mobile_browsers, $cfmobi_touch_browsers;
	$mobile = null;
	if (!isset($_SERVER["HTTP_USER_AGENT"]) || (isset($_COOKIE['cf_mobile']) && $_COOKIE['cf_mobile'] == 'false')) {
		$mobile = false;
	}
	else if (isset($_COOKIE['cf_mobile']) && $_COOKIE['cf_mobile'] == 'true') {
		$mobile = true;
	}
	$browsers = array_merge($cfmobi_mobile_browsers, $cfmobi_touch_browsers);
	if (is_null($mobile) && count($browsers)) {
		foreach ($browsers as $browser) {
			if (!empty($browser) && strpos($_SERVER["HTTP_USER_AGENT"], trim($browser)) !== false) {
				$mobile = true;
			}
		}
	}
	if (is_null($mobile)) {
		$mobile = false;
	}
	return apply_filters('cfmobi_check_mobile', $mobile);
}

if (cfmobi_check_mobile()) {
	add_filter('template', 'cfmobi_template');
	add_filter('option_template', 'cfmobi_template');
	add_filter('option_stylesheet', 'cfmobi_template');
	add_filter('the_content', 'optimize_img');
}

function mypageorder_js_libs() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
	
}

function cfmobi_template($theme) {

	if (cfmobi_installed()) {
			return apply_filters('cfmobi_template', CF_MOBILE_THEME);
		
	}
	else {
		return $theme;
	}
	
}

function cfmobi_installed() {
	 return is_dir(ABSPATH.'/wp-content/themes/'.CF_MOBILE_THEME);
}

function cfmobi_mobile_exit() {
	echo '<p class="centered">View: Mobile | <a href="'.trailingslashit(get_bloginfo('home')).'?cf_action=reject_mobile">Desktop</a></p>';
}

function cfmobi_mobile_available($content) {
	if (!defined('WPCACHEHOME')) {
		$content = $content.'<p><a href="'.trailingslashit(get_bloginfo('home')).'?cf_action=show_mobile">Return to the Mobile Edition</a>.</p>';
	}
	return $content;
}

function cfmobi_mobile_link() {
	if (!defined('WPCACHEHOME')) {
		echo '<a href="'.trailingslashit(get_bloginfo('home')).'?cf_action=show_mobile">Mobile Edition</a>';
	}
}

// TODO - add sidebar widget for link, with some sort of graphic?

function cfmobi_request_handler() {
	
	if (!empty($_GET['cf_action'])) {
		$url = parse_url(get_bloginfo('home'));
		$domain = $url['host'];
		if (!empty($url['path'])) {
			$path = $url['path'];
		}
		else {
			$path = '/';
		}
		$redirect = false;
		switch ($_GET['cf_action']) {
			case 'cfmobi_admin_js':
				cfmobi_admin_js();
				break;
			case 'cfmobi_admin_css':
				cfmobi_admin_css();
				die();
				break;
			case 'reject_mobile':
				setcookie(
					'cf_mobile'
					, 'false'
					, time() + 300000
					, $path
					, $domain
				);
				$redirect = true;
				break;
			case 'show_mobile':
				setcookie(
					'cf_mobile'
					, 'true'
					, time() + 300000
					, $path
					, $domain
				);
				$redirect = true;
				break;
			case 'cfmobi_who':
				if (current_user_can('manage_options')) {
					header("Content-type: text/plain");
					echo sprintf(__('Browser: %s', 'cf-mobile'), strip_tags($_SERVER['HTTP_USER_AGENT']));
					die();
				}
				break;
			case 'activate_mobile_theme':
				if(current_user_can('switch_themes')){
					update_option('iammobiled_mobile_theme',$_GET['theme_name']);
					update_option('iammobiled_mobile_template',$_GET['template']);
					wp_redirect(trailingslashit(get_bloginfo('wpurl')).'wp-admin/options-general.php?page=iammobiled-mobile.php&updated=true');
					die();
				}
				break;
		}
		if ($redirect) {
			if (!empty($_SERVER['HTTP_REFERER'])) {
				$go = $_SERVER['HTTP_REFERER'];
			}
			else {
				$go = get_bloginfo('home');
			}
			header('Location: '.$go);
			die();
		}
	}
	if (!empty($_POST['cf_action'])) {
		switch ($_POST['cf_action']) {
			case 'cfmobi_update_settings':
				cfmobi_save_settings();
				wp_redirect(trailingslashit(get_bloginfo('wpurl')).'wp-admin/options-general.php?page=iammobiled-mobile.php&updated=true');
				die();
				break;
		}
	}
}
add_action('init', 'cfmobi_request_handler');

function cfmobi_admin_js() {
	global $cfmobi_default_mobile_browsers, $cfmobi_default_touch_browsers;
	header('Content-type: text/javascript');
	$mobile = str_replace(array("'","\r", "\n"), array("\'", '', ''), implode('\\n', cfmobi_default_browsers('mobile')));
	$touch = str_replace(array("'","\r", "\n"), array("\'", '', ''), implode('\\n', cfmobi_default_browsers('touch')));
?>
jQuery(function($) {
	$('#cfmobi_mobile_reset').click(function() {
		$('#cfmobi_mobile_browsers').val('<?php echo $mobile; ?>');
		return false;
	});
	$('#cfmobi_touch_reset').click(function() {
		$('#cfmobi_touch_browsers').val('<?php echo $touch; ?>');
		return false;
	});
});
<?php



	die();
}
if (is_admin()) {
	wp_enqueue_script('cfmobi_admin_js', trailingslashit(get_bloginfo('url')).'?cf_action=cfmobi_admin_js', array('jquery'));
}

function cfmobi_admin_css() {
	header('Content-type: text/css');
?>
fieldset.options div.option {
	background: #EAF3FA;
	margin-bottom: 8px;
	padding: 10px;
}
fieldset.options div.option label {
	display: block;
	float: left;
	font-weight: bold;
	margin-right: 10px;
	width: 150px;
}
fieldset.options div.option span.help {
	color: #666;
	font-size: 11px;
	margin-left: 8px;
}
#cfmobi_mobile_browsers, #cfmobi_touch_browsers {
	height: 200px;
	width: 300px;
}
#cfmobi_mobile_reset, #cfmobi_touch_reset {
	display: block;
	font-size: 11px;
	font-weight: normal;
}
<?php
	die();
}


function cfmobi_admin_head() {
	echo '<link rel="stylesheet" type="text/css" href="'.trailingslashit(get_bloginfo('url')).'?cf_action=cfmobi_admin_css" />';
}
add_action('admin_head', 'cfmobi_admin_head');

$cfmobi_settings = array(
	'cfmobi_mobile_browsers' => array(
		'type' => 'textarea',
		'label' => 'Mobile Browsers <a href="#" id="cfmobi_mobile_reset">Reset to Default</a>',
		'default' => cfmobi_default_browsers('mobile'),
		'help' => '',
	),
	'cfmobi_touch_browsers' => array(
		'type' => 'textarea',
		'label' => 'Touch Browsers <a href="#" id="cfmobi_touch_reset">Reset to Default</a>',
		'default' => cfmobi_default_browsers('touch'),
		'help' => '(iPhone, Android G1, BlackBerry Storm, etc.)',
	),
);

function cfmobi_setting($option) {
	$value = get_option($option);
	if (empty($value)) {
		global $cfmobi_settings;
		$value = $cfmobi_settings[$option]['default'];
	}
	return $value;
}

function cfmobi_admin_menu() {
	if (current_user_can('manage_options')) {
		add_options_page(
			__('IamMobiled Mobile', 'cf-mobile')
			, __('IamMobiled Mobile', 'cf-mobile')
			, 10
			, basename(__FILE__)
			, 'cfmobi_settings_form'
		);
	}
}
add_action('admin_menu', 'cfmobi_admin_menu');

function cfmobi_plugin_action_links($links, $file) {
	$plugin_file = basename(__FILE__);
	if ($file == $plugin_file) {
		$settings_link = '<a href="options-general.php?page='.$plugin_file.'">'.__('Settings', 'cf-mobile').'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'cfmobi_plugin_action_links', 10, 2);

if (!function_exists('cf_settings_field')) {
	function cf_settings_field($key, $config) {
		$option = get_option($key);
		$label = '<label for="'.$key.'">'.$config['label'].'</label>';
		$help = '<span class="help">'.$config['help'].'</span>';
		switch ($config['type']) {
			case 'select':
				$output = $label.'<select name="'.$key.'" id="'.$key.'">';
				foreach ($config['options'] as $val => $display) {
					$option == $val ? $sel = ' selected="selected"' : $sel = '';
					$output .= '<option value="'.$val.'"'.$sel.'>'.htmlspecialchars($display).'</option>';
				}
				$output .= '</select>'.$help;
				break;
			case 'textarea':
				if (is_array($option)) {
					$option = implode("\n", $option);
				}
				$output = $label.'<textarea name="'.$key.'" id="'.$key.'">'.htmlspecialchars($option).'</textarea>'.$help;
				break;
			case 'string':
			case 'int':
			default:
				$output = $label.'<input name="'.$key.'" id="'.$key.'" value="'.htmlspecialchars($option).'" />'.$help;
				break;
		}
		return '<div class="option">'.$output.'<div class="clear"></div></div>';
	}
}

function cfmobi_settings_form() {
	

	global $cfmobi_settings;
	print('
<div class="wrap">
	<h2>'.__('IamMobiled Mobile', 'cf-mobile').'</h2>
	<form id="cfmobi_settings_form" name="cfmobi_settings_form" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php" method="post">
		<input type="hidden" name="cf_action" value="cfmobi_update_settings" />
		
		<fieldset class="options">
		<div class="option">
	');
	
	
	mobile_theme_chooser();
	print ('<div class="clear"></div></div>
	
	<div class="option">');
	page_order();	
	
	print ('<div class="clear"></div></div>

	<div class="option">');
	
	ad_selection();	
	
	print ('<div class="clear"></div></div>
	
		<p class="submit">
		<input type="button" id="orderButton" class="button-primary" Value="Save Settings" onclick="javascript:orderPages();">
		&nbsp;&nbsp;<strong id="updateText"></strong>
		</p>
		</fieldset>
		</form>
	
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="9677310">
Give some to cheer me up for more free "good" work :)<br /><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

	
</div>
	');

	do_action('cfmobi_settings_form');
	
	/*print('
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="9677310">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>');
*/

}



function page_order(){
	
global $wpdb;

$parentID = 0;

if (isset($_POST['ParentId']))
	$parentID = $_POST['ParentId'];
	
	$subPageStr = "";
	$results=$wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_parent = $parentID and post_type = 'page' ORDER BY menu_order ASC");
	foreach($results as $row)
	{
		$postCount=$wpdb->get_row("SELECT count(*) as postsCount FROM $wpdb->posts WHERE post_parent = $row->ID and post_type = 'page' ", ARRAY_N);
		if($postCount[0] > 0)
	    	$subPageStr = $subPageStr."<option value='$row->ID'>$row->post_title</option>";
	}
?>
<div class='wrap'>
	<h3><?php _e('Order Pages', 'mypageorder') ?></h3>
	<p><?php _e('Choose a page from the drop down to order its subpages or order the pages on this level by dragging and dropping them into the desired order. IamMobiled themes\' tabs are displayed by this order.', 'mypageorder') ?></p>

	
	<ul id="order" style="width: 500px; margin:10px 10px 10px 0px; padding:10px; border:1px solid #B2B2B2; list-style:none;"><?php
	foreach($results as $row)
	{
		echo "<li id='$row->ID' class='lineitem'>$row->post_title</li>";
	}?>
	</ul>
	
	<input type="hidden" name="ParentId" value="" />
	<input type="hidden" name="idString" value="" />
		
</div>

<style>
	li.lineitem {
		margin: 3px 0px;
		padding: 2px 5px 2px 5px;
		background-color: #F1F1F1;
		border:1px solid #B2B2B2;
		cursor: move;
		width: 490px;
	}
</style>

<script type="text/javascript">
// <![CDATA[

	function mypageorderaddloadevent(){
		jQuery("#order").sortable({ 
			placeholder: "ui-selected", 
			revert: false,
			tolerance: "pointer" 
		});
	};

	addLoadEvent(mypageorderaddloadevent);
	
	function orderPages() {
		jQuery("#orderButton").css("display", "none");
		jQuery("#updateText").html("<?php _e('Saving your settings...', 'mypageorder') ?>");

		idList = jQuery("#order").sortable("toArray");
		//location.href = 'options-general.php?page=iammobiled-mobile&mode=act_OrderPages&updated=true&parentID=<?php echo $parentID; ?>&idString='+idList;
		document.cfmobi_settings_form.ParentId.value=<?php echo $parentID; ?>;
		document.cfmobi_settings_form.idString.value=idList;
		document.cfmobi_settings_form.submit();
		
	}


// ]]>
</script>
<?php
	
}

function current_mobile_theme_info() {
	$themes = get_themes();
	$current_theme = get_option('iammobiled_mobile_theme');
	$ct->name = $current_theme;
	$ct->title = $themes[$current_theme]['Title'];
	$ct->version = $themes[$current_theme]['Version'];
	$ct->parent_theme = $themes[$current_theme]['Parent Theme'];
	$ct->template_dir = $themes[$current_theme]['Template Dir'];
	$ct->stylesheet_dir = $themes[$current_theme]['Stylesheet Dir'];
	$ct->template = $themes[$current_theme]['Template'];
	$ct->stylesheet = $themes[$current_theme]['Stylesheet'];
	$ct->screenshot = $themes[$current_theme]['Screenshot'];
	$ct->description = $themes[$current_theme]['Description'];
	$ct->author = $themes[$current_theme]['Author'];
	$ct->tags = $themes[$current_theme]['Tags'];
	return $ct;
}


function mobile_theme_chooser(){

/** WordPress Administration Bootstrap */
require_once('admin.php');


if ( !current_user_can('switch_themes') )
	wp_die( __( 'Cheatin&#8217; uh?' ) );
	
	
	$title = __('Manage Mobile Themes');
	$parent_file = 'themes.php';
	$help = '<p>' . __('Themes give your WordPress style. Once a theme is installed, you may preview it, activate it or deactivate it here.') . '</p>';
	
	if ( current_user_can('install_themes') ) {
	$help .= '<p>' . sprintf(__('You can find additional themes for your site by using the new <a href="%1$s">Theme Browser/Installer</a> functionality or by browsing the <a href="http://wordpress.org/extend/themes/">WordPress Theme Directory</a> directly and installing manually.  To install a theme <em>manually</em>, <a href="%2$s">upload its ZIP archive with the new uploader</a> or copy its folder via FTP into your <code>wp-content/themes</code> directory.'), 'theme-install.php', 'theme-install.php?tab=upload' ) . '</p>';
	$help .= '<p>' . __('Once a theme is uploaded, you should see it on this page.') . '</p>' ;
}
add_contextual_help('themes', $help);

add_thickbox();
wp_enqueue_script( 'theme-preview' );

require_once('admin-header.php');

if (  !validate_current_theme() ) : 
?>
<div id="message1" class="updated fade"><p><?php _e('The active theme is broken.  Reverting to the default theme.'); ?></p></div>
<?php elseif ( isset($_GET['activated']) ) :
		if ( isset($wp_registered_sidebars) && count( (array) $wp_registered_sidebars ) ) { ?>
<div id="message2" class="updated fade"><p><?php printf(__('New theme activated. This theme supports widgets, please visit the <a href="%s">widgets settings page</a> to configure them.'), admin_url('widgets.php') ); ?></p></div><?php
		} else { ?>
<div id="message2" class="updated fade"><p><?php printf(__('New theme activated. <a href="%s">Visit site</a>'), get_bloginfo('url') . '/'); ?></p></div><?php
		}
	elseif ( isset($_GET['deleted']) ) : ?>
<div id="message3" class="updated fade"><p><?php _e('Theme deleted.') ?></p></div>
<?php endif; 

$themes = get_themes();
$current_mobile_theme = get_option('iammobiled_mobile_theme');

$ct = current_mobile_theme_info();
//print_r($themes);

unset($themes[$current_mobile_theme]);

uksort( $themes, "strnatcasecmp" );
$theme_total = count( $themes );
$per_page = 100;

if ( isset( $_GET['pagenum'] ) )
	$page = absint( $_GET['pagenum'] );

if ( empty($page) )
	$page = 1;
	$start = $offset = ( $page - 1 ) * $per_page;

$page_links = paginate_links( array(
	'base' => add_query_arg( 'pagenum', '%#%' ) . '#themenav',
	'format' => '',
	'prev_text' => __('&laquo;'),
	'next_text' => __('&raquo;'),
	'total' => ceil($theme_total / $per_page),
	'current' => $page
));

$themes = array_slice( $themes, $start, $per_page );
?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>
<?php _e('Tips: All the themes including the given IamMobiled themes should be placed asusual in your wp-content/themes folder.<br />'); ?>
<h3><?php _e('Current Mobile Theme'); ?></h3>
<div id="current-theme">
<?php if ( $ct->screenshot ) : ?>
<img src="<?php echo content_url($ct->stylesheet_dir . '/' . $ct->screenshot); ?>" alt="<?php _e('Current theme preview'); ?>" />
<?php endif; ?>
<h4><?php
	/* translators: 1: theme title, 2: theme version, 3: theme author */
	printf(__('%1$s %2$s by %3$s'), $ct->title, $ct->version, $ct->author) ; ?></h4>
<p class="theme-description"><?php echo $ct->description; ?></p>
<?php if ($ct->parent_theme) { ?>
	<p><?php printf(__('The template files are located in <code>%2$s</code>.  The stylesheet files are located in <code>%3$s</code>.  <strong>%4$s</strong> uses templates from <strong>%5$s</strong>.  Changes made to the templates will affect both themes.'), $ct->title, $ct->template_dir, $ct->stylesheet_dir, $ct->title, $ct->parent_theme); ?></p>
<?php } else { ?>
	<p><?php printf(__('All of this theme&#8217;s files are located in <code>%2$s</code>.'), $ct->title, $ct->template_dir, $ct->stylesheet_dir); ?></p>
<?php } ?>
<?php if ( $ct->tags ) : ?>
<p><?php _e('Tags:'); ?> <?php echo join(', ', $ct->tags); ?></p>
<?php endif; ?>


</div>

<div class="clear"></div>
<h3><?php _e('Available Themes'); ?></h3>
<div class="clear"></div>

<?php if ( $theme_total ) { ?>

<?php if ( $page_links ) : ?>
<div class="tablenav">
<div class="tablenav-pages"><?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
	number_format_i18n( $start + 1 ),
	number_format_i18n( min( $page * $per_page, $theme_total ) ),
	number_format_i18n( $theme_total ),
	$page_links
); echo $page_links_text; ?></div>
</div>
<?php endif; ?>

<table id="availablethemes" cellspacing="0" cellpadding="0">
<?php
$style = '';

$theme_names = array_keys($themes);
natcasesort($theme_names);

$table = array();
$rows = ceil(count($theme_names) / 3);
for ( $row = 1; $row <= $rows; $row++ )
	for ( $col = 1; $col <= 3; $col++ )
		$table[$row][$col] = array_shift($theme_names);

foreach ( $table as $row => $cols ) {
?>
<tr>
<?php
foreach ( $cols as $col => $theme_name ) {
	$class = array('available-theme');
	if ( $row == 1 ) $class[] = 'top';
	if ( $col == 1 ) $class[] = 'left';
	if ( $row == $rows ) $class[] = 'bottom';
	if ( $col == 3 ) $class[] = 'right';
?>
	<td class="<?php echo join(' ', $class); ?>">
<?php if ( !empty($theme_name) ) :
	$template = $themes[$theme_name]['Template'];
	$stylesheet = $themes[$theme_name]['Stylesheet'];
	$title = $themes[$theme_name]['Title'];
	$version = $themes[$theme_name]['Version'];
	$description = $themes[$theme_name]['Description'];
	$author = $themes[$theme_name]['Author'];
	$screenshot = $themes[$theme_name]['Screenshot'];
	$stylesheet_dir = $themes[$theme_name]['Stylesheet Dir'];
	$template_dir = $themes[$theme_name]['Template Dir'];
	$parent_theme = $themes[$theme_name]['Parent Theme'];
	$preview_link = esc_url(get_option('home') . '/');
	if ( is_ssl() )
		$preview_link = str_replace( 'http://', 'https://', $preview_link );
	$preview_link = htmlspecialchars( add_query_arg( array('preview' => 1, 'template' => $template, 'stylesheet' => $stylesheet, 'TB_iframe' => 'true' ), $preview_link ) );
	$preview_text = esc_attr( sprintf( __('Preview of &#8220;%s&#8221;'), $title ) );
	$tags = $themes[$theme_name]['Tags'];
	$thickbox_class = 'thickbox thickbox-preview';
	$activate_link = wp_nonce_url("options-general.php?page=iammobiled-mobile.php&amp;cf_action=activate_mobile_theme&amp;theme_name=".urlencode($theme_name)."&amp;template=".urlencode($template)."&amp;stylesheet=".urlencode($stylesheet), 'switch-theme_' . $template);
	$activate_text = esc_attr( sprintf( __('Activate &#8220;%s&#8221;'), $title ) );
	$actions = array();
	$actions[] = '<a href="' . $activate_link .  '" class="activatelink" title="' . $activate_text . '">' . __('Activate') . '</a>';
	$actions[] = '<a href="' . $preview_link . '" class="thickbox thickbox-preview" title="' . esc_attr(sprintf(__('Preview &#8220;%s&#8221;'), $theme_name)) . '">' . __('Preview') . '</a>';
	
	$actions = apply_filters('theme_action_links', $actions, $themes[$theme_name]);

	$actions = implode ( ' | ', $actions );
?>
		<a href="<?php echo $preview_link; ?>" class="<?php echo $thickbox_class; ?> screenshot">
<?php if ( $screenshot ) : ?>
			<img src="<?php echo content_url($stylesheet_dir . '/' . $screenshot); ?>" alt="" />
<?php endif; ?>
		</a>
<h3><?php
	/* translators: 1: theme title, 2: theme version, 3: theme author */
	printf(__('%1$s %2$s by %3$s'), $title, $version, $author) ; ?></h3>
<p class="description"><?php echo $description; ?></p>
<span class='action-links'><?php echo $actions ?></span>
	<?php if ($parent_theme) {
	/* translators: 1: theme title, 2:  template dir, 3: stylesheet_dir, 4: theme title, 5: parent_theme */ ?>
	<p><?php printf(__('The template files are located in <code>%2$s</code>.  The stylesheet files are located in <code>%3$s</code>.  <strong>%4$s</strong> uses templates from <strong>%5$s</strong>.  Changes made to the templates will affect both themes.'), $title, $template_dir, $stylesheet_dir, $title, $parent_theme); ?></p>
<?php } else { ?>
	<p><?php printf(__('All of this theme&#8217;s files are located in <code>%2$s</code>.'), $title, $template_dir, $stylesheet_dir); ?></p>
<?php } ?>
<?php if ( $tags ) : ?>
<p><?php _e('Tags:'); ?> <?php echo join(', ', $tags); ?></p>
<?php endif; ?>
		
<?php endif; // end if not empty theme_name ?>
	</td>
<?php } // end foreach $cols ?>
</tr>
<?php } // end foreach $table ?>
</table>
<?php } else { ?>
<p><?php _e('You only have one theme installed at the moment so there is nothing to show you here.  Maybe you should download some more to try out.'); ?></p>
<?php } // end if $theme_total?>
<br class="clear" />

<?php if ( $page_links ) : ?>
<div class="tablenav">
<?php echo "<div class='tablenav-pages'>$page_links_text</div>"; ?>
<br class="clear" />
</div>
<?php endif; ?>

<br class="clear" />

<?php
}

//Mobile Image Optimizer
function optimize_img($text) {

	$add = (get_option('siteurl').'/'.(PLUGINDIR).'/iammobiled-mobile/optimize-image.php?maxsize=150&source=');
	
    $result = array();
	preg_match_all('|src="[^"]*"|U', $text, $result);
	
    foreach($result[0] as $img_tag)
    {
		$split = preg_split('<src=">',$img_tag);
		$image_link = chop($split[1],"\"");
		$text = str_replace($img_tag, 'src="'.$add.$image_link.'"', $text);
    }
	
	
	//This removes the class="anything"
	$result = array();
    preg_match_all('|class="[^"]*"|U', $text, $result);
    
    // Replace all occurances with an empty string.
    foreach($result[0] as $img_tag)
    {
        $text = str_replace($img_tag, '', $text);
    }
   
	// Remove the width
	$result = array();
    preg_match_all('|width="[^"]*"|U', $text, $result);
    
    // Replace all occurances with an empty string.
    foreach($result[0] as $img_tag)
    {
        $text = str_replace($img_tag, '', $text);
    }
	
	//Remove the height
	$result = array();
    preg_match_all('|height="[^"]*"|U', $text, $result);
    
    // Replace all occurances with an empty string.
    foreach($result[0] as $img_tag)
    {
        $text = str_replace($img_tag, '', $text);
    }
	
	//Little patch need to be changed in future release
	//add br to anything ending with />
	$text = str_replace('/>', '/><br />', $text);
    
    return $text;
	
	
}


function ad_selection(){

$iammobiled_ad_option = get_option('iammobiled_ad_option');
$iammobiled_ad_share = get_option('iammobiled_ad_share');

print 	('
		<h3>');
		
		_e('Advertisements', 'mypageorder');
		
print	('</h3>
		<p>
		');
		
		_e('You can make money by simply entering your publisher ID. The plugin will take care of everything else! ', 'mypageorder');
		
print	('</p>
		');		
		
print	('
		Your Admob ID:<input type="text" name="iammobiled_user_admob_id" value ="'.get_option('iammobiled_user_admob_id').'"class="options" size=30> <a href="http://admob.com">Click here to get a new AdMob account</a> <br>
		<br />
		Turn On/Off Ads. IamMobiled themes will now display the selected ad network:<br />
		<input type="radio" name="iammobiled_ad_option" value="admob"');
		
	if($iammobiled_ad_option == 'admob') 
		echo 'checked';

print('	> AdMob<br />
		<input type="radio" name="iammobiled_ad_option" value="noad"
		');
if($iammobiled_ad_option == 'noad') 
		echo 'checked';
	print(' > Turn off ads
	
	
	<br />
	Token of appreciation: (If ads are ON)<br />
	<input type="radio" name="iammobiled_ad_share" value="share"
	
	');
		
	if($iammobiled_ad_share == 'share') 
		echo 'checked';
		
print('> I\'ll share 50%. [ Half of the displayed ad will belong to you. You are a nice person! :) ] <br />
		<input type="radio" name="iammobiled_ad_share" value="noshare"
		');
if($iammobiled_ad_share == 'noshare') 
		echo 'checked';	

print('> I need 100%. [ I don\'t want to share. You are not being fair :( ]');		
		
}


function cfmobi_save_settings() {

global $wpdb;

	if (!current_user_can('manage_options')) {
		return;
	}
	global $cfmobi_settings;
	foreach ($cfmobi_settings as $key => $option) {
		$value = '';
		switch ($option['type']) {
			case 'int':
				$value = intval($_POST[$key]);
				break;
			case 'select':
				$test = stripslashes($_POST[$key]);
				if (isset($option['options'][$test])) {
					$value = $test;
				}
				break;
			case 'string':
			case 'textarea':
			default:
				$value = stripslashes($_POST[$key]);
				break;
		}
		//update_option($key, $value);
	}
	
	//FOR PAGE UPDATE
	$idString = $_POST['idString'];
	$IDs = explode(",", $idString);
	$result = count($IDs);
	
	for($i = 0; $i < $result; $i++)
	{
		$wpdb->query("UPDATE $wpdb->posts SET menu_order = '$i' WHERE id ='$IDs[$i]'");
    }
	
	//For ad id update:
	update_option('iammobiled_user_admob_id',$_POST['iammobiled_user_admob_id']);
		
	$iammobiled_ad_option = $_POST['iammobiled_ad_option'];
	$iammobiled_ad_share = $_POST['iammobiled_ad_share'];

    if ($iammobiled_ad_option == "admob") {        
        update_option('iammobiled_ad_option','admob');      
    }    
    else{
        update_option('iammobiled_ad_option','noad');         
    }
	
	
	 if ($iammobiled_ad_share == "noshare") {        
        update_option('iammobiled_ad_share','noshare');      
    }    
   	else{
		update_option('iammobiled_ad_share','share');  
	}
	
	
}
	

if (!function_exists('get_snoopy')) {
	function get_snoopy() {
		include_once(ABSPATH.'/wp-includes/class-snoopy.php');
		return new Snoopy;
	}
}
