<?php
/**
 * Advertise World Admin New Advert Page
 *
 * Displays the New Advert Page in the admin section.
 *
 * @link https://www.advertiseworld.com
 *
 * @package WordPress
 * @subpackage Advertise_World_Admin_New_Ad
 * @since 1.0.0
 */


/**
 * Parse or designates advert ID to be edited or created.
 *
 * Retrives editing advert ID from get paramaterd or creates new sequential ID
 *
 * @since 1.0.0
 *
 * @return int Advert ID
 */
function advertise_world_wp_options_new_ad_get_id() {
	$existing_ads = get_option( 'advertise-world-wp-options-new-ad' );

	if ( $existing_ads && count( $existing_ads ) > 0 ) {

		if ( ! is_null( $_GET['edit-ad'] ) && $existing_ads[ $_GET['edit-ad'] ] ) {
			$_SESSION['advertise-world-wp-plugin-editing-ad-id'] = $_GET['edit-ad'];
		} elseif ( $_SESSION['advertise-world-wp-plugin-editing-ad-id'] != null ) {
			return $_SESSION['advertise-world-wp-plugin-editing-ad-id'];
		} else {
			end( $existing_ads );
			$last_id                                             = (int) key( $existing_ads );
			$_SESSION['advertise-world-wp-plugin-editing-ad-id'] = $last_id + 1;
		}
	} else {
		$_SESSION['advertise-world-wp-plugin-editing-ad-id'] = 0;
	}

	return $_SESSION['advertise-world-wp-plugin-editing-ad-id'];
}


/**
 * Displays dropdown box of available ad spaces.
 *
 * @since 1.0.0
 */
function advertise_world_wp_options_new_ad_title() {

	?>
	<div style="margin-bottom: 25px;">
	<span style="margin-right: 4%;">Advertise World Ad Space Name:</span>

	<input type="hidden" id="advertise-world-wp-options-new-ad-section-id"
	       value="<?php echo $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ?>"/>
	<select id="advertise-world-wp-options-new-ad-section-title"
	        name="advertise-world-wp-options-new-ad[add][<?php echo $_SESSION['advertise-world-wp-plugin-editing-ad-id']; ?>][title]"
	        style="text-align: center; max-width: 300px; width: 100%;"><?php

		$account_options = get_option( 'advertise-world-wp-options-account' );
		$ad_options      = get_option( 'advertise-world-wp-options-new-ad' );

		$this_ad = $ad_options[ $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ];

		require_once ( dirname(__FILE__) . "/../advertise-world-wp.php" );

		$host = "www.advertiseworld.com";
		$protocol = "https";

		/*
		$alternate_hostname = @fopen( "/var/www/kritter_adsserver.conf", 'r' );
		if ($alternate_hostname) {
			@fclose($alternate_hostname);
			$host = "test.advertiseworld.com";
			$protocol = "http";
		}*/

		$api_url = $protocol . "://".$host."/portal/api/v1/publisher/adspaces/list?email=" . $account_options['account'];
		$result = Advertise_World_Get_URL( $api_url );
		if ($result) {
            $adspaces = json_decode($result, true);

            $chosen_id = '';
            if (is_array($this_ad) && array_key_exists("title", $this_ad)) {
                $chosen_id = $this_ad["title"];
            }

            if ($adspaces['accountExist']) {
                foreach ($adspaces['adSpaces'] as $adSpace) {
                    ?>
                    <option value="<?php echo $adSpace['guid'] ?>" <?php if ($chosen_id === $adSpace['guid']) {
                        echo 'selected';
                    } ?> ><?php echo $adSpace['name'] ?></option><?php
                }
            } else {
                ?>
                <option value="">No Ad Spaces Available</option><?php
            }
        } else {
            ?>
            <option value="">Error Connecting to Advertise World</option><?php
        }
		?></select></div><?php
}

/**
 * Displays responsive ad height selection options
 *
 * @since 1.0.3
 */
function advertise_world_wp_options_new_ad_responsive_choice() {
	$ad_options    = get_option( 'advertise-world-wp-options-new-ad' );
	$this_ad       = $ad_options[ $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ];
	$height_choice = '';
	if ( is_array( $this_ad ) && array_key_exists( "height-choice", $this_ad ) ) {
		$height_choice = $this_ad["height-choice"];
	}
	?>
	<div style="margin-bottom: 25px;">
		<span style="margin-right: 4%;">Preferred Height For Responsive Ad:</span>
		<select id="advertise-world-wp-options-new-ad-section-height-choice"
		        name="advertise-world-wp-options-new-ad[add][<?php echo $_SESSION['advertise-world-wp-plugin-editing-ad-id']; ?>][height-choice]"
		        style="text-align: center; max-width: 300px; width: 100%;">
			<option value="shortest" <?php if ($height_choice === "shortest") { echo "selected"; } ?>>Shortest</option>
			<option value="medium" <?php if ($height_choice === "medium") { echo "selected"; } ?>>Medium</option>
			<option value="tallest" <?php if ($height_choice === "tallest") { echo "selected"; } ?>>Tallest</option>
		</select>
	</div>
	<?php
}

/**
 * Displays fixed size ad selection options
 *
 * @since 1.3.2
 */
function advertise_world_wp_options_new_ad_fixed_choice() {
	$ad_options    = get_option( 'advertise-world-wp-options-new-ad' );
	$this_ad       = $ad_options[ $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ];
	$fixed_size = '';
	if ( is_array( $this_ad ) && array_key_exists( "fixed-size", $this_ad ) ) {
		$fixed_size = $this_ad["fixed-size"];
	}
	?>
	<div style="margin-bottom: 25px;">
		<span style="margin-right: 4%;">Use Fixed Size Ad:</span>
		<select id="advertise-world-wp-options-new-ad-section-fixed-size"
		        name="advertise-world-wp-options-new-ad[add][<?php echo $_SESSION['advertise-world-wp-plugin-editing-ad-id']; ?>][fixed-size]"
		        style="text-align: center; max-width: 300px; width: 100%;">
			<option value="728x90" <?php if ($fixed_size === "728x90") { echo "selected"; } ?>>Banner 728x90</option>
			<option value="300x250" <?php if ($fixed_size === "300x250" || $fixed_size === "responsive") { echo "selected"; } ?>>Box 300x250</option>
			<option value="160x600" <?php if ($fixed_size === "160x600") { echo "selected"; } ?>>Skyscraper 160x600</option>
		</select>
	</div>
	<?php
}

/**
 * Displays advert type radio controlls.
 *
 * Displays all advert type selection controlls available within theme.
 *
 * @since 1.0.0
 */
function advertise_world_wp_options_new_ad_type() {
	?>
	<table cellspacing="0" cellpadding="10" style="border: 1px solid #AAAAAA; width: 100%; margin-bottom: 35px;">
		<tr style="text-align: left;  height: 50px;">
			<th>Ad Placement Area</th>
		</tr>
		<?php

		$existing_ads = get_option( 'advertise-world-wp-options-new-ad' );
		$options      = Array(
			Array( 'Header', 'header' ),
			Array( 'Content', 'content' ),
			Array( 'Footer', 'footer' ),
			Array( 'Post', 'post' )
		);

		$theme_sidebars = array();
		foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar => $registered_sidebar ) {
			if ( ! ( false !== strpos( $registered_sidebar['class'], 'inactive-sidebar' ) || 'orphaned_widgets' == substr( $sidebar, 0, 16 ) ) ) {
				$theme_sidebars[ $sidebar ] = $registered_sidebar;
			}
		}

		$chosen_type = '';
		if ( is_array( $existing_ads[ $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ] ) && array_key_exists( "type", $existing_ads[ $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ] ) ) {
			$chosen_type = $existing_ads[ $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ]['type'];
		}

		echo '<tr style="background-color: white; height: 50px;"><td>';

		advertise_world_wp_options_display_placements( $options, $chosen_type );

		echo '</td></tr>';
		echo '<tr style="background-color: white; height: 50px;"><th valign="bottom" style="text-align: left;">Theme Specific Areas:</th></tr>';


		echo '<tr style="background-color: white; height: 50px;"><td>';

		$options = Array();
		foreach ( $theme_sidebars as $theme_sidebar ) {
			array_push( $options, array( $theme_sidebar['name'], $theme_sidebar['id'] ) );
		}

		advertise_world_wp_options_display_placements( $options, $chosen_type );

		echo '</td></tr>';

		?>
	</table>
	<?php

}


/**
 * Displays ad type radio buttons from array.
 *
 * @since 1.0.0
 *
 * @param array $placements Array of ad type names and values
 * @param string $chosen Type radio value selected
 */
function advertise_world_wp_options_display_placements( $placements, $chosen ) {
	foreach ( $placements as $placement ) {
		echo ' ';
		echo '<label>';
		echo '<input type="radio" id="advertise-world-wp-options-new-ad-section-type" onClick="advertise_world_type_updated(this);" name="advertise-world-wp-options-new-ad[add][' . $_SESSION['advertise-world-wp-plugin-editing-ad-id'] . '][type]" value="' . $placement[1] . '" ' . ( $chosen == $placement[1] ? 'checked' : '' ) . ' />';
		echo ' ';
		echo $placement[0];
		echo '</label>';
		echo ' ';
	}
}

/**
 * Returns advertise-world-wp-options-main-account field content
 *
 * @since 1.0.0
 */
function advertise_world_wp_options_new_ad_placement() {

	require_once( ABSPATH . 'wp-admin/includes/widgets.php' );

	$options = Array(
		Array( 'header', 'advertise_world_wp_options_default_above_below' ),
		Array( 'content', 'advertise_world_wp_options_default_above_below' ),
		Array( 'footer', 'advertise_world_wp_options_default_above_below' ),
		Array( 'post', 'advertise_world_wp_options_default_above_below' )
	);

	$theme_sidebars = array();
	foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar => $registered_sidebar ) {
		if ( ! ( false !== strpos( $registered_sidebar['class'], 'inactive-sidebar' ) || 'orphaned_widgets' == substr( $sidebar, 0, 16 ) ) ) {
			$theme_sidebars[ $sidebar ] = $registered_sidebar;
		}
	}

	foreach ( $theme_sidebars as $theme_sidebar ) {
		array_push( $options, array( $theme_sidebar['id'], 'advertise_world_wp_options_default_widget_placement' ) );
	}

	echo '<table cellspacing="0" cellpadding="10" style="border: 1px solid #AAAAAA; width: 100%;">' .
	     '<tr style="text-align: left; height: 50px;">' .
	     '<th>Position within specified area</th>' .
	     '</tr>' .
	     '<tr style="background-color: white; min-height: 50px; height: 100%;">' .
	     '<td>';
	echo '<div id="advertise-world-wp-options-new-ad-section-placement-container">';

	global $wp_registered_widgets, $wp_registered_widget_controls;

	$sort = $wp_registered_widgets;
	usort( $sort, '_sort_name_callback' );
	$done = array();

	echo '<div id="available-widgets" style="display: none;">';

	foreach ( $sort as $widget ) {


		if ( $widget['name'] == 'Advertise World' ) {

			if ( in_array( $widget['callback'], $done, true ) ) // We already showed this multi-widget
			{
				continue;
			}

			$sidebar = is_active_widget( $widget['callback'], $widget['id'], false, false );
			$done[]  = $widget['callback'];

			if ( ! isset( $widget['params'][0] ) ) {
				$widget['params'][0] = array();
			}

			$args = array( 'widget_id' => $widget['id'], 'widget_name' => $widget['name'], '_display' => 'template' );

			if ( isset( $wp_registered_widget_controls[ $widget['id'] ]['id_base'] ) && isset( $widget['params'][0]['number'] ) ) {
				$id_base            = $wp_registered_widget_controls[ $widget['id'] ]['id_base'];
				$args['_temp_id']   = "$id_base-__i__";
				$args['_multi_num'] = next_widget_id_number( $id_base );
				$args['_add']       = 'multi';
			} else {
				$args['_add'] = 'single';
				if ( $sidebar ) {
					$args['_hide'] = '1';
				}
			}


			$args                     = wp_list_widget_controls_dynamic_sidebar( array(
				0 => $args,
				1 => $widget['params'][0]
			) );
			$args[0]['before_widget'] = substr_replace( $args[0]['before_widget'], " widget-in-question", - 2, 0 );
			call_user_func_array( 'wp_widget_control', $args );
		}
	}

	echo '</div>';

	foreach ( $options as $option ) {
		call_user_func( $option[1], $option[0] );
	}

	echo '</div>';

	echo '</td></tr>';
	echo '</table>';

}


/**
 * Displays placement radio options for a specified injected ad.
 *
 * @since 1.0.0
 *
 * @param string $id Type value
 */
function advertise_world_wp_options_default_above_below( $id ) {

	$existing_ads = get_option( 'advertise-world-wp-options-new-ad' );
	if ( ! is_array( $existing_ads ) ) {
		$existing_ads = array();
	}

	?>
	<div id="advertise-world-wp-options-new-ad-section-placement-<?php echo $id; ?>" style="display:none;">
		<label>
			<input type="radio" class="advertise-world-wp-options-new-ad-section-placement"
			       id="advertise-world-wp-options-new-ad-section-placement"
			       name="advertise-world-wp-options-new-ad[add][<?php echo $_SESSION['advertise-world-wp-plugin-editing-ad-id']; ?>][placement]"
			       value="top" <?php if ( array_key_exists( $_SESSION['advertise-world-wp-plugin-editing-ad-id'], $existing_ads ) && array_key_exists( 'type', $existing_ads[$_SESSION['advertise-world-wp-plugin-editing-ad-id']] ) ) {
				if ( $existing_ads[ $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ]['placement'] == 'top' && $existing_ads[ $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ]['type'] == $id ) {
					echo 'checked';
				}
			} ?> />
			Top
		</label>
		<label>
			<input type="radio" class="advertise-world-wp-options-new-ad-section-placement"
			       id="advertise-world-wp-options-new-ad-section-placement"
			       name="advertise-world-wp-options-new-ad[add][<?php echo $_SESSION['advertise-world-wp-plugin-editing-ad-id']; ?>][placement]"
			       value="bottom" <?php if ( array_key_exists( $_SESSION['advertise-world-wp-plugin-editing-ad-id'], $existing_ads ) && array_key_exists( 'type', $existing_ads[$_SESSION['advertise-world-wp-plugin-editing-ad-id']] ) ) {
				if ( $existing_ads[ $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ]['placement'] == 'bottom' && $existing_ads[ $_SESSION['advertise-world-wp-plugin-editing-ad-id'] ]['type'] == $id) {
					echo 'checked';
				}
			} ?> />
			Bottom
		</label>
	</div>
	<?php

}


/**
 * Displays placement options for a specified widget area.
 *
 * @since 1.0.0
 *
 * @param string $id Type value
 */
function advertise_world_wp_options_default_widget_placement( $id ) {

	?>
	<div id="advertise-world-wp-options-new-ad-section-placement-<?php echo $id; ?>" style="display:none;">
		<b>Instructions:</b>
		<ol>
			<li>Navigate to: Appearance > Widgets.</li>

			<li>Add an Advertise World widget block (via the drag and drop functionality) to your sidebar.</li>

			<li>Please copy and paste <span class="advertise-world-admin-menu-new-ad-adspace-id"></span> into the "Ad
				Space" field inside the widget.
			</li>
		</ol>
	</div>
	<?php

}

/**
 * Displays Advertise World plugin New Ad page.
 *
 * @since 1.0.0
 * @since 1.1.0 Images are inline base64, Ad Blocker plus was causing issues with images loading.
 */
function advertise_world_wp_admin_menu_new_ad() {

	advertise_world_wp_options_new_ad_get_id();

	wp_enqueue_script( 'admin-widgets' );

	?><script type="text/javascript"><?php
	readfile ( dirname( __FILE__ ) . "/js/new-da.js" );
	?></script><?php
	if ( function_exists( 'wp_is_mobile' ) && wp_is_mobile() ) {
		wp_enqueue_script( 'jquery-touch-punch' );
	}

	?>
	<div class="wrap" style=" margin: 0 5%;">
		<img src="data:image/png;base64,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"
		     style="margin: 10px 0 25px 0; max-width: 100%;">
		<?php settings_errors(); ?>
		<h2 class="nav-tab-wrapper wp-clearfix" style="margin-bottom: 25px;"><a href="/wp-admin/nav-menus.php"
		                                                                        class="nav-tab nav-tab-active">Create
				New Ad
				Space</a></h2>

		<div class="advertise-world-admin-container" style="padding-left: 18px;">
		<form method="post" action="options.php">
			<?php settings_fields( 'advertise-world-wp-options-new-ad' ); ?>
			<table style="width: 100%;">
				<tr>
					<td>
						<?php advertise_world_wp_options_new_ad_title();
						advertise_world_wp_options_new_ad_responsive_choice();
						advertise_world_wp_options_new_ad_fixed_choice();
						advertise_world_wp_options_new_ad_type();
						advertise_world_wp_options_new_ad_placement(); ?>
					</td>
					<td id="advertise-wrold-wp-options-new-ad-placement-infographic" valign="top">
						<p style="color: #1288bd; margin: 10px 11px;"><b>Example Areas</b></p>
						<div style="border: 1px solid #AAAAAA; background-color: #ffffff; padding: 10px; width: auto;">
							<p style="color: #b0b0b0;">YOUR WEBPAGE</p>
							<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASMAAAEOCAYAAADCEnHFAAAACXBIWXMAAAsTAAALEwEAmpwYAAA5smlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMwNjcgNzkuMTU3NzQ3LCAyMDE1LzAzLzMwLTIzOjQwOjQyICAgICAgICAiPgogICA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgICAgICAgICAgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiCiAgICAgICAgICAgIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiCiAgICAgICAgICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgICAgICAgICAgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIgogICAgICAgICAgICB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iCiAgICAgICAgICAgIHhtbG5zOnRpZmY9Imh0dHA6Ly9ucy5hZG9iZS5jb20vdGlmZi8xLjAvIgogICAgICAgICAgICB4bWxuczpleGlmPSJodHRwOi8vbnMuYWRvYmUuY29tL2V4aWYvMS4wLyI+CiAgICAgICAgIDx4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ+QjQ3MDY2RTZDRUNGQzdBOTVFQzhFMzZCRkI4RTE4RjQ8L3htcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD4KICAgICAgICAgPHhtcE1NOkRvY3VtZW50SUQ+eG1wLmRpZDo4MEYxOTBGMDY3NDgxMUU2QjEyRTk5QjZGNkM4NkVDRTwveG1wTU06RG9jdW1lbnRJRD4KICAgICAgICAgPHhtcE1NOkluc3RhbmNlSUQ+eG1wLmlpZDpmOWNmMDc1Ni04ZGM3LTRhMGUtOTJhYy0yYThiMjNkMTIzZjg8L3htcE1NOkluc3RhbmNlSUQ+CiAgICAgICAgIDx4bXBNTTpEZXJpdmVkRnJvbSByZGY6cGFyc2VUeXBlPSJSZXNvdXJjZSI+CiAgICAgICAgICAgIDxzdFJlZjppbnN0YW5jZUlEPnhtcC5paWQ6MGQ0NWJlMjItYjU3ZC00NGZlLTk4ZTctYzhlNzU1NmE4ZGY0PC9zdFJlZjppbnN0YW5jZUlEPgogICAgICAgICAgICA8c3RSZWY6ZG9jdW1lbnRJRD5hZG9iZTpkb2NpZDpwaG90b3Nob3A6YzA3ZDFkNWEtYWYxNi0xMTc5LWFhYWItYjJiY2JiMzQzMzhiPC9zdFJlZjpkb2N1bWVudElEPgogICAgICAgICA8L3htcE1NOkRlcml2ZWRGcm9tPgogICAgICAgICA8eG1wTU06SGlzdG9yeT4KICAgICAgICAgICAgPHJkZjpTZXE+CiAgICAgICAgICAgICAgIDxyZGY6bGkgcmRmOnBhcnNlVHlwZT0iUmVzb3VyY2UiPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6YWN0aW9uPnNhdmVkPC9zdEV2dDphY3Rpb24+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDppbnN0YW5jZUlEPnhtcC5paWQ6ZjljZjA3NTYtOGRjNy00YTBlLTkyYWMtMmE4YjIzZDEyM2Y4PC9zdEV2dDppbnN0YW5jZUlEPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6d2hlbj4yMDE2LTA4LTMxVDExOjM5OjI4KzA4OjAwPC9zdEV2dDp3aGVuPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6c29mdHdhcmVBZ2VudD5BZG9iZSBQaG90b3Nob3AgQ0MgMjAxNSAoTWFjaW50b3NoKTwvc3RFdnQ6c29mdHdhcmVBZ2VudD4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OmNoYW5nZWQ+Lzwvc3RFdnQ6Y2hhbmdlZD4KICAgICAgICAgICAgICAgPC9yZGY6bGk+CiAgICAgICAgICAgIDwvcmRmOlNlcT4KICAgICAgICAgPC94bXBNTTpIaXN0b3J5PgogICAgICAgICA8eG1wOkNyZWF0b3JUb29sPkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE1IChNYWNpbnRvc2gpPC94bXA6Q3JlYXRvclRvb2w+CiAgICAgICAgIDx4bXA6Q3JlYXRlRGF0ZT4yMDE2LTA4LTMxVDExOjEwOjU4KzA4OjAwPC94bXA6Q3JlYXRlRGF0ZT4KICAgICAgICAgPHhtcDpNb2RpZnlEYXRlPjIwMTYtMDgtMzFUMTE6Mzk6MjgrMDg6MDA8L3htcDpNb2RpZnlEYXRlPgogICAgICAgICA8eG1wOk1ldGFkYXRhRGF0ZT4yMDE2LTA4LTMxVDExOjM5OjI4KzA4OjAwPC94bXA6TWV0YWRhdGFEYXRlPgogICAgICAgICA8ZGM6Zm9ybWF0PmltYWdlL3BuZzwvZGM6Zm9ybWF0PgogICAgICAgICA8cGhvdG9zaG9wOkNvbG9yTW9kZT4zPC9waG90b3Nob3A6Q29sb3JNb2RlPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICAgICA8dGlmZjpYUmVzb2x1dGlvbj43MjAwMDAvMTAwMDA8L3RpZmY6WFJlc29sdXRpb24+CiAgICAgICAgIDx0aWZmOllSZXNvbHV0aW9uPjcyMDAwMC8xMDAwMDwvdGlmZjpZUmVzb2x1dGlvbj4KICAgICAgICAgPHRpZmY6UmVzb2x1dGlvblVuaXQ+MjwvdGlmZjpSZXNvbHV0aW9uVW5pdD4KICAgICAgICAgPGV4aWY6Q29sb3JTcGFjZT42NTUzNTwvZXhpZjpDb2xvclNwYWNlPgogICAgICAgICA8ZXhpZjpQaXhlbFhEaW1lbnNpb24+MjkxPC9leGlmOlBpeGVsWERpbWVuc2lvbj4KICAgICAgICAgPGV4aWY6UGl4ZWxZRGltZW5zaW9uPjI3MDwvZXhpZjpQaXhlbFlEaW1lbnNpb24+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgIAo8P3hwYWNrZXQgZW5kPSJ3Ij8+OutnJQAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAAPI0lEQVR42uzdf2yT94HH8Y+TJ7EPmyQ4c36V4oSFBEhogdJpVZq115s4qlJQKUhd0VRAaH+glp5OWqfTIa43FU30+KeCIvUUwTYd0qbSVC2T2uMgg4WojPHrCIUCKcRJyA9cfHZiR7Z5ktwfIQ88iYHQUhLI+yUhxY8ff+34+frN93kSKY4FH+wbaL+WLgAYK0UZfTKummnqMokRgLHjdAwojbcBwHhAjAAQIwAgRgCIEQAQIwDECACIEQBiBADECAAxAgBiBIAYAQAxAkCMAIAYASBGAECMADwUDN6Ciak6xyWfy1BtZzTltrX+HNv+5yJx1YfjkqRlBR4F46Z1uzrHpfJsl7Xvga6omuKmdTvVWD6XIa9zcPqFEqbtdYAYYQJZU5GveUVZqv2wMeW27UsqFAj1KpYcjMqfzl1RfTiu6hyX/rDicQWjCT2y86j1uJcqC9UcikmSthdkadfxNq1uCAzeTjHW4vI8+TxOBaMJFXvd2hxN6PmPGm0RAzECJEmbD11STSA8ImJHAiFVFmZrrT/Hur85FNPc62FbVuDR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">
						</div>
					</td>
				</tr>
			</table>
			<input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>"
			       class="button button-primary" style="margin-top: 25px;" />
		</form>

		<form method="post">
			<?php wp_nonce_field( 'save-sidebar-widgets', '_wpnonce_widgets', false ); ?>
		</form>

		<div class="widgets-chooser">
			<div class="widgets-chooser-actions">
			</div>
		</div>

		</div>
	</div>
	<style>
		#advertise-wrold-wp-options-new-ad-placement-infographic {
			width: 1%;
		}

		@media only screen and (max-width: 760px),
		(min-device-width: 768px) and (max-device-width: 1024px) {
			table, thead, tbody, th, td, tr {
				display: block;
			}

			#advertise-wrold-wp-options-new-ad-placement-infographic {
				width: 100%;
				margin: 25px 0;
			}
		}
	</style><?php
}

/**
 * Validates changes to option advertise-world-wp-options-new-ad
 *
 * Ensure that all fields in new ad form are filled correctly
 *
 * @since 1.0.0
 *
 * @param array $input Changes to advertise-world-wp-options-new-ad option
 *
 * @return array Updated advertise-world-wp-options-new-ad option
 */
function advertise_world_wp_options_new_ad_validate( $input ) {

	$valid = get_option( 'advertise-world-wp-options-new-ad', array() );
	if (!is_array($valid)) {
	    $valid = array();
    }

	$was_succuessfull_addition = false;

	if ( $input['add'] ) {
		foreach ( $input['add'] as $id => $advert ) {

			if ( ! array_key_exists( 'title', $advert ) || $advert['title'] === '' ) {
				add_settings_error(
					'advertise-world-new-ad-title-invalid',
					'settings_updated',
					'You must select an ad space name for your ad space to continue!',
					'error' );
				continue;
			}

			if ( ! array_key_exists( 'type', $advert ) ) {
				add_settings_error(
					'advertise-world-new-ad-type-invalid',
					'settings_updated',
					'You must select a type for your ad space to continue!',
					'error' );
				continue;
			}

			// Default to shortest for backward compatibility
			if ( ! array_key_exists( 'height-choice', $advert ) ) {
				$advert['height-choice'] = 'shortest';
			}

			// Default to responsive ads for backward compatibility
			if ( ! array_key_exists( 'fixed-size', $advert ) ) {
				$advert['fixed-size'] = '300x250';
			}

			$theme_sidebars = array();
			foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar => $registered_sidebar ) {
				if ( ! ( false !== strpos( $registered_sidebar['class'], 'inactive-sidebar' ) || 'orphaned_widgets' == substr( $sidebar, 0, 16 ) ) ) {
					array_push( $theme_sidebars, $registered_sidebar['id'] );
				}
			}

			if ( in_array( $advert['type'], $theme_sidebars ) ) {
				add_settings_error(
					'advertise-world-new-ad-widget-placement-invalid',
					'settings_updated',
					'Please follow the instructions to correctly create a Advertise World widget!',
					'error' );
				continue;
			}

			if ( ! array_key_exists( 'placement', $advert ) ) {
				add_settings_error(
					'advertise-world-new-ad-placement-invalid',
					'settings_updated',
					'You must select a placement for your ad space to continue!',
					'error' );
				continue;
			}

			$was_succuessfull_addition = true;
			$valid[ $id ]              = $advert;

		}
	}

	if ( $input['delete'] ) {
		foreach ( $input['delete'] as $id ) {
			unset( $valid[ $id ] );
		}
	}

	if ( true == $was_succuessfull_addition ) {
		$_SESSION['advertise-world-wp-plugin-editing-ad-id'] = null;
	}

	return $valid;
}