<?php
/**
 * Advertise World
 *
 * @package   WordPress
 * @subpackage Advertise_World
 * @author    Devisd Pty Ltd <support@advertiseworld.com>
 * @license   GPL-2.0+
 * @link      https://advertiseworld.com
 * @copyright 2016 Devisd Pty Ltd
 *
 * @wordpress-plugin
 * Plugin Name: Advertise World
 * Plugin URI: https://wordpress.org/plugins/advertise-world/
 * Description: This plugin simplifies adding Advertise World advertisements to your Wordpress site
 * Version: 1.3.7
 * Author: Devisd Pty Ltd
 * Author URI: http://www.devisd.com/
 * License: GPL2
 */



/*
 * Initializes plugin widget
 */
require( 'widget.php' );


/*
 * Initializes plugin admin section
 */
require( 'admin/main.php' );


/**
 * Relative Image location URL.
 *
 * @since 1.0.0
 * @access public
 * @var string Relative image location url
 */
define( 'ADWORLD_IMAGE_PATH', 'wp-content/uploads/' );


/**
 * Advert URL prefix.
 *
 * @since 1.0.0
 * @access public
 * @var String Advert URL prefix
 */
define( 'ADVERTISE_WORLD_ADVERT_URL_PREFIX', 'adspace://' );


/**
 * Returns connection protocol and domain
 *
 * @since 1.0.0
 *
 * @return String Current connection protocol and domain
 */
function siteURL() {
	$protocol   = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== "off" || $_SERVER['SERVER_PORT'] == 443 ) ? "https://" : "http://";
	$domainName = $_SERVER['HTTP_HOST'] . '/';

	return $protocol . $domainName;
}

define( 'SITE_URL', siteURL() );


// Initialize ad list option ( If this is not done the first time saving data to the table will be called twice )
add_option( 'advertise-world-wp-options-new-ad' );


/**
 * Determines if url request is an advert request
 *
 * @since 1.0.0
 */
function scan_generated_url() {
	$querys        = explode( '&', $_SERVER['QUERY_STRING'] );
	$advert_url    = base64_decode( $querys[0] );
	$advert_width  = base64_decode( $querys[1] );
	$advert_height = base64_decode( $querys[2] );
	$reqId         = base64_decode( $querys[3] );

	if ( substr( $advert_url, 0, strlen( ADVERTISE_WORLD_ADVERT_URL_PREFIX ) ) == ADVERTISE_WORLD_ADVERT_URL_PREFIX ) {

		$advert_id = substr( $advert_url, strlen( ADVERTISE_WORLD_ADVERT_URL_PREFIX ) );
		generate_ad( $advert_id, $advert_width, $advert_height, $reqId );

	}
}

add_action( 'plugins_loaded', 'scan_generated_url' );

/**
 * Gets the client ip address and returns
 *
 * @since 1.0.1
 *
 * @return string
 */
function Advertise_World_Get_Client_IP() {
	// Get user IP address
	$ip = false;
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		if ( strpos( $ip, "," ) ) {
			$ip = trim(substr( $ip, 0, strpos( $ip, "," ) ));
		}
	} else {
		$ip = "66.249.64.1";
	}
	$ip = filter_var( $ip, FILTER_VALIDATE_IP );
	$ip = ( $ip === false ) ? "66.249.64.1" : $ip;

	return $ip;
}

/**
 * Returns the latitude and longitude of an IP address
 *
 * @since 1.0.4
 *
 * @param $ip
 *
 * @return array(string,string)
 */
function Advertise_World_Get_LATLON( $ip ) {
	$v           = strpos( $ip, ":" ) === false ? "4" : "6"; // Fast check IP version
	$ipFormatted = $v === "4" ? ip2long( $ip ) : bin2hex( inet_pton( $ip ) );
	if ( $ipFormatted === false ) {
		// Fallback to US location (US Googleplex)
		return array( "37.452", "-122.08" );
	}
	$result = Advertise_World_Get_URL( "https://www.advertiseworld.com/portal/GeoIPModelLookup?ip=" . $ip . "&v=" . $v );
	if ( $result && strpos( $result, "," ) !== false ) {
		return explode( ",", $result );
	} else {
		// Fallback to US location (US Googleplex)
		return array( "37.452", "-122.08" );
	}
}

/**
 * Makes a HTTP request to a given URL and returns the response.
 * Note: If PHP-CURL is available, if will be used. If not, then fopen() will be used to download the URL.
 *
 * @since 1.0.1
 * @since 1.0.5 added the ability to make a POST request with parameters
 * @since 1.2.2 turned off curl's CURLOPT_FOLLOWLOCATION option as this causes problems if php is in safe_mode.
 *
 * @param string $url - The URL you would like to request.
 * @param bool $binaryResult - If true, the function will return the binary response. Used for downloading images.
 * @param bool $headers - An associative array of key value pairs to send in the HTTP request header.
 * @param array $post_params - false if your are using a GET request, or the key/value pairs of parameters to post.
 *
 * @return bool|mixed|string - Returns FALSE if there is an error or a timeout. Otherwise the response data is returned.
 */
function Advertise_World_Get_URL( $url, $binaryResult = false, $headers = false, $post_params = false, $follow_redirects = false ) {
    $TIMEOUT_SECS = 7;

	$result = false;

	// Check if we're posting the parameters, if so we need to add a Content-Type header
	if ( $post_params ) {
		$content_type_exists = false;
		$content_type_key    = false;
		if ( $headers ) {
			foreach ( $headers as $key => $value ) {
				if ( strtolower( trim( $key ) ) == "content-type" ) {
					$content_type_exists = true;
					$content_type_key    = $key;
				}
			}
			if ( $content_type_exists ) {
				$headers[ $content_type_key ] = "application/x-www-form-urlencoded";
			} else {
				$headers["Content-Type"] = "application/x-www-form-urlencoded";
			}
		} else {
			$headers = array( "Content-Type" => "application/x-www-form-urlencoded" );
		}
	}

	if ( function_exists( 'curl_init' ) ) {
		// Make URL Request using CURL
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		if ( $binaryResult ) {
			curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
		}
		if ( $headers ) {
			$curl_header = array();
			foreach ( $headers as $key => $value ) {
				$curl_header[] = $key . ": " . $value;
			}
			curl_setopt( $ch, CURLOPT_HEADER, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $curl_header );
		}
		if ( $post_params ) {
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $post_params ) );
		}
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $TIMEOUT_SECS );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $TIMEOUT_SECS );
		if ( $follow_redirects ) {
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		}

		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec( $ch );
		curl_close( $ch );

	} elseif ( function_exists( 'fopen' ) && function_exists( 'stream_get_contents' ) ) {
		// Make URL Request using fopen
		if ( $headers ) {

			// Construct the header for the URL Request
			$header = '';
			foreach ( $headers as $key => $value ) {
				$header .= $key . ": " . $value . "\r\n";
			}

			// Create the stream options
			$opts = array(
				'http' => array(
					'method' => $post_params ? "POST" : "GET",
					'header' => $header
				)
			);

			// Add the post parameters if to the stream options
			if ( $post_params ) {
				$opts['http']['content'] = http_build_query( $post_params );
			}

			$context = stream_context_create( $opts );
			$handle  = fopen( $url, "r", false, $context );
		} else {
			$handle = fopen( $url, "r" );
		}
        stream_set_timeout( $handle, $TIMEOUT_SECS, 0 );
		$result = stream_get_contents( $handle );
		fclose( $handle );

	}

	return $result;
}

/**
 * This method will send an email to Advertise World support
 *
 * @since 1.0.5
 *
 * @param $subject
 * @param $message
 */
function Advertise_World_Send_Email_To_Support( $subject, $message, $reply_to = false ) {

	$plugin_data = "No Plugin Data";
	if ( function_exists('get_plugin_data') ) {
		$plugin_data = print_r ( get_plugin_data( __FILE__ ), 1 );
	}

	$wordpress_version = "Unknown Version";
	if ( function_exists('get_bloginfo') ) {
		$wordpress_version = get_bloginfo( 'version' );
	}

	$result = Advertise_World_Get_URL(
		"https://www.advertiseworld.com/portal/ContactSupport",
		false,
		false,
		array(
			"name"         => "Wordpress Plugin",
			"email"        => $reply_to ? $reply_to : "support@advertiseworld.com",
			"your-subject" => $subject,
			"comment"      => "",
			"message"      => $message . "\r\n\r\n\r\n" . "Wordpress Version: " . $wordpress_version . "\r\n\r\n\r\nPlugin Info:\r\n" . $plugin_data
		)
	);

	if ( $result === false ) {
		return false;
	} else {
		return true;
	}

}

/**
 * Generates ad HTML with advert id and desired width
 *
 * @since 1.0.0
 * @since 1.0.3 Added $advert_height_choice arguement
 * @since 1.0.6 Changed to use fixed width and height for ad space (client decides now)
 *              Added $reqId parameter
 * @since 1.1.0 Fixed the json_decode line, the code was incompatible with older versions of PHP.
 *
 * @param string $advert_id - Adspace id
 * @param Int $advert_width - Width of client adspace
 * @param Int $advert_height - Height of client adspace
 * @param Int $reqId - The id of the request for a new ad
 *
 */
function generate_ad( $advert_id, $advert_width, $advert_height, $reqId = false ) {

	if ( $advert_width == 0 ) {
		exit;
	}

	// Get the client IP address
	$clientIP = Advertise_World_Get_Client_IP();

	// Get the latitude and longitude of the client
	list( $lat, $lon ) = Advertise_World_Get_LATLON( $clientIP );

	$host = "ads.advertiseworld.com";

	/*
	$alternate_hostname = @fopen( "/var/www/kritter_adsserver.conf", 'r' );
	if ($alternate_hostname) {
		@fclose($alternate_hostname);
		$host = "ads.test.advertiseworld.com";
	}
	error_log($host);*/

	$api_url = 'http://'.$host.'/ads/'
	           . "?site-id=" . $advert_id
	           . "&ua=" . urlencode( $_SERVER['HTTP_USER_AGENT'] )
	           . "&ip=" . $clientIP
	           . "&w=" . $advert_width
	           . "&h=" . $advert_height
	           . "&fmt=xhtml"
	           . "&ver=s2s_1"
	           . "&lat=" . $lat
	           . "&lon=" . $lon;
	$html    = Advertise_World_Get_URL( $api_url );
	if ($html === false || trim($html) == '') {
		$html = '<a href="https://www.advertiseworld.com/"><img src="https://www.advertiseworld.com/images/fallback/' . $advert_width . 'x' . $advert_height . '.png"></a>';
	}

	header( 'Content-Type: text/html' );
	echo $html;

	exit;


	/* This does Anti Adblocking Stuff, it is on hold for now
	$json    = Advertise_World_Get_URL( $api_url );

	if ( $json ) {
		$decoded = json_decode( $json, true );
		if ( is_array( $decoded ) ) {
			$result = $decoded[0];
		} else {
			$result = $decoded;
		}
	}

	$image_url       = false;
	$impression_urls = array();
	$landing_url     = false;

	if ( isset( $result['native'] ) ) {
		// Howto5 Response
		$native = $result['native'];
		if ( $native['assets'] ) {
			// Grab the image url
			if ( is_array( $native['assets'] ) ) {
				foreach ( $native['assets'] as $asset ) {
					if ( isset( $asset['img'] ) && isset( $asset['img']['url'] ) ) {
						$image_url = $asset['img']['url'];
					}
				}
			}
			// Grab the landing url
			if ( isset( $native['link'] ) && isset( $native['link']['url'] ) ) {
				$landing_url = $native['link']['url'];
			}
			// Grab the impression URL
			if ( isset( $native['imptrackers'] ) && isset( $native['link']['url'] ) ) {
				array_push( $impression_urls, $native['link']['url'] );
			}
		}
	} elseif ( isset( $result['assets'] ) && is_array( $result['assets'] ) ) {
		// Kritter Native Response
		foreach ( $result['assets'] as $asset ) {
			// Get the Landing URL
			if ( isset( $asset['link'] ) && isset( $asset['clicktrackers'] ) ) {
				foreach ( $asset['clicktrackers'] as $clicktracker ) {
					if ( strpos( $clicktracker, "advertiseworld" ) !== false ) {
						$landing_url = $clicktracker;
					}
				}
			}
			// Get the ad image
			if ( isset( $asset['img'] ) && isset( $asset['img']['url'] ) && isset( $asset['img']['w'] ) && isset( $asset['img']['h'] ) && $asset['img']['w'] == $advert_width && $asset['img']['h'] == $advert_height ) {
				$image_url = $asset['img']['url'];
			}
			// Get the impression urls
			if ( isset( $result['imptrackers'] ) && is_array( $result['imptrackers'] ) ) {
				foreach ( $result['imptrackers'] as $imptracker ) {
					array_push( $impression_urls, $imptracker );
				}
			}
		}
	} else {
		// Kritter Banner Response

		// Get the image URL
		if ( isset( $result['img'] ) ) {
			$image_url = $result['img'];
		}

		// Get the impression URL
		if ( isset( $result['csc'] ) ) {
			array_push( $impression_urls, $result['csc'] );
		}

		// Get the landing URL
		if ( isset( $result['curl'] ) ) {
			$landing_url = $result['curl'];
		}
	}

	error_log("image: " . $image_url);
	error_log("landing: " . $landing_url);
	error_log("impress trackers: " . implode(",",$impression_urls));

	if ( ! $result ) {
		exit;
	}

	$name      = basename( $image_url );
	$local_url = WP_CONTENT_DIR . '/aw-images/' . $name;
	$fp        = @fopen( $local_url, 'r' );

	if ( $fp === false ) {

		$fetch_url = transform_internal_url( SITE_URL . ADWORLD_IMAGE_PATH . $name );
		$raw       = Advertise_World_Get_URL( $fetch_url );

		if ( file_exists( $local_url ) ) {

			error_log( 'Failed to create file for image: File already exists!' );

		} else {

		    if (!is_dir(dirname($local_url))) {
                mkdir(dirname($local_url), 0755, true);
            }

			$new_fp = @fopen( $local_url, 'x+' );

			if ( $new_fp !== false ) {

				fwrite( $new_fp, $raw );
				rewind( $new_fp );
				Advertise_World_Server_And_Impress( $name, $landing_url, $impression_urls, $reqId );

			} else {
				error_log( 'Failed to create file for image: ' . $fetch_url );
			}
		}
	} else {
		Advertise_World_Server_And_Impress( $name, $landing_url, $impression_urls, $reqId );
	}
	*/

	exit;
}


/**
 * Converts an external image url to internal
 *
 * @since 1.0.0
 *
 * @param string $url External image url
 *
 * @return string Local image url
 */
function transform_external_url( $url ) {
	$filename = basename( $url );
	$base_url = SITE_URL . ADWORLD_IMAGE_PATH . $filename;

	return $base_url;
}


/**
 * Converts an local image url to external
 *
 * @since 1.0.0
 *
 * @param string $url Local image url
 *
 * @return string External image url
 */
function transform_internal_url( $url ) {
	$filename = basename( $url );
	$base_url = ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ) ? "https://" : "http://" ) . 'wac.33233.alphacdn.net/8033233/adworld/' . $filename;

	return $base_url;
}

/**
 * Fires after wordpress plugins are loaded
 *
 * @since 1.0.0
 */
function Advertise_World_Widget_Init() {

	if ( ! session_id() ) {
		session_start();
	}

	$_SESSION['ADVERTISE_WORLD_INJECTION_COUNT'] = 0;
	$_SESSION['ADVERTISE_WORLD_LAST_SIDEBAR']    = '';
}

add_action( 'plugins_loaded', 'Advertise_World_Widget_Init' );

/**
 * Registers an impression then serve advert image and click url
 *
 * @since 1.0.0
 *
 * @param string $image_file Relative image file location
 * @param string $click_url Advert click url
 * @param string $impression_url Advert impression url
 */
function Advertise_World_Server_And_Impress( $image_file, $click_url, $impression_urls, $reqId = 1 ) {
	$clientIP = Advertise_World_Get_Client_IP();
	$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36";

	// Request headers to doing an impression
	$headers = array(
		"User-Agent"      => $userAgent,
		"X-Forwarded-For" => $clientIP
	);

	// Echo the image data to the response
	header( 'Content-Type: text/plain' );
	echo base64_encode( file_get_contents( WP_CONTENT_DIR . '/aw-images/' . $image_file ) ) . '&&&&' . $click_url . '&&&&' . $reqId . '&&&&' . implode(",",$impression_urls);
	exit;
}


/**
 * Fires before the page header is loaded
 *
 * Captures header output and attatch any assigned adverts
 *
 * @since 1.0.0
 *
 * @param string $name Theme header name
 */
function Advertise_World_Header_Injection( $name ) {

	ob_start();

	$templates = array();
	$name      = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "header-{$name}.php";
	}

	$templates[] = 'header.php';

	locate_template( $templates, true );

	$theme_header = ob_get_contents();
	ob_end_clean();

	$advertise_world_ad_list = get_option( 'advertise-world-wp-options-new-ad' );
	$header_ads_above        = Array();
	$header_ads_below        = Array();
	$content_ads_above       = Array();

	if ( $advertise_world_ad_list && is_array($advertise_world_ad_list) ) {
        foreach ($advertise_world_ad_list as $ad_id => $advert) {
            if (!isset($advert['height-choice'])) {
                // Add backward compatibility for ad height selection
                $advert['height-choice'] = "shortest";
            }
            if (!isset($advert['fixed-size'])) {
                $advert['fixed-size'] = "300x250";
            }
            if (isset($advert['fixed-size']) && !isset($advert['adspace']) ) {
                // Add backward compatibility for ad size / responsive selection
                $advert['fixed-size'] = false;
                $advert['adspace'] = false;
            }
            if ('header' == $advert['type']) {
                if ('top' == $advert['placement']) {
                    $header_ads_above[$ad_id] = $advert;
                } elseif ('bottom' == $advert['placement']) {
                    $header_ads_below[$ad_id] = $advert;
                }
            } elseif ('content' == $advert['type']) {
                if ('top' == $advert['placement']) {
                    $content_ads_above[$ad_id] = $advert;
                }
            }
        }
    }

    $advertise_world_spoof_widget = new Advertise_World_Widget();

    // Insert Inside Header Above Adverts
    $header_start_pos = strpos( $theme_header, '<header', 0 );
    $header_begin     = strpos( $theme_header, '>', $header_start_pos ) + 1;

    foreach ( $header_ads_above as $advert ) {
        $theme_header = substr_replace( $theme_header, $advertise_world_spoof_widget->output( $advert['title'], null, $advert['height-choice'], $advert['fixed-size'], $advert['adspace'] ), $header_begin, 0 );
    }

    // Insert Inside Header Below Adverts
    $header_end = strpos( $theme_header, '</header', 0 );
    foreach ( $header_ads_below as $advert ) {
        $theme_header = substr_replace( $theme_header, $advertise_world_spoof_widget->output( $advert['title'], null, $advert['height-choice'], $advert['fixed-size'], $advert['adspace'] ), $header_end, 0 );
    }

    // Insert Under Header Adverts
    $header_end   = strpos( $theme_header, '</header', 0 );
    $under_header = strpos( $theme_header, '>', $header_end ) + 1;
    foreach ( $content_ads_above as $advert ) {
        $theme_header = substr_replace( $theme_header, $advertise_world_spoof_widget->output( $advert['title'], null, $advert['height-choice'], $advert['fixed-size'], $advert['adspace'] ), $under_header, 0 );
    }

	echo $theme_header;
}

add_action( 'get_header', 'Advertise_World_Header_Injection', 10, 1 );


/**
 * Fires before loading the footer of the page
 *
 * Captures footer output and attatch any assigned adverts
 *
 * @since 1.0.0
 *
 * @param string $name Theme footer name
 */
function Advertise_World_Footer_Injection( $name ) {

	ob_start();

	$templates = array();
	$name      = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "footer-{$name}.php";
	}

	$templates[] = 'footer.php';

	locate_template( $templates, true );

	$theme_footer = ob_get_contents();
	ob_end_clean();

	$advertise_world_ad_list = get_option( 'advertise-world-wp-options-new-ad' );
	$content_ads_below       = Array();
	$footer_ads_above        = Array();
	$footer_ads_below        = Array();

	if ( $advertise_world_ad_list ) {
        foreach ($advertise_world_ad_list as $ad_id => $advert) {
            if (!isset($advert['height-choice'])) {
                // Add backward compatibility for ad height selection
                $advert['height-choice'] = "shortest";
            }
            if (!isset($advert['fixed-size'])) {
                $advert['fixed-size'] = "300x250";
            }
            if (isset($advert['fixed-size']) && !isset($advert['adspace']) ) {
                // Add backward compatibility for ad size / responsive selection
                $advert['fixed-size'] = false;
                $advert['adspace'] = false;
            }
            if ('content' == $advert['type']) {
                if ('bottom' == $advert['placement']) {
                    $content_ads_below[$ad_id] = $advert;
                }
            } elseif ('footer' == $advert['type']) {
                if ('top' == $advert['placement']) {
                    $footer_ads_above[$ad_id] = $advert;
                } elseif ('bottom' == $advert['placement']) {
                    $footer_ads_below[$ad_id] = $advert;
                }
            }
        }
    }

    $advertise_world_spoof_widget = new Advertise_World_Widget();

    // Insert Above Footer Adverts
    $footer_before = strpos( $theme_footer, '<footer', 0 );
    foreach ( $content_ads_below as $advert ) {
        $theme_footer = substr_replace( $theme_footer, $advertise_world_spoof_widget->output( $advert['title'], null, $advert['height-choice'], $advert['fixed-size'], $advert['adspace'] ), $footer_before, 0 );
    }

    // Insert inside Footer above Adverts
    $footer_before = strpos( $theme_footer, '<footer', 0 );
    $footer_begin  = strpos( $theme_footer, '>', $footer_before ) + 1;
    foreach ( $footer_ads_above as $advert ) {
        $theme_footer = substr_replace( $theme_footer, $advertise_world_spoof_widget->output( $advert['title'], null, $advert['height-choice'], $advert['fixed-size'], $advert['adspace'] ), $footer_begin, 0 );
    }

    // Insert inside Footer bellow Adverts
    $footer_end = strpos( $theme_footer, '</footer', 0 );
    foreach ( $footer_ads_below as $advert ) {
        $theme_footer = substr_replace( $theme_footer, $advertise_world_spoof_widget->output( $advert['title'], 'order: 1 !important;', $advert['height-choice'], $advert['fixed-size'], $advert['adspace'] ), $footer_end, 0 );
    }

    echo $theme_footer;
}

add_action( 'get_footer', 'Advertise_World_Footer_Injection', 10, 1 );


/**
 * Filters the post content
 *
 * Attatches assigned ads to the post content
 *
 * @since 1.0.0
 *
 * @param $content Post content
 *
 * @return string Post content
 */
function Advertise_World_Post_Injection( $content ) {

	$new_content = "";

	$advertise_world_spoof_widget = new Advertise_World_Widget();

	$advertise_world_ad_list = get_option( 'advertise-world-wp-options-new-ad' );
	$post_ads_above          = Array();
	$post_ads_below          = Array();

	if ( $advertise_world_ad_list ) {
        foreach ($advertise_world_ad_list as $ad_id => $advert) {
            if (!isset($advert['height-choice'])) {
                // Add backward compatibility for ad height selection
                $advert['height-choice'] = "shortest";
            }
            if (!isset($advert['fixed-size'])) {
                $advert['fixed-size'] = "300x250";
            }
            if (isset($advert['fixed-size']) && !isset($advert['adspace']) ) {
                // Add backward compatibility for ad size / responsive selection
                $advert['fixed-size'] = false;
                $advert['adspace'] = false;
            }
            if ('post' == $advert['type']) {
                if ('top' == $advert['placement']) {
                    $post_ads_above[$ad_id] = $advert;
                } elseif ('bottom' == $advert['placement']) {
                    $post_ads_below[$ad_id] = $advert;
                }
            }
        }
    }

    foreach ( $post_ads_above as $advert ) {
        $new_content .= $advertise_world_spoof_widget->output( $advert['title'], null, $advert['height-choice'], $advert['fixed-size'], $advert['adspace'] );
    }

    $new_content .= $content;

    foreach ( $post_ads_below as $advert ) {
        $new_content .= $advertise_world_spoof_widget->output( $advert['title'], null, $advert['height-choice'], $advert['fixed-size'], $advert['adspace'] );
    }

    return $new_content;

}

add_filter( 'the_content', 'Advertise_World_Post_Injection' );
