<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for plugin TheMightyMo
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'tmm_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function tmm_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		
		// afragen's Github Updater plugin keeps the tmm-dashboard-customizations plugin up-to-date
		array(
			'name'      => 'Github Updater',
			'slug'      => 'github-updater',
			'required'  => false,
			'source'    => 'https://github.com/afragen/github-updater/archive/master.zip',
		),
		
		// Admin Bar Edit Links for Gravity Forms from wp.org repo
		array(
			'name'      => 'Admin Bar Edit Links for Gravity Forms',
			'slug'      => 'admin-bar-edit-links-for-gravity-forms',
			'required'  => false,
		),
		
		// Gravity Forms
		array(
			'name'         => 'Gravity Forms', // The plugin name.
			'slug'         => 'gravityforms', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/gravityforms.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/gravityforms.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		// Gravity Divi - Gravity Forms Styler for Divi
		array(
			'name'         => 'Gravity Divi - Gravity Forms Styler for Divi', // The plugin name.
			'slug'         => 'gravitydivi-forms-customizer-module', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/gravitydivi-forms-customizer-module-1.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/gravitydivi-forms-customizer-module-1.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		// ACF Pro
		array(
			'name'         => 'ACF Pro', // The plugin name.
			'slug'         => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/advanced-custom-fields-pro.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/advanced-custom-fields-pro.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		// Client Reports from wp.org repo
		array(
			'name'      => 'WP Client Reports',
			'slug'      => 'wp-client-reports',
			'required'  => false,
		),
		
		// WP Client Reports Pro
		array(
			'name'         => 'WP Client Reports Pro', // The plugin name.
			'slug'         => 'wp-client-reports-pro', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/wp-client-reports-pro.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/wp-client-reports-pro.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		// Gravity Divi - Gravity Forms Styler for Divi
		array(
			'name'         => 'Gravity Divi - Gravity Forms Styler for Divi', // The plugin name.
			'slug'         => 'wp-client-reports-pro', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/gravitydivi-forms-customizer-module-1', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/gravitydivi-forms-customizer-module-1', // If set, overrides default API URL and points to an external URL.
		),
		
		// wpmu-dev-dashboard
		array(
			'name'         => 'WPMU Dev Dashboard', // The plugin name.
			'slug'         => 'wpmu-dev-dashboard', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/wpmu-dev-dashboard.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/wpmu-dev-dashboard.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		// integrated-video-tutorials
		array(
			'name'         => 'Integrated Video Tutorials', // The plugin name.
			'slug'         => 'integrated-video-tutorials', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/integrated-video-tutorials.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/integrated-video-tutorials.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		// beehive-pro
		array(
			'name'         => 'Beehive Pro (Google Analytics)', // The plugin name.
			'slug'         => 'beehive-pro', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/beehive-pro.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/beehive-pro.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		// smartcrawl
		array(
			'name'         => 'Smartcrawl Pro (SEO)', // The plugin name.
			'slug'         => 'smartcrawl', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/smartcrawl.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/smartcrawl.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		// defender-pro
		array(
			'name'         => 'Defender Pro (Security)', // The plugin name.
			'slug'         => 'defender-pro', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/defender-pro.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/defender-pro.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		// branda-pro
		array(
			'name'         => 'Branda Pro (WP Admin Design)', // The plugin name.
			'slug'         => 'branda-pro', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/branda-pro.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/branda-pro.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		// hustle-pro
		array(
			'name'         => 'Hustle Pro (Email Marketing Popups)', // The plugin name.
			'slug'         => 'hustle-pro', // The plugin slug (typically the folder name).
			'source'       => 'https://tgmdownloads.themightymo.com/downloads/hustle-pro.zip', // The plugin source.
			'required'     => false, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://tgmdownloads.themightymo.com/downloads/hustle-pro.zip', // If set, overrides default API URL and points to an external URL.
		),
		
		
		
		// This is an example of the use of 'is_callable' functionality. A user could - for instance -
		// have WPSEO installed *or* WPSEO Premium. The slug would in that last case be different, i.e.
		// 'wordpress-seo-premium'.
		// By setting 'is_callable' to either a function from that plugin or a class method
		// `array( 'class', 'method' )` similar to how you hook in to actions and filters, TGMPA can still
		// recognize the plugin as being installed.
		/*array(
			'name'        => 'WordPress SEO by Yoast',
			'slug'        => 'wordpress-seo',
			'is_callable' => 'wpseo_init',
			'required'  => false,
),*/

	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'tmm',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => false,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

		/*
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'tmm' ),
			'menu_title'                      => __( 'Install Plugins', 'tmm' ),
			/* translators: %s: plugin name. * /
			'installing'                      => __( 'Installing Plugin: %s', 'tmm' ),
			/* translators: %s: plugin name. * /
			'updating'                        => __( 'Updating Plugin: %s', 'tmm' ),
			'oops'                            => __( 'Something went wrong with the plugin API.', 'tmm' ),
			'notice_can_install_required'     => _n_noop(
				/* translators: 1: plugin name(s). * /
				'This theme requires the following plugin: %1$s.',
				'This theme requires the following plugins: %1$s.',
				'tmm'
			),
			'notice_can_install_recommended'  => _n_noop(
				/* translators: 1: plugin name(s). * /
				'This theme recommends the following plugin: %1$s.',
				'This theme recommends the following plugins: %1$s.',
				'tmm'
			),
			'notice_ask_to_update'            => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'tmm'
			),
			'notice_ask_to_update_maybe'      => _n_noop(
				/* translators: 1: plugin name(s). * /
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'tmm'
			),
			'notice_can_activate_required'    => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'tmm'
			),
			'notice_can_activate_recommended' => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'tmm'
			),
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'tmm'
			),
			'update_link' 					  => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'tmm'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'tmm'
			),
			'return'                          => __( 'Return to Required Plugins Installer', 'tmm' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'tmm' ),
			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'tmm' ),
			/* translators: 1: plugin name. * /
			'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'tmm' ),
			/* translators: 1: plugin name. * /
			'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'tmm' ),
			/* translators: 1: dashboard link. * /
			'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'tmm' ),
			'dismiss'                         => __( 'Dismiss this notice', 'tmm' ),
			'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'tmm' ),
			'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'tmm' ),

			'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
		),
		*/
	);

	tgmpa( $plugins, $config );
}
