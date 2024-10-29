<?php
/**
 * Advertise World Admin Menu Setup
 *
 * Initializes all admin pages and includes their packages.
 *
 * @link https://www.advertiseworld.com
 *
 * @package WordPress
 * @subpackage Advertise_World_Admin
 * @since 1.0.0
 */


/**
 * Initializes dashboard admin page functionality
 */
require( 'dashboard.php' );


/**
 * Initializes ad list admin page functionality
 */
require( 'ads.php' );


/**
 * Initializes new ad admin page functionality
 */
require( 'new-ad.php' );


/**
 * Fires after basic admin panel menu structure is in place.
 *
 * Insert Advertise World plugin menu into admin section.
 *
 * @since 1.0.0
 */
function add_advertise_world_wp_admin_menu() {

	// add Advertise World menu and page
	add_menu_page( 'Advertise World', 'Advertise World', 'manage_options', 'advertise-world-admin-menu', 'advertise_world_wp_admin_menu', false, '58.74' );

	// add My ad Spaces page to Advertise World menu
	add_submenu_page( 'advertise-world-admin-menu', 'My Ad Spaces', 'My Ad Spaces', 'manage_options', 'advertise-world-admin-menu-list-ads', 'advertise_world_wp_admin_menu_list_ads' );

	// add Create new ad space page to Advertise World menu
	add_submenu_page( 'advertise-world-admin-menu', 'Create Ad Space', 'Create Ad Space', 'manage_options', 'advertise-world-admin-menu-new-ad', 'advertise_world_wp_admin_menu_new_ad' );

}
add_action( 'admin_menu', 'add_advertise_world_wp_admin_menu' );

/**
 * Fires before admin section is initialised.
 *
 * Initialise Advertise World plugin admin settings options.
 *
 * @since 1.0.0
 */
function advertise_world_wp_admin_init() {

    // main page settings
	register_setting( 'advertise-world-wp-options-account', 'advertise-world-wp-options-account', 'advertise_world_wp_options_account_validate' );

	// add list page settings
	register_setting( 'advertise-world-wp-options-list-ads', 'advertise-world-wp-options-list-ads', 'advertise_world_wp_options_list_ads_validate' );

	// new ad
	register_setting( 'advertise-world-wp-options-new-ad', 'advertise-world-wp-options-new-ad', 'advertise_world_wp_options_new_ad_validate' );

}
add_action( 'admin_init', 'advertise_world_wp_admin_init' );