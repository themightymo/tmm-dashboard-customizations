<?php
/*
 * Plugin Name: Dashboard Customizations by The Mighty Mo!
 * Plugin URI: https://github.com/themightymo/tmm-dashboard-customizations
 * Description: Auto-hides annoying dashboard widgets. Adds RSS feed from themightymo.com. Asks if you want to install common plugins (SEO, Analytics, Forms, etc).  Adds ability to update wp-login.php image. 
 * Author: The Mighty Mo!
 * Author URI: http://www.themightymo.com/
 * License: GPLv2 (or later)
 * Version: 1.9.10
 * GitHub Plugin URI: https://github.com/themightymo/tmm-dashboard-customizations
 * GitHub Branch: master
 * Roadmap: Add tgm plugin activation plugin that then calls this one (include the github updater plugin so I can keep sites up-to-date with this one).
 */
 

require_once dirname( __FILE__ ) . '/tgm-plugin-activation/class-tgm-plugin-activation.php';
require_once dirname( __FILE__ ) . '/tgm-plugin-activation/tmm-custom-activate-plugins.php';


 
// Add and Remove Dashboard widgets - via https://codex.wordpress.org/Dashboard_Widgets_API#Advanced:_Removing_Dashboard_Widgets and http://adamscottcreative.com/add-your-own-news-feed-to-wordpress-dashboard/
add_action('wp_dashboard_setup', 'tmm_dashboard_widgets');
function tmm_dashboard_widgets() {
	// BEGIN REMOVE ALL DASHBOARD WIDGETS
	global $wp_meta_boxes;
    $wp_meta_boxes['dashboard']['normal']['core'] = array();
    $wp_meta_boxes['dashboard']['side']['core'] = array();
    // END REMOVE ALL DASHBOARD WIDGETS
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
	remove_meta_box( 'wpe_dify_news_feed', 'dashboard', 'normal');
	remove_meta_box( 'ga_dashboard_widget', 'dashboard', 'normal');
	remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' );
	remove_meta_box( 'jetpack_summary_widget', 'dashboard', 'normal' );
	remove_meta_box( 'rg_forms_dashboard', 'dashboard', 'normal' );
	remove_meta_box( 'jp-banner', 'dashboard', 'normal' );
	remove_meta_box( 'email_log_dashboard_widget', 'dashboard', 'normal' );
	remove_meta_box( 'themefusion_news', 'dashboard', 'normal' );
	remove_meta_box( 'duplicate-post-notice', 'dashboard', 'normal' );
	
	wp_add_dashboard_widget('tmm_support_dashboard_widget', 'Need Help?', 'tmm_support_dashboard_widget_function');
	wp_add_dashboard_widget( 'dashboard_custom_feed', 'Updates from The Mighty Mo!', 'tmm_dashboard_custom_feed_output' );
	
	
	// Hide WP 3.3 "Upgrade" welcome panel for multisite.  Via http://wordpress.org/extend/plugins/hide-welcome-panel-for-multisite/
	$user_id = get_current_user_id();
	if ( 0 != get_user_meta( $user_id, 'show_welcome_panel', true ) )
		update_user_meta( $user_id, 'show_welcome_panel', 0 );
		
}

// Function that outputs the contents of the dashboard widget
function tmm_support_dashboard_widget_function( $post, $callback_args ) {
	
	echo "
		<img src='" . plugins_url('/support.png', __FILE__) . "' style='max-width:15%;display:inline-block;'>
		<div style='display:inline-block;max-width:66%;vertical-align: top;'>Hi!  It's me, Toby Cryns, from The Mighty Mo!  <br>I'm here to help.  Call or email with questions.
		<ul style='list-style: disc inside;'>
			<li>Email: <a href='mailto:toby@themightymo.com'>toby@themightymo.com</a></li>
			<li>Phone: (612) 293-8629</li>
		</ul></div>";
	
}

// Display themightymo.com blog feed on the Dashboard
function tmm_dashboard_custom_feed_output() {
     echo '<div class="rss-widget">';
     wp_widget_rss_output(array(
          'url' => 'https://www.themightymo.com/category/tmm-dashboard-customizations-feed/feed/',  //put your feed URL here
          'title' => 'Updates from The Mighty Mo! Design Co.',
          'items' => 4, //how many posts to show
          'show_summary' => 1,
          'show_author' => 0,
          'show_date' => 1
     ));
     echo "</div>";
}

// Change the text that appears in the bottom-left of the dashboard - via http://www.instantshift.com/2012/03/06/21-most-useful-wordpress-admin-page-hacks/
function tmm_custom_admin_footer_text () {
  echo 'Questions? Stuck? Email <a href="mailto:support@themightymo.com">support@themightymo.com</a> or call 612-293-8629 (9-5 CST).';
}
add_filter('admin_footer_text', 'tmm_custom_admin_footer_text');


// Change the dashboard widgets to a single column - via http://www.instantshift.com/2012/03/06/21-most-useful-wordpress-admin-page-hacks/
function tmm_single_screen_columns( $columns ) {
    $columns['dashboard'] = 1;
    return $columns;
}
add_filter( 'screen_layout_columns', 'tmm_single_screen_columns' );
function tmm_single_screen_dashboard() { return 1; }
add_filter( 'get_user_option_screen_layout_dashboard', 'tmm_single_screen_dashboard' );


// Display the featured images of posts in the "All Posts" view in the admin via http://www.instantshift.com/2012/03/06/21-most-useful-wordpress-admin-page-hacks/ 
// Add the posts and pages columns filter. They can both use the same function.
add_filter('manage_posts_columns', 'tmm_add_post_thumbnail_column', 5);
add_filter('manage_pages_columns', 'tmm_add_post_thumbnail_column', 5);

// Add the column
function tmm_add_post_thumbnail_column($cols){
  $cols['tmm_post_thumb'] = __('Featured');
  return $cols;
}

// Hook into the posts an pages column managing. Sharing function callback again.
add_action('tmm_manage_posts_custom_column', 'tmm_display_post_thumbnail_column', 5, 2);
add_action('tmm_manage_pages_custom_column', 'tmm_display_post_thumbnail_column', 5, 2);

// Grab featured-thumbnail size post thumbnail and display it.
function tmm_display_post_thumbnail_column($col, $id){
  switch($col){
    case 'tmm_post_thumb':
      if( function_exists('the_post_thumbnail') )
        echo the_post_thumbnail( 'admin-list-thumb' );
      else
        echo 'Not supported in theme';
      break;
  }
}


// Hide this plugin from the plugins list in the dashboard if you aren't The Mighty Mo! - via http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
function tmm_enqueue_admin_styles($hook) {
		
    if ( 'plugins.php' != $hook && 'index.php' != $hook ) 
    return;
  
    global $current_user;
	get_currentuserinfo();
    wp_register_style('tmm-enqueue-admin-styles', plugins_url('/admin-style.css', __FILE__));
    wp_enqueue_style( 'tmm-enqueue-admin-styles' );
    //wp_register_script('tmm-enqueue-admin-js', plugins_url('/admin-js.js', __FILE__));
    //wp_enqueue_script( 'tmm-enqueue-admin-js' );
}
add_action( 'admin_enqueue_scripts', 'tmm_enqueue_admin_styles' );


//Remove the WordPress logo from the admin bar
function tmm_tweaked_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}
add_action( 'wp_before_admin_bar_render', 'tmm_tweaked_admin_bar' );

// Add username to admin body class so we can hide/display stuff for certain users.
function tmm_add_username_to_admin_body_class( $classes ) {
	
    global $current_user;
	$current_user = wp_get_current_user();
    return "$classes username-" . $current_user->user_login;
    
}
add_filter( 'admin_body_class', 'tmm_add_username_to_admin_body_class' );



/*
	Add your own WP-LOGIN.PHP logo, Updated 2020.12.20
*/
add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {

    // Check function exists.
    if( function_exists('acf_add_options_sub_page') ) {

        // Add sub page.
        $child = acf_add_options_sub_page(array(
            'page_title'  => __('Login Design'),
            'menu_title'  => __('Login Design'),
			'parent_slug' 	=> 'options-general.php',
			'menu_slug'     => 'login_design',
        	'capability'    => 'manage_options',
            'redirect'    => false,
        ));
    }
}

/* 
	BEGIN ACF CUSTOM FIELDS
*/
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_59fce410672c5',
	'title' => 'Login Design',
	'fields' => array (
		array (
			'key' => 'field_59fce5e24eb58',
			'label' => 'Login Logo Image',
			'name' => 'login_logo_image',
			'type' => 'image',
			'value' => NULL,
			'instructions' => 'Must be 320px wide with any height.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'url',
			'preview_size' => 'full',
			'library' => 'all',
			'min_width' => 320,
			'min_height' => '',
			'min_size' => '',
			'max_width' => 320,
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array (
			'key' => 'field_59fdbc32e4496',
			'label' => 'Login Background Image',
			'name' => 'login_bg_image',
			'type' => 'image',
			'value' => NULL,
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'url',
			'preview_size' => 'full',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'login_design',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
/* 
	END ACF CUSTOM FIELDS
*/

function my_login_logo() { ?>
    <style type="text/css">
        html {
			height: 100%;
			margin: 0;
			padding: 0;
		}
        html body {
	        background: url("<?php 
		        if (class_exists('ACF')) {
				    if ( get_field('login_bg_image', 'option') ) { 
						the_field('login_bg_image', 'option'); 
					} else { 
						echo plugins_url('/login-bg.png', __FILE__);
					} 
				}
		?>");
			background-color: #000;
			background-position: center center;
        }
        html body {
	        display: table;
			margin: 0 auto;
			height: 100%;
        }
        body.login form {
	        background: #000;
			padding: 1em;
        }
        body #login {
	        padding: 0;
	        display: table-cell;
			vertical-align: middle;
        }
        body.login form .input, 
        body.login form input[type="checkbox"], 
        body.login input[type="text"] {
        	background: none;
        	font-size: 3em;
			color: #fff;
		}
        #login h1 a, 
        .login h1 a {
	        display:none;
            background-image: url("<?php 
	            if (class_exists('ACF')) {
		            the_field('login_logo_image', 'option'); 
		        } 
		        ?>");
			height:65px;
			width:320px;
			background-size: 320px 65px;
			background-repeat: no-repeat;
        }
        .login h1::before {
		    content: url('<?php 
			    if (class_exists('ACF')) {
			        if ( get_field('login_logo_image', 'option') ) { 
				        the_field('login_logo_image', 'option'); 
				    } else { 
					    // do nothing
					} 
				}
				?>');
    	}
    	/* Jetpack's Login Protection */
    	input#jetpack_protect_answer {
		    color: #fff;
		}
        body.wp-core-ui .button-primary {
	        background: #2196F3;
	        text-transform: uppercase;
	        width:50%;
        }
        body.login,
		body.login label,
		body.login *,
		body.login #backtoblog a, 
		body.login #nav a {
			color: #fff;
		}
		body.login input[name=jetpack_protect_num] { 
		    color: #000;
		}
		.login .message {
			background-color: inherit !important;
		}
		.login #login_error {
			background-color: inherit !important;
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );
function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );
/*
	END UPDATE THE WP-LOGIN.PHP LOGO
*/

// Remove plugin editor and theme editor from WP Dashboard menus
add_action('admin_init', 'tmm_remove_menu_elements', 102);
function tmm_remove_menu_elements(){
	remove_submenu_page( 'themes.php', 'theme-editor.php' );
	remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
}
