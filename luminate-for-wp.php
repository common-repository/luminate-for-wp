<?php
/*
Plugin Name: Luminate for WP
Plugin URI: http://www.jimmyscode.com/wordpress/luminate-for-wp/
Description: Automatically add Luminate JavaScript code into your site.
Version: 0.0.4
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/
if (!defined('LUMINFWP_PLUGIN_NAME')) {
	define('LUMINFWP_PLUGIN_NAME', 'Luminate for WP');
	// plugin constants
	define('LUMINFWP_VERSION', '0.0.4');
	define('LUMINFWP_SLUG', 'luminate-for-wp');
	define('LUMINFWP_LOCAL', 'luminfwp');
	define('LUMINFWP_OPTION', 'luminfwp');
	define('LUMINFWP_OPTIONS_NAME', 'luminateforwp_options');
	define('LUMINFWP_PERMISSIONS_LEVEL', 'manage_options');
	define('LUMINFWP_PATH', plugin_basename(dirname(__FILE__)));
	/* default values */
	define('LUMINFWP_DEFAULT_ENABLED', true);
	define('LUMINFWP_DEFAULT_URL', '');
	define('LUMINFWP_DEFAULT_LOAD_LOCATION', 'head');
	define('LUMINFWP_LOAD_LOCATIONS', 'head,body');
	/* option array member names */
	define('LUMINFWP_DEFAULT_ENABLED_NAME', 'enabled');
	define('LUMINFWP_DEFAULT_URL_NAME', 'url');
	define('LUMINFWP_DEFAULT_LOAD_LOCATION_NAME', 'loadlocation');
}
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', luminfwp_get_local()));
	}

	// localization to allow for translations
	add_action('init', 'luminfwp_translation_file');
	function luminfwp_translation_file() {
		$plugin_path = luminfwp_get_path() . '/translations';
		load_plugin_textdomain(luminfwp_get_local(), '', $plugin_path);
	}
	// tell WP that we are going to use new options
	// also, register the admin CSS file for later inclusion
	add_action('admin_init', 'luminfwp_options_init');
	function luminfwp_options_init() {
		register_setting(LUMINFWP_OPTIONS_NAME, luminfwp_get_option(), 'luminfwp_validation');
		register_luminfwp_admin_style();
	}
	// validation function
	function luminfwp_validation($input) {
		// validate all form fields
		if (!empty($input)) {
			$input[LUMINFWP_DEFAULT_ENABLED_NAME] = (bool)$input[LUMINFWP_DEFAULT_ENABLED_NAME];
			$input[LUMINFWP_DEFAULT_URL_NAME] = filter_var($input[LUMINFWP_DEFAULT_URL_NAME], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			$input[LUMINFWP_DEFAULT_LOAD_LOCATION_NAME] = sanitize_text_field($input[LUMINFWP_DEFAULT_LOAD_LOCATION_NAME]);
		}
		return $input;
	}

	// add Settings sub-menu
	add_action('admin_menu', 'luminfwp_plugin_menu');
	function luminfwp_plugin_menu() {
		add_options_page(LUMINFWP_PLUGIN_NAME, LUMINFWP_PLUGIN_NAME, LUMINFWP_PERMISSIONS_LEVEL, luminfwp_get_slug(), 'luminfwp_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	function luminfwp_page() {
		// check perms
		if (!current_user_can(LUMINFWP_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', luminfwp_get_local()));
		}
		?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo luminfwp_getimagefilename('arrow-forward.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo LUMINFWP_PLUGIN_NAME; _e(' by ', luminfwp_get_local()); ?><a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div><?php _e('You are running plugin version', luminfwp_get_local()); ?> <strong><?php echo LUMINFWP_VERSION; ?></strong>.</div>

			<?php /* http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-5-tabbed-navigation-for-your-settings-page--wp-24971 */ ?>
			<?php $active_tab = (isset($_GET['tab']) ? $_GET['tab'] : 'settings'); ?>
			<h2 class="nav-tab-wrapper">
			  <a href="?page=<?php echo luminfwp_get_slug(); ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', luminfwp_get_local()); ?></a>
				<a href="?page=<?php echo luminfwp_get_slug(); ?>&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', luminfwp_get_local()); ?></a>
			</h2>
			
			<form method="post" action="options.php">
				<?php settings_fields(LUMINFWP_OPTIONS_NAME); ?>
				<?php $options = luminfwp_getpluginoptions(); ?>
				<?php update_option(luminfwp_get_option(), $options); ?>
				<?php if ($active_tab == 'settings') { ?>
					<h3 id="settings"><img src="<?php echo luminfwp_getimagefilename('settings.png'); ?>" title="" alt="" height="61" width="64" align="absmiddle" /> <?php _e('Plugin Settings', luminfwp_get_local()); ?></h3>
					<table class="form-table" id="theme-options-wrap">
						<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', luminfwp_get_local()); ?>" for="<?php echo luminfwp_get_option(); ?>[<?php echo LUMINFWP_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', luminfwp_get_local()); ?></label></strong></th>
							<td><input type="checkbox" id="<?php echo luminfwp_get_option(); ?>[<?php echo LUMINFWP_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo luminfwp_get_option(); ?>[<?php echo LUMINFWP_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', luminfwp_checkifset(LUMINFWP_DEFAULT_ENABLED_NAME, LUMINFWP_DEFAULT_ENABLED, $options)); ?> /></td>
						</tr>
						<?php luminfwp_explanationrow(__('Is plugin enabled? Uncheck this to turn it off temporarily.', luminfwp_get_local())); ?>
						<?php luminfwp_getlinebreak(); ?>
						<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter your Luminate Publisher URL', luminfwp_get_local()); ?>" for="<?php echo luminfwp_get_option(); ?>[<?php echo LUMINFWP_DEFAULT_URL_NAME; ?>]"><?php _e('Enter Luminate Publisher URL here', luminfwp_get_local()); ?></label></strong></th>
							<td><input type="text" id="<?php echo luminfwp_get_option(); ?>[<?php echo LUMINFWP_DEFAULT_URL_NAME; ?>]" name="<?php echo luminfwp_get_option(); ?>[<?php echo LUMINFWP_DEFAULT_URL_NAME; ?>]" value="<?php echo luminfwp_checkifset(LUMINFWP_DEFAULT_URL_NAME, LUMINFWP_DEFAULT_URL, $options); ?>" /></td>								  
						</tr>
						<?php luminfwp_explanationrow(__('Copy and paste your Luminate publisher URL here. Ex: <strong>http://www.luminate.com/widget/async/1234567890</strong>', luminfwp_get_local())); ?>
						<?php luminfwp_getlinebreak(); ?>
						
						<tr valign="top"><th scope="row"><strong><label title="<?php _e('Code location', luminfwp_get_local()); ?>" for="<?php echo luminfwp_get_option(); ?>[<?php echo LUMINFWP_DEFAULT_LOAD_LOCATION_NAME; ?>]"><?php _e('Code location', luminfwp_get_local()); ?></label></strong></th>
							<td><select id="<?php echo luminfwp_get_option(); ?>[<?php echo LUMINFWP_DEFAULT_LOAD_LOCATION_NAME; ?>]" name="<?php echo luminfwp_get_option(); ?>[<?php echo LUMINFWP_DEFAULT_LOAD_LOCATION_NAME; ?>]">
							<?php $loadlocations = explode(",", LUMINFWP_LOAD_LOCATIONS);
								sort($loadlocations);
								foreach($loadlocations as $loadlocation) {
									echo '<option value="' . $loadlocation . '"' . selected($loadlocation, luminfwp_checkifset(LUMINFWP_DEFAULT_LOAD_LOCATION_NAME, LUMINFWP_DEFAULT_LOAD_LOCATION, $options), false) . '>' . $loadlocation . '</option>';
								} ?>
							</select></td>
						</tr>
						<?php luminfwp_explanationrow(__('Choose where you want the code to be loaded, head or body. Code will be placed before the closing head or body tag.', luminfwp_get_local())); ?>
					</table>
					<?php submit_button(); ?>
				<?php } else { ?>
					<h3 id="support"><img src="<?php echo luminfwp_getimagefilename('support.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Support', luminfwp_get_local()); ?></h3>
					<div class="support">
						<?php echo luminfwp_getsupportinfo(luminfwp_get_slug(), luminfwp_get_local()); ?>
						<small><?php _e('Disclaimer: This plugin is not affiliated with or endorsed by Luminate.', luminfwp_get_local()); ?></small>
					</div>
				<?php } ?>
			</form>
		</div>
		<?php }

	// main function and filter
	add_action('init', 'luminfwp_register_script');
	function luminfwp_register_script() {
		$options = luminfwp_getpluginoptions();
		$enabled = $options[LUMINFWP_DEFAULT_ENABLED_NAME];
		$url = $options[LUMINFWP_DEFAULT_URL_NAME];
		$loadlocation = $options[LUMINFWP_DEFAULT_LOAD_LOCATION_NAME];
		$filemodtime = '';
		
		if ($enabled) {
			// write code to .js file?
			$myFile = dirname(__FILE__) . '/js/luminate.js';
			if (!file_exists($myFile)) { // if file doesn't exist, then recreate JS
				if ($url) { // only check URL if we need to recreate the JS file
					$lfwpscript = '(function() {';
					$lfwpscript .= 'var a, s = document.getElementsByTagName("script")[0];';
					$lfwpscript .= 'a = document.createElement("script");';
					$lfwpscript .= 'a.type="text/javascript";  a.async = true;';
					$lfwpscript .= 'a.src = "' . $url . '";';
					$lfwpscript .= 's.parentNode.insertBefore(a, s);';
					$lfwpscript .= '})();';

					// create folder if it doesn't exist?
					// empty folders are not included in .zip files so the /js folder might not be in the install
					if (!file_exists(dirname(__FILE__) . '/js/')) {
						mkdir(dirname(__FILE__) . '/js/');
					}
					$fh = @fopen($myFile, 'w+');
					@fwrite($fh, $lfwpscript);
					@fclose($fh);
				}
			} else { // file exists already
				$filemodtime = "_" . date('njYHis', filemtime(dirname(__FILE__) . '/js/luminate.js'));
			}

      // enqueue script in head/foot regardless of whether it existed previously or not
			wp_enqueue_script(LUMINFWP_OPTION,
				plugins_url(luminfwp_get_path() . '/js/luminate.js'),
				array(), LUMINFWP_VERSION . $filemodtime, ($loadlocation == 'head' ? false : true));
		}
	}
	
	// show admin messages to plugin user
	add_action('admin_notices', 'luminfwp_showAdminMessages');
	function luminfwp_showAdminMessages() {
		// http://wptheming.com/2011/08/admin-notices-in-wordpress/
		global $pagenow;
		if (current_user_can(LUMINFWP_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == luminfwp_get_slug()) { // we are on this plugin's settings page
						$options = luminfwp_getpluginoptions();
						if (!empty($options)) {
							$enabled = (bool)$options[LUMINFWP_DEFAULT_ENABLED_NAME];
							if (!$enabled) {
								echo '<div id="message" class="error">' . LUMINFWP_PLUGIN_NAME . ' ' . __('is currently disabled.', luminfwp_get_local()) . '</div>';
							}
						}
					}
				}
			} // end page check
		} // end privilege check
	} // end admin msgs function
	// enqueue admin CSS if we are on the plugin options page
	add_action('admin_head', 'insert_luminfwp_admin_css');
	function insert_luminfwp_admin_css() {
		global $pagenow;
		if (current_user_can(LUMINFWP_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == luminfwp_get_slug()) { // we are on this plugin's settings page
						luminfwp_admin_styles();
					}
				}
			}
		}
	}
	// add helpful links to plugin page next to plugin name
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'luminfwp_plugin_settings_link');
	add_filter('plugin_row_meta', 'luminfwp_meta_links', 10, 2);
	
	function luminfwp_plugin_settings_link($links) {
		return luminfwp_settingslink($links, luminfwp_get_slug(), luminfwp_get_local());
	}
	function luminfwp_meta_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$links = array_merge($links,
			array(
				sprintf(__('<a href="http://wordpress.org/support/plugin/%s">Support</a>', luminfwp_get_local()), luminfwp_get_slug()),
				sprintf(__('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', luminfwp_get_local()), luminfwp_get_slug()),
				sprintf(__('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a>', luminfwp_get_local()), luminfwp_get_slug())
			));
		}
		return $links;	
	}
	// enqueue/register the admin CSS file
	function luminfwp_admin_styles() {
		wp_enqueue_style('luminfwp_admin_style');
	}
	function register_luminfwp_admin_style() {
		wp_register_style('luminfwp_admin_style',
			plugins_url(luminfwp_get_path() . '/css/admin.css'),
			array(),
			LUMINFWP_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'luminfwp_activate');
	function luminfwp_activate() {
		$options = luminfwp_getpluginoptions();
		update_option(luminfwp_get_option(), $options);
		
		// delete option when plugin is uninstalled
		register_uninstall_hook(__FILE__, 'uninstall_luminfwp_plugin');
	}
	function uninstall_luminfwp_plugin() {
		delete_option(luminfwp_get_option());
	}
		
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function luminfwp_getpluginoptions() {
		return get_option(luminfwp_get_option(), 
			array(
				LUMINFWP_DEFAULT_ENABLED_NAME => LUMINFWP_DEFAULT_ENABLED, 
				LUMINFWP_DEFAULT_URL_NAME => LUMINFWP_DEFAULT_URL,
				LUMINFWP_DEFAULT_LOAD_LOCATION_NAME => LUMINFWP_DEFAULT_LOAD_LOCATION
			));
	}
	
	// encapsulate these and call them throughout the plugin instead of hardcoding the constants everywhere
	function luminfwp_get_slug() { return LUMINFWP_SLUG; }
	function luminfwp_get_local() { return LUMINFWP_LOCAL; }
	function luminfwp_get_option() { return LUMINFWP_OPTION; }
	function luminfwp_get_path() { return LUMINFWP_PATH; }

	function luminfwp_settingslink($linklist, $slugname = '', $localname = '') {
		$settings_link = sprintf( __('<a href="options-general.php?page=%s">Settings</a>', $localname), $slugname);
		array_unshift($linklist, $settings_link);
		return $linklist;
	}
	function luminfwp_getsupportinfo($slugname = '', $localname = '') {
		$output = __('Do you need help with this plugin? Check out the following resources:', $localname);
		$output .= '<ol>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/support/plugin/%s">Support Forum</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://www.jimmyscode.com/wordpress/%s">Plugin Homepage / Demo</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/developers/">Development</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/changelog/">Changelog</a><br />', $localname), $slugname) . '</li>';
		$output .= '</ol>';
		
		$output .= sprintf( __('If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/%s/">rate it on WordPress.org</a>', $localname), $slugname);
		$output .= sprintf( __(' and click the <a href="http://wordpress.org/plugins/%s/#compatibility">Works</a> button. ', $localname), $slugname);
		$output .= '<br /><br /><br />';
		$output .= __('Your donations encourage further development and support. ', $localname);
		$output .= '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Support this plugin" width="92" height="26" /></a>';
		$output .= '<br /><br />';
		return $output;		
	}
	function luminfwp_checkifset($optionname, $optiondefault, $optionsarr) {
		return (isset($optionsarr[$optionname]) ? $optionsarr[$optionname] : $optiondefault);
	}
	function luminfwp_getlinebreak() {
	  echo '<tr valign="top"><td colspan="2"></td></tr>';
	}
	function luminfwp_explanationrow($msg = '') {
		echo '<tr valign="top"><td></td><td><em>' . $msg . '</em></td></tr>';
	}
	function luminfwp_getimagefilename($fname = '') {
		return plugins_url(luminfwp_get_path() . '/images/' . $fname);
	}
?>