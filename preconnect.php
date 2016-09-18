<?php
/*
Plugin Name: Preconnect
Plugin URI: https://thecustomizewindows.com/
Description: Add preconnect crossorgin meta tags to your site.
Version: 1.0
Author: Abhishek_ghosh
Contributors: Abhishek_ghosh
Author URI: https://thecustomizewindows.com/
License: GPLv2 or later
*/
if (!defined('PC_PLUGIN_NAME')) {
	// plugin constants
	define('PC_PLUGIN_NAME', 'Preconnect');
	define('PC_VERSION', '0.1.0');
	define('PC_SLUG', 'preconnect');
	define('PC_LOCAL', 'pc');
	define('PC_OPTION', 'pc');
	define('PC_OPTIONS_NAME', 'pc_options');
	define('PC_PERMISSIONS_LEVEL', 'manage_options');
	define('PC_PATH', plugin_basename(dirname(__FILE__)));
	/* default values */
	define('PC_DEFAULT_ENABLED', true);
	define('PC_DEFAULT_TEXT', '');
	/* option array member names */
	define('PC_DEFAULT_ENABLED_NAME', 'enabled');
	define('PC_DEFAULT_TEXT_NAME', 'domainstoadd');
}
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', pc_get_local()));
	}

	// localization to allow for translations
	add_action('init', 'pc_translation_file');
	function pc_translation_file() {
		$plugin_path = pc_get_path() . '/translations';
		load_plugin_textdomain(pc_get_local(), '', $plugin_path);
	}
	// tell WP that we are going to use new options
	// also, register the admin CSS file for later inclusion
	add_action('admin_init', 'pc_options_init');
	function pc_options_init() {
		register_setting(PC_OPTIONS_NAME, pc_get_option(), 'pc_validation');
		register_pc_admin_style();
	}
	// validation function
	function pc_validation($input) {
		// validate all form fields
		if (!empty($input)) {
			$input[PC_DEFAULT_ENABLED_NAME] = (bool)$input[PC_DEFAULT_ENABLED_NAME];
			$input[PC_DEFAULT_TEXT_NAME] = wp_kses_post($input[PC_DEFAULT_TEXT_NAME]);
		}
		return $input;
	} 

	// add Settings sub-menu
	add_action('admin_menu', 'pc_plugin_menu');
	function pc_plugin_menu() {
		add_options_page(PC_PLUGIN_NAME, PC_PLUGIN_NAME, PC_PERMISSIONS_LEVEL, pc_get_slug(), 'pc_page');
	}
	// plugin settings page
	function pc_page() {
		// check perms
		if (!current_user_can(PC_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', pc_get_local()));
		}
		?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo pc_getimagefilename('globe.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo PC_PLUGIN_NAME; _e(' by ', pc_get_local()); ?><a href="https://thecustomizewindows.com/">TheCustomizeWindows</a></h2>
			<div><?php _e('You are running plugin version', pc_get_local()); ?> <strong><?php echo PC_VERSION; ?></strong>.</div>

			<?php $active_tab = (isset($_GET['tab']) ? $_GET['tab'] : 'settings'); ?>
			<h2 class="nav-tab-wrapper">
			  <a href="?page=<?php echo pc_get_slug(); ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', pc_get_local()); ?></a>
				<a href="?page=<?php echo pc_get_slug(); ?>&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', pc_get_local()); ?></a>
			</h2>
			
			<form method="post" action="options.php">
				<?php settings_fields(PC_OPTIONS_NAME); ?>
				<?php $options = pc_getpluginoptions(); ?>
				<?php update_option(pc_get_option(), $options); ?>
				<?php if ($active_tab == 'settings') { ?>
					<h3 id="settings"><img src="<?php echo pc_getimagefilename('settings.png'); ?>" title="" alt="" height="61" width="64" align="absmiddle" /> <?php _e('Plugin Settings', pc_get_local()); ?></h3>
					<table class="form-table" id="theme-options-wrap">
						<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', pc_get_local()); ?>" for="<?php echo pc_get_option(); ?>[<?php echo PC_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', pc_get_local()); ?></label></strong></th>
							<td><input type="checkbox" id="<?php echo pc_get_option(); ?>[<?php echo PC_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo pc_get_option(); ?>[<?php echo PC_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', pc_checkifset(PC_DEFAULT_ENABLED_NAME, PC_DEFAULT_ENABLED, $options)); ?> /></td>
						</tr>
						<?php pc_explanationrow(__('Is plugin enabled? Uncheck this to turn it off temporarily.', pc_get_local())); ?>
						<?php pc_getlinebreak(); ?>
						<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter URLs to be prefetched', pc_get_local()); ?>" for="<?php echo pc_get_option(); ?>[<?php echo PC_DEFAULT_TEXT_NAME; ?>]"><?php _e('Enter URLs to be prefetched', pc_get_local()); ?></label></strong></th>
							<td><textarea rows="12" cols="75" id="<?php echo pc_get_option(); ?>[<?php echo PC_DEFAULT_TEXT_NAME; ?>]" name="<?php echo pc_get_option(); ?>[<?php echo PC_DEFAULT_TEXT_NAME; ?>]"><?php echo pc_checkifset(PC_DEFAULT_TEXT_NAME, PC_DEFAULT_TEXT, $options); ?></textarea></td>
						</tr>
						<?php pc_explanationrow(__('Type the URLs you want to be preconnect by visitors\' browsers. <strong>One URL per line.</strong> Include prefix (such as <strong>https://</strong>) <br /><strong>These domains will be preconnected in addition to the domains already linked on your pages.</strong>', pc_get_local())); ?>
					</table>
					<?php submit_button(); ?>
				<?php } else { ?>
					<h3 id="support"><img src="<?php echo pc_getimagefilename('support.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Support', pc_get_local()); ?></h3>
					<div class="support">
						<?php echo pc_getsupportinfo(pc_get_slug(), pc_get_local()); ?>
						<small><?php _e('Disclaimer: This plugin is not affiliated with or endorsed by W3C.', pc_get_local()); ?></small>
					</div>
				<?php } ?>
			</form>
		</div>
		<?php }

	// main function and filter
	add_action('wp_head', 'pc_prefetch', 1);
	function pc_prefetch() {
		$options = pc_getpluginoptions();
		if (!empty($options)) {
			$enabled = (bool)$options[PC_DEFAULT_ENABLED_NAME];
		} else {
			$enabled = PC_DEFAULT_ENABLED;
		}
		$result = '';
		
		if ($enabled) {
			$result = '<meta http-equiv="x-dns-prefetch-control" content="on">';
		
			$tta = explode("\n", $options[PC_DEFAULT_TEXT_NAME]);
			if (!empty($tta)) {
				$tta = array_map('esc_url', $tta); 
				foreach ($tta as $pcdomain) {
					$result .= '<link rel="preconnect" href="' . $pcdomain . '" crossorigin>';
				}
			}
			echo $result;
		} // end enabled check
	} // end function
	
	// show admin messages to plugin user
	add_action('admin_notices', 'pc_showAdminMessages');
	function pc_showAdminMessages() {
		// http://wptheming.com/2011/08/admin-notices-in-wordpress/
		global $pagenow;
		if (current_user_can(PC_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == pc_get_slug()) { // we are on this plugin's settings page
						$options = pc_getpluginoptions();
						if (!empty($options)) {
							$enabled = (bool)$options[PC_DEFAULT_ENABLED_NAME];
							if (!$enabled) {
								echo '<div id="message" class="error">' . PC_PLUGIN_NAME . ' ' . __('is currently disabled.', pc_get_local()) . '</div>';
							}
						}
					}
				}
			} // end page check
		} // end privilege check
	} // end admin msgs function
	// enqueue admin CSS if we are on the plugin options page
	add_action('admin_head', 'insert_pc_admin_css');
	function insert_pc_admin_css() {
		global $pagenow;
		if (current_user_can(PC_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == pc_get_slug()) { // we are on this plugin's settings page
						pc_admin_styles();
					}
				}
			}
		}
	}
	// add helpful links to plugin page next to plugin name
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'pc_plugin_settings_link');
	add_filter('plugin_row_meta', 'pc_meta_links', 10, 2);
	
	function pc_plugin_settings_link($links) {
		return pc_settingslink($links, pc_get_slug(), pc_get_local());
	}
	function pc_meta_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$links = array_merge($links,
			array(
				sprintf(__('<a href="http://wordpress.org/support/plugin/%s">Support</a>', pc_get_local()), pc_get_slug()),
				sprintf(__('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', pc_get_local()), pc_get_slug()),
				sprintf(__('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a>', pc_get_local()), pc_get_slug())
			));
		}
		return $links;	
	}
	// enqueue/register the admin CSS file
	function pc_admin_styles() {
		wp_enqueue_style('pc_admin_style');
	}
	function register_pc_admin_style() {
		wp_register_style('pc_admin_style',
			plugins_url(pc_get_path() . '/css/admin.css'),
			array(),
			PC_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'pc_activate');
	function pc_activate() {
		$options = pc_getpluginoptions();
		update_option(pc_get_option(), $options);
		
		// delete option when plugin is uninstalled
		register_uninstall_hook(__FILE__, 'uninstall_pc_plugin');
	}
	function uninstall_pc_plugin() {
		delete_option(pc_get_option());
	}
		
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function pc_getpluginoptions() {
		return get_option(pc_get_option(), 
			array(
				PC_DEFAULT_ENABLED_NAME => PC_DEFAULT_ENABLED, 
				PC_DEFAULT_TEXT_NAME => PC_DEFAULT_TEXT
			));
	}
	
	// encapsulate these and call them throughout the plugin instead of hardcoding the constants everywhere
	function pc_get_slug() { return PC_SLUG; }
	function pc_get_local() { return PC_LOCAL; }
	function pc_get_option() { return PC_OPTION; }
	function pc_get_path() { return PC_PATH; }

	function pc_settingslink($linklist, $slugname = '', $localname = '') {
		$settings_link = sprintf( __('<a href="options-general.php?page=%s">Settings</a>', $localname), $slugname);
		array_unshift($linklist, $settings_link);
		return $linklist;
	}
	function pc_getsupportinfo($slugname = '', $localname = '') {
		$output = __('Do you need help with this plugin? Check out the following resources:', $localname);
		$output .= '<ol>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/support/plugin/%s">Support Forum</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://thecustomizewindows.com/%s">Plugin Homepage</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/developers/">Development</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/changelog/">Changelog</a><br />', $localname), $slugname) . '</li>';
		$output .= '</ol>';
		
		$output .= sprintf( __('If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/%s/">rate it on WordPress.org</a>', $localname), $slugname);
		$output .= sprintf( __(' and click the <a href="http://wordpress.org/plugins/%s/#compatibility">Works</a> button. ', $localname), $slugname);
		$output .= '<br /><br /><br />';
		$output .= __('Your donations will be encourage in future. ', $localname);
		$output .= '<a href="https://thecustomizewindows.com/"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Support this plugin" width="92" height="26" /></a>';
		$output .= '<br /><br />';
		return $output;		
	}
	function pc_checkifset($optionname, $optiondefault, $optionsarr) {
		return (isset($optionsarr[$optionname]) ? $optionsarr[$optionname] : $optiondefault);
	}
	function pc_getlinebreak() {
	  echo '<tr valign="top"><td colspan="2"></td></tr>';
	}
	function pc_explanationrow($msg = '') {
		echo '<tr valign="top"><td></td><td><em>' . $msg . '</em></td></tr>';
	}
	function pc_getimagefilename($fname = '') {
		return plugins_url(pc_get_path() . '/images/' . $fname);
	}
?>
