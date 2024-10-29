<?php
/**
 * Advertise World Advert Placement Widget
 *
 * A widget that allows admin's to place adverts by widget.
 *
 * @link https://www.advertiseworld.com
 *
 * @package WordPress
 * @subpackage Advertise_World_Widget
 * @since 1.0.0
 */


/**
 * Advertise World Advert Widget.
 *
 * A widget that adds an advert based on the adspace id field.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Advertise_World_Widget extends WP_Widget {

	//const AW_DEFAULT_SIZES = '[{"label":"120*240 : Vertical Banner","value":"1"},{"label":"120*600 : Skyscraper","value":"2"},{"label":"125*125 : Button","value":"3"},{"label":"160*600 : Wide Skyscraper","value":"4"},{"label":"180*150 : Small Rectangle","value":"5"},{"label":"200*200 : Small Square","value":"6"},{"label":"234*60 : Half Banner","value":"7"},{"label":"250*250 : Square","value":"8"},{"label":"300*250 : Medium Rectangle","value":"9"},{"label":"300*600 : Half Page","value":"10"},{"label":"300*1050 : Portrait","value":"11"},{"label":"320*50 : Mobile Leaderboard","value":"12"},{"label":"320*100 : Large Mobile Banner","value":"13"},{"label":"336*280 : Large Rectangle","value":"14"},{"label":"468*60 : Banner","value":"15"},{"label":"728*90 : Leaderboard","value":"16"},{"label":"970*250 : Billboard","value":"17"},{"label":"970*90 : Large Leaderboard","value":"18"}]';
	//const AW_DEFAULT_SIZES = '[{"label":"160*600 : Wide Skyscraper","value":"4"},{"label":"300*250 : Medium Rectangle","value":"9"},{"label":"728*90 : Leaderboard","value":"16"}]';
	//const AW_DEFAULT_SIZES = "{\"160\":[600],\"320\":[50,100],\"200\":[200],\"234\":[60],\"970\":[250,90],\"300\":[250,600,1050],\"336\":[280],\"180\":[150],\"468\":[60],\"120\":[240,600],\"728\":[90],\"250\":[250],\"125\":[125]}";
	const AW_DEFAULT_SIZES = "{\"160\":[600],\"300\":[250],\"728\":[90]}";

	function __construct() {
		parent::__construct( 'Advertise_World_Widget', 'Advertise World', Array( 'description' => 'A widget for placing advertise world advertisements!' ) );
	}

	/**
	 * Renders the widget.
     *
     * Add styling to theme extras.
     *
     * @since 1.0.0
	 *
     * @param array $args Widget options
     * @param array $instance Widget object instance
	 */
	function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['adspace'] );

		$last_element = 0;
		$element_end  = 1;
		$style        = 'style="display: block !important;"';
/*
		// Make sure all theme added styles are display: block !important
		while ( $element_end ) {

			$element_end = strpos( $args['before_widget'], '>', $last_element );

			if ( $element_end !== false ) {

				$existing_style = strpos( $args['before_widget'], 'style="', $last_element );

				if ( $existing_style ) {

					$args['before_widget'] = substr_replace( $args['before_widget'], 'display: block !important;', $existing_style + strlen( 'style="' ), 0 );
					$last_element          = $element_end + strlen( 'display: block !important;' ) + 1;

				} else {

					$args['before_widget'] = substr_replace( $args['before_widget'], $style, $element_end, 0 );
					$last_element          = $element_end + strlen( $style ) + 1;
				}

			}
		}*/

		// before and after widget arguments are defined by themes
		//echo $args['before_widget'];

        if (!isset($instance['fixed-size'])) {
            $instance['fixed-size'] = false;
        }
        if (!isset($instance['adspace'])) {
            $instance['adspace'] = false;
        }

		$output = self::output( $instance['id'], null, null, $instance['fixed-size'], $instance['adspace'] );

		echo $output;

		//echo $args['after_widget'];
	}

	/**
	 * Fires when the widget is updated from the admin section.
     *
	 * @since 1.0.0
     *
     * @param array $new_instance Attributes of widget
     * @param array $old_instance Attributes of widget
     *
     * @return array Attributes of widget
	 */
	function update( $new_instance, $old_instance ) {
		$instance               = $old_instance;
		$instance['id']         = $new_instance['id'];
		$instance['title']      = $new_instance['title'];
		$instance['adspace']    = $new_instance['adspace'];
		$instance['fixed-size'] = $new_instance['fixed-size'];

		return $instance;
	}

	/**
	 * Displays widget options update form.
     *
     * Displays form for updating widget options prefilled with existing options.
     *
     * @since 1.0.0
     *
     * @param array $instance Attributes of widget
	 */
	function form( $instance ) {
		$instance   = wp_parse_args( (array) $instance, array(
			'id'         => '',
			'title'      => '',
			'adspace'    => '',
			'fixed-size' => ''
		) );
		$id         = strip_tags( $instance['id'] );
		$name       = strip_tags( $instance['title'] );
		$title      = strip_tags( $instance['adspace'] );
		$fixed_size = strip_tags( $instance['fixed-size'] );

		?>
		<input class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>"
		         name="<?php echo $this->get_field_name( 'id' ); ?>" type="hidden"
		         value="<?php echo esc_attr( $id ); ?>" />
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Name:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
			       value="<?php echo esc_attr( $name ); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'adspace' ); ?>"><?php _e( 'Ad-Space:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'adspace' ); ?>"
			       name="<?php echo $this->get_field_name( 'adspace' ); ?>" type="text"
			       value="<?php echo esc_attr( $title ); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fixed-size' ); ?>"><?php _e( 'Ad Size:' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'fixed-size' ); ?>"
			        name="<?php echo $this->get_field_name( 'fixed-size' ); ?>">
				<option value="728x90" <?php if ($fixed_size === "728x90") { echo "selected"; } ?>>Banner 728x90</option>
				<option value="300x250" <?php if ($fixed_size === "300x250" || $fixed_size === "responsive") { echo "selected"; } ?>>Box 300x250</option>
				<option value="160x600" <?php if ($fixed_size === "160x600") { echo "selected"; } ?>>Skyscraper 160x600</option>
			</select>
		</p>
		<?php
	}

	/**
	 * Generates Advert html and javascript.
     *
     * Creates advert html and javascript for widget with extra styles if
     * specified.
     *
     * @since 1.0.0
	 * @since 1.0.3 Added $height_choice parameter
     *
     * @param string $item_id Ad Space ID
     * @param string $style Extra css styling
	 * @param string $height_choice - The advert height selection to choose with browser is resized
     *
     * @return string Advert html and javascript
	 */
	function output( $item_id = null, $style = null, $height_choice = null, $fixed_size = false, $site_id = false ) {
		$fake_ad = false;

        // Get the ad sizes
		/*
		$ad_size_json = get_option( 'advertise-world-wp-ad-sizes' );
		if ( $ad_size_json === false ) {
			require_once ( 'advertise-world-wp.php' );
			$ad_size_json = Advertise_World_Get_URL( "https://www.advertiseworld.com/portal/CommonNonAuthenticatedAjax?req=getCreativeSizes" );
			if ( ! $ad_size_json ) {
				// Fallback ad sizes if URL request failed
				$ad_size_json = self::AW_DEFAULT_SIZES;
			}
			update_option( 'advertise-world-wp-ad-sizes', $ad_size_json );
		}
		if ( json_decode($ad_size_json) === NULL ) {
			// Fallback ad sizes if current value is not valid json
			$ad_size_json = self::AW_DEFAULT_SIZES;
			update_option( 'advertise-world-wp-ad-sizes', $ad_size_json );
		}*/

		$ad_size_json = self::AW_DEFAULT_SIZES;

		// add an id even if its a hidden ad to avoid difference detection
		if ( $item_id == null ) {
			$item_id = mt_rand();
		}

        if ( $fixed_size === false || $fixed_size === "responsive" ) {
            $fixed_size = "300x250";
        }

		if ( $fixed_size && $fixed_size !== "responsive" && $site_id ) {

			// Get the client IP address
			$clientIP = Advertise_World_Get_Client_IP();

			// Get the latitude and longitude of the client
			list( $lat, $lon ) = Advertise_World_Get_LATLON( $clientIP );

			$host = "ads.advertiseworld.com";

			list ( $advert_width, $advert_height ) = explode( "x" , $fixed_size );
			/*
			$alternate_hostname = @fopen( "/var/www/kritter_adsserver.conf", 'r' );
			if ($alternate_hostname) {
				@fclose($alternate_hostname);
				$host = "ads.test.advertiseworld.com";
			}
			error_log($host);*/

			$api_url = 'http://'.$host.'/ads/'
			           . "?site-id=" . $site_id
			           . "&ua=" . urlencode( $_SERVER['HTTP_USER_AGENT'] )
			           . "&ip=" . $clientIP
			           . "&w=" . $advert_width
			           . "&h=" . $advert_height
			           . "&fmt=xhtml"
			           . "&ver=s2s_1"
                       . "&lat=" . $lat
                       . "&lon=" . $lon;

			//return '<iframe width="' . $advert_width . '" height="' . $advert_height . '" src="' . $api_url . '"></iframe>';

			$html    = Advertise_World_Get_URL( $api_url );
			if ($html === false || trim($html) == '') {
				return '<div style="width: 100%; text-align: center;"><!-- advertiseworld.com --><a href="https://www.advertiseworld.com/"><img src="https://www.advertiseworld.com/images/fallback/' . $advert_width . 'x' . $advert_height . '.png"></a></div>';
			} else {
				return '<div style="width: 100%; text-align: center;" req_width="'.$advert_width.'" req_height="'.$advert_height.'"><!-- advertiseworld.com -->' . $html . '</div>';
			}
		}

		$rand_id  = mt_rand();
		$ajax_url = home_url() . '/?' . base64_encode( ADVERTISE_WORLD_ADVERT_URL_PREFIX . $item_id );
		if ( $height_choice == null ) {
			$height_choice = "shortest";
		}
		$height_choice_base64 = base64_encode( $height_choice );
		$output   = '<div id="' . $rand_id . '" style="width:100%; text-align: center; ' . $style . ' ' . ( $fake_ad ? "display:none !important;" : "display:block !important;" ) . '"></div>' . "\n";
		$output .=      '<script>' . "\n" .
						'var timer' . $rand_id . ' = 0;' . "\n" .
						'var awCurrentSize' . $rand_id . ' = false;' . "\n" .
						'var awHeightChoice' . $rand_id . ' = "' . $height_choice . '";' . "\n" .
						'var awReqId' . $rand_id . ' = 1;' . "\n" .
						'var awSizeArray' . $rand_id . ' = ' . $ad_size_json . ';' . "\n" .
		                'var advertiseWorld' . $rand_id . 'html = "";' . "\n" .

		                'function awUpdateAd' . $rand_id . '() {' . "\n" .
						'    document.getElementById("' . $rand_id . '").innerHTML = "";' . "\n" .
						'    postscribe ("#' . $rand_id . '",advertiseWorld' . $rand_id . 'html);' . "\n" .
						'}' . "\n" .

						'function resize' . $rand_id . 'End() {' . "\n" .
						'    var elem = document.getElementById("' . $rand_id . '");' . "\n" .
						'    if (elem) {' . "\n" .
						'        var elemWidth = 0;' . "\n" .
						'        if (elem.clientWidth) {' . "\n" .
						'            elemWidth = elem.clientWidth;' . "\n" .
						'        }' . "\n" .
						'        var widthToUse = false;' . "\n" .
						'        var keyCount = 0;' . "\n" .
						'        var skinniestImgWidth = false;' . "\n" .
						'        for (var imgWidth in awSizeArray' . $rand_id . ') {' . "\n" .
						'            keyCount++;' . "\n" .
						'            if (imgWidth < elemWidth) {' . "\n" .
						'                if (widthToUse === false || imgWidth > widthToUse) {' . "\n" .
						'                    widthToUse = imgWidth;' . "\n" .
						'                }' . "\n" .
						'            }' . "\n" .
						'            if (skinniestImgWidth === false || imgWidth < skinniestImgWidth) {' . "\n" .
						'                skinniestImgWidth = imgWidth;' . "\n" .
						'            }' . "\n" .
						'            lastImg = imgWidth;' . "\n" .
						'        }' . "\n" .
						'        var imgWithWidth = false;' . "\n" .
						'        if (widthToUse === false && keyCount > 0) {' . "\n" .
		                '            widthToUse = skinniestImgWidth ? skinniestImgWidth : lastImg;' . "\n" .
						'            imgWithWidth = skinniestImgWidth ? awSizeArray' . $rand_id . '[skinniestImgWidth] : awSizeArray' . $rand_id . '[lastImg];' . "\n" .
						'        } else if (widthToUse) {' . "\n" .
						'            imgWithWidth = awSizeArray' . $rand_id . '[widthToUse]' . "\n" .
						'        } ' . "\n";

		if ( $height_choice == 'shortest' || $height_choice == 'tallest' ) {
			// Add javascript for Shortest / Tallest ad height selection method
			$comparator = $height_choice == 'shortest' ? '<' : '>';
			$output .=  '       var heightToUse = false;' . "\n" .
						'       if (imgWithWidth) {' . "\n" .
						'           for (imgHeightIndex in imgWithWidth) {' . "\n" .
						'               var imgHeight = imgWithWidth[imgHeightIndex];' . "\n" .
						'               if (heightToUse === false || imgHeight ' . $comparator . ' heightToUse) {' . "\n" .
						'                   heightToUse = imgHeight;' . "\n" .
						'               }' . "\n" .
						'           }' . "\n" .
						'       }' ;
		} else {
			// Add javascript for the Medium ad height selection method
			$output .=  '       var heightToUse = false;' . "\n" .
						'       if (imgWithWidth) {' . "\n" .
						'           imgWithWidth.sort(function(a, b) { ' . "\n" .
						'               return a - b; ' . "\n" .
						'           });' . "\n" .
						'           heightToUse = imgWithWidth[Math.floor(imgWithWidth.length / 2)];' . "\n" .
						'       }' ;
		}


		$output .=      '       if (widthToUse && heightToUse && awCurrentSize' . $rand_id . ' !== widthToUse+"x"+heightToUse) { ' . "\n" .
						'           awCurrentSize' . $rand_id . ' = widthToUse+"x"+heightToUse; ' . "\n" .
						'           var reqId = ++awReqId' . $rand_id . ';' . "\n" .
						'           var xmlhttp = new XMLHttpRequest();' . "\n" .
						'           xmlhttp.onreadystatechange = function() {' . "\n" .
						'               if (xmlhttp.readyState == XMLHttpRequest.DONE ) {' . "\n" .
						'                   if (xmlhttp.status >= 200 && xmlhttp.status <= 299) {' . "\n" .

                        '                               document.getElementById("' . $rand_id . '").setAttribute("req_width",widthToUse);' . "\n" .
                        '                               document.getElementById("' . $rand_id . '").setAttribute("req_height",heightToUse);' . "\n" .

		                '                                if (typeof postscribe === "undefined") {' . "\n" .
						'                                    var jq = document.createElement(\'script\');' . "\n" .
						'                                    jq.type = \'text/javascript\';' . "\n" .
						'                                    jq.src = \'https://cdnjs.cloudflare.com/ajax/libs/postscribe/2.0.8/postscribe.min.js\';' . "\n" .
						'                                    jq.crossorigin = \'anonymous\';' . "\n" .
						'                                    jq.onload = awUpdateAd' . $rand_id . ';' . "\n" .
						'                                    advertiseWorld' . $rand_id . 'html = xmlhttp.responseText;' . "\n" .
						'                                    document.getElementsByTagName(\'head\')[0].appendChild(jq);' . "\n" .
						'                                } else {' . "\n" .
						'                                    advertiseWorld' . $rand_id . 'html = xmlhttp.responseText;' . "\n" .
						'                                    awUpdateAd' . $rand_id . '();' . "\n" .
						'                                }' . "\n" ;

						/////// jQuery XHTML insert
						//'                       jQuery("#' . $rand_id . '").html(xmlhttp.responseText);' . "\n" ;

						/////// Plain XHTML
		                //'                       document.getElementById("' . $rand_id . '").innerHTML = xmlhttp.responseText;' . "\n" ;

						////// JSON with Trackers etc.
						/*'                       var results = xmlhttp.responseText.split("&&&&");' . "\n" .
						'                       if (results.length >= 4 && results[2] === awReqId' . $rand_id . '+"") {' . "\n" .
		                '                           var impression_trackers = "";' . "\n" .
		                '                           var imp_array = results[3].split(",");' . "\n" .
		                '                           for (var url of imp_array) {' . "\n" .
		                '                               impression_trackers += \'<img src="\' + url + \'">\';' . "\n" .
		                '                           }' . "\n" .
						'                           document.getElementById("' . $rand_id . '").innerHTML = "<a target=\"_blank\" href=\"" + results[1] + "\" style=\"display:block !important; box-shadow: none !important;\"><img src=\"data:png;base64," + results[0] + "\" style=\"' . ( $fake_ad ? 'height: 0 !important; display: none !important;' : 'display:block !important;' ) . 'margin: 0 auto; max-width: none;\"></a>" + impression_trackers;' . "\n" .
						'                       }' . "\n" .*/

		$output .=      '                   } else {' . "\n" .
						'                       document.getElementById("' . $rand_id . '").innerHTML = "";' . "\n" .
						'                   }' . "\n" .
						'               }' . "\n" .
						'           };' . "\n" ;
		if (!$fake_ad) {
			$output .= '           xmlhttp.open("GET", "' . $ajax_url . '&" + btoa(widthToUse) + "&" + btoa(heightToUse) + "&" + btoa(reqId), true);' . "\n" .
			           '           xmlhttp.send();' . "\n";
		}
		$output .=  	'       } ' . "\n" .
						'   }' . "\n" .
						'   ' . "\n" .
						'}' . "\n" .
						'function resize' . $rand_id . 'Begin(){' . "\n" .
						'    if(timer' . $rand_id . ') {' . "\n" .
						'        clearTimeout(timer' . $rand_id . ');' . "\n" .
						'    }' . "\n" .
						'    timer' . $rand_id . ' = setTimeout(resize' . $rand_id . 'End, 500);' . "\n" .
						'}' . "\n" .
						'window.addEventListener("resize", resize' . $rand_id . 'Begin);' . "\n" .
						'resize' . $rand_id . 'End();' . "\n" .
						'</script>';

		return $output;
	}

}

/**
 * Fires before loading widgets.
 *
 * Registers plugin widget with wordpress.
 *
 * @since 1.0.0
 */
function advertise_world_wp_load_widget() {
	register_widget( 'Advertise_World_Widget' );
}

add_action( 'widgets_init', 'advertise_world_wp_load_widget' );
