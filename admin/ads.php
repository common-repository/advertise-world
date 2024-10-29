<?php
/**
 * Advertise World list adverts
 *
 * Displays and manages functionality for the admin list adverts page.
 *
 * @link https://www.advertiseworld.com
 *
 * @package WordPress
 * @subpackage Advertise_World_Admin_List_Ads
 * @since 1.0.0
 */


/**
 * Displays Advertise World plugin Existing Ads page.
 *
 * @since 1.0.0
 * @since 1.1.0 Images are inline base64, Ad Blocker Plus was causing image loading issues
 */
function advertise_world_wp_admin_menu_list_ads() {
	?>
	<div class="wrap" style=" margin: 0 5%;">
		<img src="data:image/png;base64,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"
		     style="margin: 10px 0 25px 0; max-width: 100%;">
		<?php settings_errors(); ?>
		<h2 class="nav-tab-wrapper wp-clearfix" style="margin-bottom: 25px;"><a href="/wp-admin/nav-menus.php"
		                                                                        class="nav-tab nav-tab-active">Existing
				Adverts</a></h2>

		<div class="advertise-world-admin-container" style="padding-left: 18px;">
		<?php
		$new_ad_url = add_query_arg( array(
			'page' => 'advertise-world-admin-menu-new-ad'
		), admin_url( 'admin.php' ) );

		?><a href="<?php echo $new_ad_url ?>" class="button button-primary" style="margin-bottom: 25px;">New
			Advert</a><?php

		$ad_list = get_option( 'advertise-world-wp-options-new-ad' );

		$account_options = get_option( 'advertise-world-wp-options-account' );

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

		$api_url = $protocol."://".$host."/portal/api/v1/publisher/adspaces/list?email=" . $account_options['account'];
		$result = Advertise_World_Get_URL( $api_url );
		if ($result == FALSE) {
		    ?>
            <div>Error connecting to Advertise World server. Operation Timed Out.</div>
            <?php
            return;
        }
		$available_adspaces = json_decode( $result, true );

		?>

		<table cellspacing="0" style="border: 1px solid #AAAAAA; width: 100%; text-align: center;">
			<tr style="height: 50px;">
				<th>Ad space name</th>
				<th>Ad height choice</th>
				<th>Size</th>
				<th>Ad placement</th>
				<th>Position</th>
				<th></th>
			</tr>
			<tr style="background-color: white; height: 50px;">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php

			if ( $ad_list ) {

				foreach ( $ad_list as $ad_id => $advert ) {
					?>
					<tr style="background-color: white; height: 50px;">
						<td><?php
							if ( true == $available_adspaces['accountExist'] ) {
								$is_found = false;
								foreach ( $available_adspaces['adSpaces'] as $adspace ) {
									if ( $advert['title'] == $adspace['guid'] ) {
										$is_found = true;
										echo $adspace['name'];
									}
								}
								if ( false == $is_found ) {
									echo 'This Ad is no longer valid in your <a href="https://www.advertiseworld.com">https://www.advertisworld.com</a> account';
								}
							} else {
								echo 'Please ad your account email in <a href="' . add_query_arg( array( 'page' => 'advertise-world-admin-menu' ), admin_url( 'admin.php' ) ) . '">settings</a> to see your ad space names';
							}
							?>
						</td>
						<td>
							<?php
							if ( isset( $advert['height-choice'] ) ) {
								echo $advert['height-choice'];
							} else {
								echo "shortest";
							}
							?>
						</td>
						<td>
							<?php
							if ( isset( $advert['fixed-size'] ) ) {
								echo $advert['fixed-size'];
							} else {
								echo "300x250";
							}
							?>
						</td>
						<td><?php echo $advert['type'] ?></td>
						<td><?php echo $advert['placement'] ?></td>
						<td>
							<?php
							$edit_ad_url = add_query_arg( array(
								'edit-ad' => $ad_id,
								'page'    => 'advertise-world-admin-menu-new-ad'
							), admin_url( 'admin.php' ) );

							?><a href="<?php echo $edit_ad_url ?>" class="button">Edit</a>
							<form method="post" action="options.php" style="display: inline-block;">
								<?php settings_fields( 'advertise-world-wp-options-list-ads' ); ?>
								<input type="hidden" name="advertise-world-wp-options-list-ads[delete][0]"
								       id="advertise-world-wp-options-list-ads-section-table"
								       value="<?php echo $ad_id ?>">
								<input name="Submit" type="submit" value="<?php esc_attr_e( 'DELETE' ); ?>"
								       onclick="return confirm('Are you sure you want to delete this ad?');"
								       class="button button-primary"/>
							</form>
						</td>
					</tr>
					<?php
				}
			} else { ?>
				<tr style="background-color: white; height: 50px;">
					<td colspan="5" style="background-color: white; height: 50px;">You have no ad spaces!</td>
				</tr>
			<?php } ?>
			<tr style="background-color: white; height: 50px;">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</table>
	</div>
	</div>
	<?php
}

/**
 * Validates changes to ad list.
 *
 * Updates new-ad option to remove selected advert.
 *
 * @since 1.0.0
 *
 * @param array $input
 *
 * @return null
 */
function advertise_world_wp_options_list_ads_validate( $input ) {

	if ( ! $input ) {
		return null;
	}

	update_option( 'advertise-world-wp-options-new-ad', $input );

	return null;
}