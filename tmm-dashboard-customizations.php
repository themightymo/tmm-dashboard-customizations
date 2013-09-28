<?php
/*
 * Plugin Name: Dashboard Customizations by The Mighty Mo! Design Co.
 * Plugin URI: https://github.com/themightymo/tmm-dashboard-customizations
 * Description: Update the dashboard widgets.
 * Author: The Mighty Mo! Design Co. LLC
 * Author URI: http://www.themightymo.com/
 * License: GPLv2 (or later)
 * Version: 1.1
 */
 
// Add and Remove Dashboard widgets - via http://adamscottcreative.com/add-your-own-news-feed-to-wordpress-dashboard/
add_action('wp_dashboard_setup', 'tmm_dashboard_widgets');
function tmm_dashboard_widgets() {
	//global $wp_meta_boxes;  // use to get all the widget IDs
	//var_dump( $wp_meta_boxes['dashboard'] ); // use to get all the widget IDs
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	//remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
	
	
	remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );
	//remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	remove_meta_box( 'pb_backupbuddy_stats', 'dashboard', 'normal' );
	
	// Hide WP 3.3 "Upgrade" welcome panel.  Via http://wordpress.org/extend/plugins/hide-welcome-panel-for-multisite/
	$user_id = get_current_user_id();
	if ( 0 != get_user_meta( $user_id, 'show_welcome_panel', true ) )
		update_user_meta( $user_id, 'show_welcome_panel', 0 );
	
	
	// add a custom dashboard widget
	wp_add_dashboard_widget( 'dashboard_custom_feed', 'Updates from The Mighty Mo! Design Co.', 'tmm_dashboard_custom_feed_output' ); //add new RSS feed output
	
}
function tmm_dashboard_custom_feed_output() {
     echo '<div class="rss-widget">';
     wp_widget_rss_output(array(
          'url' => 'http://www.themightymo.com/feed',  //put your feed URL here
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
  echo 'Questions? Call The Mighty Mo! Design Co. at 612-293-8629 or email <a href="mailto:toby@themightymo.com">toby@themightymo.com</a>';
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


// Hide this plugin from the plugins list in the dashboard - via http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
function tmm_enqueue_admin_styles($hook) {
    if( 'plugins.php' != $hook )
        return;
    wp_register_style('tmm-enqueue-admin-styles', plugins_url('/admin-style.css', __FILE__));
    wp_enqueue_style( 'tmm-enqueue-admin-styles' );
}
add_action( 'admin_enqueue_scripts', 'tmm_enqueue_admin_styles' );



//Remove the WordPress logo from the admin bar
function tmm_tweaked_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}
add_action( 'wp_before_admin_bar_render', 'tmm_tweaked_admin_bar' ); 


// Add support link to admin bar - via http://www.onextrapixel.com/2012/02/24/taking-control-of-wordpress-3-0-admin-bar/
//add_action( 'wp_before_admin_bar_render', 'tmm_more_adminbar_tweaks');
function tmm_more_adminbar_tweaks() {
    global $wp_admin_bar;
	$wp_admin_bar->add_menu( 
		array( 
			'id' => 'tmm-support', //the view-site ID that refers to what we are doing.
			'title' => __( 'Stuck? Click here to sign up for support.' ), //the anchor text that links to homepage.
			'href' => 'http://www.themightymo.com/contact-us/' 
		) 
	);
}