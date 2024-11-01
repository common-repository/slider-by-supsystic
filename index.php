<?php

/**
 * Plugin Name: Slider by Supsystic
 * Description: Responsive Slider by Supsystic - the ultimate slideshow solution. Create stunning image, video and content sliders with professional templates and options
 * Version: 1.8.11
 * Author: supsystic.com
 * Author URI: http://supsystic.com
 * Text Domain: supsystic-slider
 **/

 //Fix RSC Class rename for PRO plugin
 function ssChangeProVersionNotice(){
	 global $pagenow;
	 if ( $pagenow == 'admin.php' || $pagenow == 'plugins.php' ) {
		 echo '<div class="notice notice-warning is-dismissible"><p><b>WARNING!</b> You using <b>OLD Slider by Supsystic PRO</b> version! For continued use and before activating the PRO plugin - please <b>UPDATE PRO VERSION</b>. Thank you.<br><b>You can download new compatible PRO version direct from this <a href="https://supsystic.com/pro/supsystic_slider_pro.zip">LINK</a></b>.</p></div>';
	 }
 }
 require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
 $proPluginPath = dirname(__FILE__);
 $proPluginPath = str_replace('slider-by-supsystic', 'supsystic_slider_pro', $proPluginPath);
 $proPluginPath = $proPluginPath . '/index.php';
 if (file_exists($proPluginPath)) {
	$pluginData = get_file_data($proPluginPath, array('Version' => 'Version'), false);
	if (!empty($pluginData['Version']) && version_compare($pluginData['Version'], '1.4.13', '<')) {
		add_action('admin_notices', 'ssChangeProVersionNotice');
		deactivate_plugins('supsystic_slider_pro/index.php');
	}
 }

require_once dirname(__FILE__) . '/app/SupsysticSlider.php';

if (!defined('SS_PLUGIN_URL')) {
	define('SS_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}
if (!defined('SS_PLUGIN_ADMIN_URL')) {
	define('SS_PLUGIN_ADMIN_URL', admin_url());
}

$supsysticSlider = new SupsysticSlider();
$supsysticSlider->run();
