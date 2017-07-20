<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cohhe.com/
 * @since             1.0
 * @package           ftg_func
 *
 * @wordpress-plugin
 * Plugin Name:       Feed The Grid
 * Plugin URI:        https://cohhe.com/project-view/feed-grid-pro/
 * Description:       Feed The Grid plugin allows you to show Instagram, Twitter and Youtube feeds on your website.
 * Version:           1.2
 * Author:            Cohhe
 * Author URI:        https://cohhe.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       feed-the-grid
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ftg-functionality-activator.php
 */
function ftg_activate_ftg_func() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ftg-functionality-activator.php';
	ftg_func_Activator::ftg_activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ftg-functionality-deactivator.php
 */
function ftg_deactivate_ftg_func() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ftg-functionality-deactivator.php';
	ftg_func_Deactivator::ftg_deactivate();
}

register_activation_hook( __FILE__, 'ftg_activate_ftg_func' );
register_deactivation_hook( __FILE__, 'ftg_deactivate_ftg_func' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
define('FTG_POPUP_MANAGER', true);
define('FTG_PLUGIN', plugin_dir_path( __FILE__ ));
define('FTG_PLUGIN_URI', plugin_dir_url( __FILE__ ));
define('FTG_PLUGIN_MENU_PAGE', 'feed-the-grid');
define('FTG_PLUGIN_SUBMENU_PAGE', 'feed-the-grid-settings');
define('FTG_PLUGIN_ADDON_PAGE', 'feed-the-grid-addons');
define('FTG_PLUGIN_MENU_PAGE_URL', get_admin_url() . 'admin.php?page=' . FTG_PLUGIN_MENU_PAGE);
define('FTG_PLUGIN_SUBMENU_PAGE_URL', get_admin_url() . 'admin.php?page=' . FTG_PLUGIN_SUBMENU_PAGE);
define('FTG_PLUGIN_ADDON_PAGE_URL', get_admin_url() . 'admin.php?page=' . FTG_PLUGIN_ADDON_PAGE);
require plugin_dir_path( __FILE__ ) . 'includes/class-ftg-functionality.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ftg_func() {

	$plugin = new ftg_func();
	$plugin->ftg_run();

}
run_ftg_func();

function ftg_register_manager_menu_page() {
	add_menu_page(
		__( 'Feed the grid', 'feed-the-grid' ),
		__( 'Feed the grid', 'feed-the-grid' ),
		'manage_options',
		FTG_PLUGIN_MENU_PAGE,
		'',
		'dashicons-grid-view',
		6
	);

	add_submenu_page(
		FTG_PLUGIN_MENU_PAGE,
		__('Streams', 'feed-the-grid'),
		__('Streams', 'feed-the-grid'),
		'manage_options',
		FTG_PLUGIN_MENU_PAGE,
		'ftg_main_html'
	);

	add_submenu_page(
		FTG_PLUGIN_MENU_PAGE,
		__('Settings', 'feed-the-grid'),
		__('Settings', 'feed-the-grid'),
		'manage_options',
		FTG_PLUGIN_SUBMENU_PAGE,
		'ftg_settings_html'
	);

}
add_action( 'admin_menu', 'ftg_register_manager_menu_page' );

function ftg_main_html() {
	if ( !current_user_can('manage_options') )  {
		wp_die( __('You do not have sufficient permissions to access this page.', 'feed-the-grid') );
	}
	?>
	<div class="ftg-main-wrapper container-fluid" id="ftg-main-wrapper">
		<a href="javascript:void(0)" class="ftg-add-new-stream ftg-primary-button">Add stream</a>
		<div id="ftg-new-stream-dialog" style="display: none;">
			<span class="ftg-setting-dialog-close ftgicon-cancel"></span>
			<div class="ftg-row">
				<h1 class="ftg-section-title with-border first">Stream name</h1>
				<input type="text" class="form-control" id="ftg-stream-name" placeholder="Stream name" value="">
			</div>
			<ul class="ftg-stream-type" style="display: none;">
				<li class="ftg-stream-item ftgicon-instagram" data-type="instagram"></li>
				<li class="ftg-stream-item ftgicon-youtube-squared" data-type="youtube"></li>
				<li class="ftg-stream-item ftgicon-twitter-squared" data-type="twitter"></li>
			</ul>
			<div class="ftg-stream-types" style="display: none;">
				<div class="ftg-instagram-type" data-stream-type="instagram">
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-instagram-username">User</label>
						<div>
							<input type="text" class="form-control" id="ftg-instagram-username" placeholder="Instagram username" value="">
							<p class="form-control-dsc">Enter nickname( with the @ ) of any public Instagram account</p>
						</div>
					</div>
				</div>
				<div class="ftg-youtube-type" data-stream-type="youtube">
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-instagram-username">YouTube channel URL</label>
						<div>
							<input type="text" class="form-control" id="ftg-youtube-channel" placeholder="Youtube channel URL" value="">
							<p class="form-control-dsc">Insert the URL of your YouTube channel, like https://www.youtube.com/channel/UChKaBg1tdEkm000BJjH6NSA</p>
						</div>
					</div>
				</div>
				<div class="ftg-twitter-type" data-stream-type="twitter">
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-twitter-username">Twitter username</label>
						<div>
							<input type="text" class="form-control" id="ftg-twitter-username" placeholder="Twitter username" value="">
							<p class="form-control-dsc">Insert your Twitter page username, like Cohhe_Themes</p>
						</div>
					</div>
				</div>
				<a href="javascript:void(0)" class="ftg-primary-button save-stream">Save stream</a>
			</div>
		</div>
		<h3 class="ftg-main-title">Your stream list</h3>
		<?php
			global $wpdb;
			$stream_list = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'feed_the_grid_streams ORDER BY ID DESC');
			if ( empty($stream_list) ) {
				?>
				<div class="ftg-box">There's no streams to display!</div>
				<?php
			} else {
				?> <div class="ftg-stream-element-wrapper"> <?php
				foreach ( $stream_list as $stream_value ) {
					$stream_types = explode('|', $stream_value->stream_type);
					?>
					<div class="ftg-stream-element-container col-sm-6 col-md-4 col-xl-3">
						<div class="ftg-stream-element" data-id="<?php echo $stream_value->ID; ?>">
							<span class="stream-element-name"><?php echo $stream_value->stream_name; ?></span>
							<span class="stream-element-shortcode">[feed_the_grid id="<?php echo $stream_value->ID; ?>"]</span>
							<img src="<?php echo FTG_PLUGIN_URI; ?>admin/images/loading.gif" class="item-loading" alt="Loading">
							<a href="javascript:void(0)" class="ftg-delete-stream ftgicon-trash"></a>
							<ul class="stream-element-types">
								<!--<li class="stream-element-type ftgicon-facebook-squared <?php echo(in_array('facebook', $stream_types)?'active':''); ?>"></li>-->
								<li class="stream-element-type ftgicon-instagram <?php echo(in_array('instagram', $stream_types)?'active':''); ?>"></li>
								<li class="stream-element-type ftgicon-youtube-squared <?php echo(in_array('youtube', $stream_types)?'active':''); ?>"></li>
								<li class="stream-element-type ftgicon-twitter-squared <?php echo(in_array('twitter', $stream_types)?'active':''); ?>"></li>
							</ul>
						</div>
					</div>
					<?php
				}
				?>
				</div>
				<div id="ftg-setting-sidebar"></div>
				<section class="content">
					<div class="scroll-wrap">
						<article class="content__item">
						</article>
					</div>
					<button class="close-button"><span>Close</span></button>
				</section>
				<?php
			}
		?>
	</div>
	<?php
}

function ftg_settings_html() {
	if ( !current_user_can('manage_options') )  {
		wp_die( __('You do not have sufficient permissions to access this page.', 'feed-the-grid') );
	}
	$main_settings = json_decode(str_replace('\\', '', get_option( 'ftg_main_settings')), true);
	?>
	<div class="ftg-main-wrapper container-fluid">
		<h3 class="ftg-main-title">Main settings</h3>
		<div class="ftg-box">
			<div class="ftg-row">
				<h5 class="ftg-section-title with-border first">Instagram auth settings<p class="ftg-section-dsc">Read more about how to get your Instagram access token <a href="http://documentation.cohhe.com/feedthegrid/knowledgebase/instagram-access-token/">here</a></p></h5>
				<div class="frg-row-group clearfix">
					<span class="ftg-row-label col-sm-2">Access token</span>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="ftg-instagram-auth" placeholder="Access token" value="<?php echo (isset($main_settings['instagram_auth'])?$main_settings['instagram_auth']:''); ?>">
					</div>
				</div>
				<h5 class="ftg-section-title with-border first">YouTube access settings<p class="ftg-section-dsc">Read more about how to get your YouTube key <a href="http://documentation.cohhe.com/feedthegrid/knowledgebase/youtube-api-key/">here</a></p></h5>
				<div class="frg-row-group clearfix">
					<span class="ftg-row-label col-sm-2">Key</span>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="ftg-youtube-key" placeholder="Key" value="<?php echo (isset($main_settings['youtube_key'])?$main_settings['youtube_key']:''); ?>">
					</div>
				</div>
				<h5 class="ftg-section-title with-border first">Twitter access settings<p class="ftg-section-dsc">Read more about how to get your Twitter keys <a href="http://documentation.cohhe.com/feedthegrid/knowledgebase/twitter-access-settings/">here</a></p></h5>
				<div class="frg-row-group clearfix">
					<span class="ftg-row-label col-sm-2">Consumer Key</span>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="ftg-twitter-consumer-key" placeholder="Consumer Key" value="<?php echo (isset($main_settings['twitter_consumer_key'])?$main_settings['twitter_consumer_key']:''); ?>">
					</div>
				</div>
				<div class="frg-row-group clearfix">
					<span class="ftg-row-label col-sm-2">Consumer secret</span>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="ftg-twitter-consumer-secret" placeholder="Consumer secret" value="<?php echo (isset($main_settings['twitter_consumer_secret'])?$main_settings['twitter_consumer_secret']:''); ?>">
					</div>
				</div>
				<div class="frg-row-group clearfix">
					<span class="ftg-row-label col-sm-2">Access Token</span>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="ftg-twitter-client-token" placeholder="Access Token" value="<?php echo (isset($main_settings['twitter_client_token'])?$main_settings['twitter_client_token']:''); ?>">
					</div>
				</div>
				<div class="frg-row-group clearfix">
					<span class="ftg-row-label col-sm-2">Access secret</span>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="ftg-twitter-client-secret" placeholder="Access secret" value="<?php echo (isset($main_settings['twitter_client_secret'])?$main_settings['twitter_client_secret']:''); ?>">
					</div>
				</div>
				<div class="frg-row-group clearfix">
					<a href="javascript:void(0)" class="ftg-primary-button save">Save changes</a>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function ftg_save_popup() {
	$ftg_settings = ( isset($_POST['ftg_settings']) ? $_POST['ftg_settings'] : '' );

	if ( $ftg_settings != '' ) {
		update_option( 'ftg_main_settings', $ftg_settings);
	}

	die(0);
}
add_action( 'wp_ajax_ftg_save_data', 'ftg_save_popup' );

function ftg_save_stream_data() {
	global $wpdb;
	$ftg_stream_name = ( isset($_POST['ftg_stream_name']) ? $_POST['ftg_stream_name'] : '' );
	$ftg_stream_type = ( isset($_POST['ftg_stream_type']) ? $_POST['ftg_stream_type'] : '' );
	$ftg_stream_settings = ( isset($_POST['ftg_stream_settings']) ? $_POST['ftg_stream_settings'] : '' );

	if ( $wpdb->query('INSERT INTO '.$wpdb->prefix.'feed_the_grid_streams (`stream_name`, `stream_type`, `stream_settings`) VALUES ("'.$ftg_stream_name.'", "'.$ftg_stream_type.'", "'.$ftg_stream_settings.'")') === false ) {
		echo '{"save_response": "failed"}';
	} else {
		echo '{"save_response": "'.$wpdb->insert_id.'"}';
	}

	die(0);
}
add_action( 'wp_ajax_ftg_save_stream', 'ftg_save_stream_data' );

function ftg_main_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'id'   => '',
	), $atts ) );
	$output = '';

	if ( $id == '' ) {
		return 'There\'s no ID set for the shortcode!';
	}

	global $wpdb;
	$stream_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'feed_the_grid_streams WHERE ID="'.$id.'"');
	if ( empty($stream_data) ) {
		return 'No valid stream data found!';
	} else {
		$stream_settings = json_decode($stream_data['0']->stream_settings, true);
		$main_settings = ftg_get_settings();

		if ( $stream_settings['stream_type'] == 'instagram' ) {
			$insta_user_id = '';
			if ( !isset($main_settings['instagram_auth']) || $main_settings['instagram_auth'] == '' ) {
				return 'Please fill in the <a href="' . get_admin_url() . 'admin.php?page=feed-the-grid-settings">Instagram settings</a>!';
			}

			if ( ( isset($stream_settings['instagram_cache']) && $stream_settings['instagram_cache'] == 0 ) || !isset($stream_settings['instagram_cache']) ) {
				$stream_settings['instagram_cache'] = 3600;
			}

			if ( ( time() - intval($stream_data['0']->cache_timestamp) ) > intval($stream_settings['instagram_cache']) ) {
				ftg_generate_media_cache( $id );
				$stream_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'feed_the_grid_streams WHERE ID="'.$id.'"');
			}

			if ( isset($stream_settings['instagram_columns']) && $stream_settings['instagram_columns'] != '' ) {
				$initial_load = $stream_settings['instagram_columns']*$stream_settings['instagram_rows'];
				$inst_columns = $stream_settings['instagram_columns'];
			} else {
				$initial_load = '9';
				$inst_columns = '3';
			}

			if ( isset($stream_settings['instagram_rows']) && $stream_settings['instagram_rows'] != '' ) {
				$inst_rows = $stream_settings['instagram_rows'];
			} else {
				$inst_rows = '3';
			}

			$instagram_width = '';
			if ( isset($stream_settings['instagram_width']) && $stream_settings['instagram_width'] != '' ) {
				$instagram_width = 'width: '.$stream_settings['instagram_width'].'px;';
			}

			$instagram_height = '';
			if ( isset($stream_settings['instagram_height']) && $stream_settings['instagram_height'] != '' ) {
				$instagram_height = 'height: '.$stream_settings['instagram_height'].'px;';
			}

			$instagram_cached_data = json_decode(stripcslashes($stream_data['0']->stream_cache));
			$instagram_cached_user = $instagram_cached_data->insta_user_data;
			$instagram_cached_media = $instagram_cached_data->insta_user_media_data;
			if ( !isset($instagram_cached_media) || empty($instagram_cached_media) ) {
				ftg_generate_media_cache( $id );
				$stream_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'feed_the_grid_streams WHERE ID="'.$id.'"');
			}

			$instagram_custom_style = '<style type="text/css">';
			if ( function_exists('ftg_get_custom_css') ) {
				$instagram_custom_style .= ftg_get_custom_css( $stream_settings );
			}
			$instagram_custom_style .= '</style>';

			$instagram_gallery_mode = 'popup';
			if ( isset($stream_settings['instagram_gallery_mode']) && $stream_settings['instagram_gallery_mode'] != '' ) {
				$instagram_gallery_mode = $stream_settings['instagram_gallery_mode'];
			}

			$instagram_gallery_layout = 'grid';
			if ( isset($stream_settings['instagram_gallery_layout']) && $stream_settings['instagram_gallery_layout'] != '' ) {
				$instagram_gallery_layout = $stream_settings['instagram_gallery_layout'];
			}

			$output .= '
			<div class="ftg-main-wrapper">';
				$output .= $instagram_custom_style;
				$output .= '
				<div class="ftg-main-wrapper-inner ftg-layout-'.$instagram_gallery_layout.'" style="'.$instagram_width.$instagram_height.'">
					<div class="ftg-author-info">
						<div class="ftg-author-picture">
							<img src="'.$instagram_cached_user->profile_picture.'" alt="">
						</div>
						<div class="ftg-author-data">
							<span class="ftg-author-nickname">'.$instagram_cached_user->username.'</span>
							<a href="https://www.instagram.com/'.$instagram_cached_user->username.'/" class="ftg-user-follow" target="_blank">Follow</a>
							<p class="ftg-author-bio"><span class="ftg-author-full-name">'.$instagram_cached_user->full_name.'</span>'.$instagram_cached_user->bio.'</p>
							<a href="'.$instagram_cached_user->website.'" class="ftg-author-website">'.$instagram_cached_user->website.'</a>
							<div class="ftg-author-meta">
								<span class="ftg-meta-item"><span class="ftg-item-count">'.$instagram_cached_user->counts->media.'</span> posts</span>
								<span class="ftg-meta-item"><span class="ftg-item-count">'.$instagram_cached_user->counts->followed_by.'</span> followers</span>
								<span class="ftg-meta-item"><span class="ftg-item-count">'.$instagram_cached_user->counts->follows.'</span> following</span>
							</div>
						</div>
					</div>
					<div class="ftg-author-media clearfix" data-media-initial="'.$initial_load.'" data-inst-mode="'.$instagram_gallery_mode.'" data-col="'.$inst_columns.'" data-rows="'.$inst_rows.'">';
						$media_js_arr = array();
						if ( isset($instagram_cached_media) ) {
							foreach ($instagram_cached_media as $media_value) {
								$media_js_arr[] = ftg_return_new_inst_media_item( $media_value, '' );
							}
						}
						$output .= '<script type="text/preloaded" id="ftg-media-json">'.json_encode($media_js_arr).'</script>';
					$output .= '
					</div>
					<div style="text-align: center;">
						<a href="javascript:void(0)" class="ftg-insta-load-more">Load more</a>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>';
		} else if ( $stream_settings['stream_type'] == 'youtube' ) {
			if ( !isset($main_settings['youtube_key']) || $main_settings['youtube_key'] == '' ) {
				return 'Please fill in the <a href="' . get_admin_url() . 'admin.php?page=feed-the-grid-settings">YouTube settings</a>!';
			}

			$stream_cache = get_option('ftg_stream_'.$id.'_cache');
			$stream_cache_time = (isset($stream_settings['youtube_cache'])?$stream_settings['youtube_cache']:'3600');
			if ( !$stream_cache || ( ( time() - intval($stream_cache['cache_timestamp']) ) > intval($stream_cache_time) ) ) {
				ftg_generate_youtube_cache( $id );
				$stream_cache = get_option('ftg_stream_'.$id.'_cache');
			}

			$youtube_channel_id = '';
			if ( isset($stream_settings['source_list']) ) {
				$youtube_channel = explode('/', $stream_settings['source_list']);
				$youtube_channel_id = $youtube_channel[count($youtube_channel)-1];
			}

			$youtube_video_layout = 'grid';
			if ( isset($stream_settings['youtube_video_layout']) && $stream_settings['youtube_video_layout'] != '' ) {
				$youtube_video_layout = $stream_settings['youtube_video_layout'];
			}

			$youtube_width = '';
			if ( isset($stream_settings['youtube_width']) && $stream_settings['youtube_width'] != '' ) {
				$youtube_width = 'width: '.$stream_settings['youtube_width'].'px;';
			}

			$youtube_height = '';
			if ( isset($stream_settings['youtube_height']) && $stream_settings['youtube_height'] != '' ) {
				$youtube_height = 'height: '.$stream_settings['youtube_height'].'px;';
			}

			if ( isset($stream_settings['youtube_columns']) && $stream_settings['youtube_columns'] != '' ) {
				$initial_load = $stream_settings['youtube_columns']*$stream_settings['youtube_rows'];
				$youtube_columns = $stream_settings['youtube_columns'];
			} else {
				$initial_load = '9';
				$youtube_columns = '3';
			}

			if ( isset($stream_settings['youtube_rows']) && $stream_settings['youtube_rows'] != '' ) {
				$youtube_rows = $stream_settings['youtube_rows'];
			} else {
				$youtube_rows = '3';
			}

			$youtube_custom_style = '<style type="text/css">';
			if ( function_exists('ftg_get_custom_css') ) {
				$youtube_custom_style .= ftg_get_custom_css( $stream_settings );
			} else {
				$youtube_custom_style .= '@media (min-width: 768px) and (max-width: 991px) { body .ftg-channel-video-wrapper .ftg-channel-videos .ftg-video-item { width: 50%; } } @media (max-width: 570px) { html body .ftg-channel-video-wrapper .ftg-channel-videos .ftg-video-item { width: 100%; } } @media (max-width: 768px) { body .ftg-channel-video-wrapper .ftg-channel-videos .ftg-video-item { width: 50%; } }';
			}
			$youtube_custom_style .= '</style>';

			$youtube_header_style = (isset($stream_settings['youtube_header_style'])?$stream_settings['youtube_header_style']:'full');
			$youtube_video_mode = (isset($stream_settings['youtube_video_mode'])?$stream_settings['youtube_video_mode']:'popup');
			$youtube_popup_subscribe = (isset($stream_settings['youtube_popup_subscribe'])?$stream_settings['youtube_popup_subscribe']:'true');
			$youtube_popup_comments = (isset($stream_settings['youtube_popup_comments'])?$stream_settings['youtube_popup_comments']:true);

			$header_output = '<div class="ftg-channel-header-info">';
				$header_output .= '<span class="ftg-channel-name">'.$stream_cache['channel_data']['snippet']['title'].'</span>';
				$header_output .= '<div class="ftg-channel-header-meta">';
					$header_output .= '<span class="ftg-channel-header-meta-item ftgicon-youtube-play">'.ftg_format_numbers($stream_cache['channel_data']['statistics']['videoCount']).'</span>';
					$header_output .= '<span class="ftg-channel-header-meta-item ftgicon-user">'.ftg_format_numbers($stream_cache['channel_data']['statistics']['subscriberCount']).'</span>';
					$header_output .= '<span class="ftg-channel-header-meta-item ftgicon-eye">'.ftg_format_numbers($stream_cache['channel_data']['statistics']['viewCount']).'</span>';
				$header_output .= '</div>';
				$header_output .= '<div class="clearfix"></div>';
				$header_output .= '<div class="ftg-channel-description">'.$stream_cache['channel_data']['snippet']['description'].'</div>';
				$header_output .= '<div class="ftg-channel-subscribe">';
					$header_output .= '<script src="https://apis.google.com/js/platform.js"></script>';
					if ( isset($stream_settings['youtube_header_views']) && $stream_settings['youtube_header_views'] !== false ) {
						$header_output .= '<div class="g-ytsubscribe" data-channelid="'.$youtube_channel_id.'" data-layout="default" data-count="default"></div>';
					}
					$header_output .= '</div><div class="clearfix"></div>';
			$header_output .= '</div>';

			if ( isset($stream_settings['youtube_popup_autoplay']) && $stream_settings['youtube_popup_autoplay'] === false ) {
				$stream_custom_style .= '.ftg-video-meta .ftg-video-meta-item.ftgicon-comment-1 { display: none; }';
			}
			$youtube_video_autoplay = (isset($stream_settings['youtube_popup_autoplay'])?$stream_settings['youtube_popup_autoplay']:'true');

			$output .= '<div class="ftg-main-wrapper youtube ftg-layout-'.$youtube_video_layout.'"><link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&subset=latin-ext" rel="stylesheet"><div class="ftg-main-wrapper-inner" style="'.$youtube_width.$youtube_height.'">';
			$output .= $youtube_custom_style;
			if ( is_admin() ) {
				$output .= '<script type="text/preloaded" id="ftg-youtube-cache-json">'.json_encode($stream_cache).'</script>';
			}
			$output .= '<div class="ftg-channel-info ftg-style-'.$youtube_header_style.'">';
				$output .= '<div class="ftg-channel-header" style="background: url('.$stream_cache['channel_data']['brandingSettings']['image']['bannerImageUrl'].') no-repeat;background-size: cover;background-position: center;">';
					$output .= '<img src="'.$stream_cache['channel_data']['snippet']['thumbnails']['default']['url'].'" alt="">';
					if ( $youtube_header_style == 'minimal' || is_admin() ) {
						$output .= $header_output;
					}
				$output .= '</div>';
				if ( $youtube_header_style == 'full' || is_admin() ) {
					$output .= $header_output;
				}
				$output .= '<ul class="ftg-channel-tab"><li>Uploads</li></ul>';
			$output .= '</div>';

			$output .= '<div class="ftg-channel-video-wrapper"><div class="ftg-channel-videos clearfix"><div class="ftg-slider-row" style="display: none;">';
			if ( !empty($stream_cache['video_data']) ) {
				foreach ($stream_cache['video_data'] as $video_key => $video_value) {
					$output .= '<div class="ftg-video-item ftg-mode-'.$youtube_video_mode.'" data-video="'.$video_value['id'].'">';
						$output .= '<div class="ftg-video-image ftgicon-play">';
							$output .= '<img src="'.$video_value['snippet']['thumbnails']['medium']['url'].'" alt="">';
							if ( isset($video_value['contentDetails']['duration']) ) {
								$output .= '<span class="ftg-video-duration">'.ftg_convert_duration($video_value['contentDetails']['duration']).'</span>';
							}
						$output .= '</div>';
						$output .= '<div class="ftg-video-info">';
							$output .= '<a href="https://www.youtube.com/watch?v='.$video_value['id'].'" class="ftg-video-link" target="_blank">'.$video_value['snippet']['title'].'</a>';
							$output .= '<span class="ftg-video-date">'.date('m/d/Y', strtotime($video_value['snippet']['publishedAt'])).'</span>';
							$output .= '<div class="ftg-video-desc">'.substr($video_value['snippet']['description'], 0, 200).'...</div>';
							$output .= '<div class="ftg-video-meta clearfix">
								<span class="ftg-video-meta-item ftgicon-eye">'.ftg_format_numbers($video_value['statistics']['viewCount']).'</span>
								<span class="ftg-video-meta-item ftgicon-thumbs-up-alt">'.ftg_format_numbers($video_value['statistics']['likeCount']).'</span>
								<span class="ftg-video-meta-item ftgicon-comment-1">'.ftg_format_numbers($video_value['statistics']['commentCount']).'</span>
							</div>';
						$output .= '</div>';
						$output .= '<div class="ftg-video-dialog" style="display: none;"><span class="ftg-youtube-dialog-close ftgicon-cancel"></span>';
							$output .= '<div class="ftg-dialog-iframe-wrapper" data-autoplay="'.$youtube_video_autoplay.'" style="background: url('.$video_value['snippet']['thumbnails']['medium']['url'].') no-repeat;background-size: cover;background-position: center;">';
								$output .= preg_replace("/(src=\"[^\"]*\")/", '', $video_value['player']['embedHtml']);
								preg_match("/src=\"([^\"]*)\"/", $video_value['player']['embedHtml'], $iframe_src);
								$output .= '<input type="hidden" class="ftg-video-dialog-iframe" value="'.$iframe_src['1'].'">';
							$output .= '</div>';
							$output .= '<div class="ftg-video-dialog-inner">';
								$output .= '<span class="ftg-video-dialog-title">'.$video_value['snippet']['title'].'</span>';
								$output .= '<div class="ftg-video-dialog-subscribe" data-channel="'.$youtube_channel_id.'" data-visibility="'.$youtube_popup_subscribe.'"><div class="g-ytsubscribe"></div></div>';
								$inner_ratio = ' style="width: 0%;"';
								if ( $video_value['statistics']['dislikeCount']+$video_value['statistics']['likeCount'] > 0 ) {
									$inner_ratio = ' style="width: '.($video_value['statistics']['likeCount']/($video_value['statistics']['dislikeCount']+$video_value['statistics']['likeCount'])*100).'%"';
								}
								$output .= '<div class="ftg-video-dialog-meta">
									<span class="ftg-video-dialog-views">'.number_format($video_value['statistics']['viewCount']).'</span>
									<span class="ftg-video-dialog-ratio"><span class="ftg-video-dialog-ratio-inner"'.$inner_ratio.'></span></span>
									<span class="ftg-video-dialog-thumbsup ftgicon-thumbs-up-alt">'.ftg_format_numbers($video_value['statistics']['likeCount']).'</span>
									<span class="ftg-video-dialog-thumbsdown ftgicon-thumbs-down-alt">'.ftg_format_numbers($video_value['statistics']['dislikeCount']).'</span>
								</div>';
								$video_description = str_replace("\n", '<br>', $video_value['snippet']['description']);
								$video_description = preg_replace('~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~', '<a href="\\0" target="_blank">\\0</a>', $video_description);
								$output .= '<div class="ftg-video-dialog-description">
									<span class="ftg-video-dialog-published">Published at '.date('m/d/Y', strtotime($video_value['snippet']['publishedAt'])).'</span>
									<div class="ftg-video-dialog-full-description">'.$video_description.'</div>
									<span class="ftg-toggle-description" data-text="Show more"></span>
								</div>';
								if ( $youtube_popup_comments === true ) {
									$output .= '<div class="ftg-video-dialog-comments">';
										$output .= '<span class="ftg-comments-loading">Loading comments...</span>';
									$output .= '</div>';
								}
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';

					if ( ($video_key+1) % ($youtube_columns*$youtube_rows) == 0 ) {
						$output .= '</div><div class="ftg-slider-row" style="display: none;">';
					}
				}
			}
			$output .= '</div></div>';

			if ( $youtube_video_layout == 'slider' || is_admin() ) {
				$output .= '<a href="javascript:void(0)" class="ftg-video-next ftgicon-angle-right"></a><a href="javascript:void(0)" class="ftg-video-prev ftgicon-angle-left"></a>';
			}

			if ( $youtube_video_layout == 'grid' || is_admin() ) {
				$output .= '<div style="text-align: center;"><a href="javascript:void(0)" class="ftg-youtube-load-more">Load more</a></div>';
			}

			$output .= '</div>';

			$output .= '</div></div>';
		} else if ( $stream_settings['stream_type'] == 'twitter' ) {
			if ( !isset($main_settings['twitter_consumer_key']) || $main_settings['twitter_consumer_secret'] == '' || $main_settings['twitter_client_token'] == '' || $main_settings['twitter_client_secret'] == '' ) {
				return 'Please fill in the <a href="' . get_admin_url() . 'admin.php?page=feed-the-grid-settings">Twitter access settings</a>!';
			}

			$stream_cache = get_option('ftg_stream_'.$id.'_cache');
			$stream_cache_time = (isset($stream_settings['twitter_cache'])?$stream_settings['twitter_cache']:'3600');
			if ( !$stream_cache || ( ( time() - intval($stream_cache['cache_timestamp']) ) > intval($stream_cache_time) ) ) {
				ftg_generate_twitter_cache( $id );
				$stream_cache = get_option('ftg_stream_'.$id.'_cache');
			}

			$user_data = $stream_cache['user_data'];
			$user_timeline = $stream_cache['timeline_data'];

			$twitter_header_tweets = (isset($stream_settings['twitter_header_tweets'])?$stream_settings['twitter_header_tweets']:true);
			$twitter_header_following = (isset($stream_settings['twitter_header_following'])?$stream_settings['twitter_header_following']:true);
			$twitter_header_followers = (isset($stream_settings['twitter_header_followers'])?$stream_settings['twitter_header_followers']:true);
			$twitter_header_likes = (isset($stream_settings['twitter_header_likes'])?$stream_settings['twitter_header_likes']:true);

			$twitter_header_style = (isset($stream_settings['twitter_header_style'])?$stream_settings['twitter_header_style']:'full');

			$output .= '<div class="ftg-twitter-wrapper header-'.$twitter_header_style.'">';
				if ( $twitter_header_style == 'full' || is_admin() ) {
					$output .= '<div class="ftg-twitter-header">';
						$output .= '<div class="ftg-twitter-header-image" style="'.(isset($user_data['profile_banner_url'])&&$user_data['profile_banner_url']!=''?'background: url('.$user_data['profile_banner_url'].')':'background-color: #'.$user_data['profile_background_color']).'"></div>';
						$output .= '<div class="ftg-twitter-header-info">
										<div class="ftg-twitter-header-inner">';
											if ( $twitter_header_tweets === true ) {
												$output .= '
												<div class="ftg-twitter-header-info-item tweets">
													<span class="ftg-twitter-item-name">Tweets</span>
													<span class="ftg-twitter-item-value" style="color: #'.$user_data['profile_link_color'].'">'.$user_data['statuses_count'].'</span>
												</div>';
											}
											if ( $twitter_header_following === true ) {
												$output .= '
												<div class="ftg-twitter-header-info-item following">
													<span class="ftg-twitter-item-name">Following</span>
													<span class="ftg-twitter-item-value" style="color: #'.$user_data['profile_link_color'].'">'.$user_data['friends_count'].'</span>
												</div>';
											}
											if ( $twitter_header_followers === true ) {
												$output .= '
												<div class="ftg-twitter-header-info-item followers">
													<span class="ftg-twitter-item-name">Followers</span>
													<span class="ftg-twitter-item-value" style="color: #'.$user_data['profile_link_color'].'">'.$user_data['followers_count'].'</span>
												</div>';
											}
											if ( $twitter_header_likes === true ) {
												$output .= '
												<div class="ftg-twitter-header-info-item likes">
													<span class="ftg-twitter-item-name">Likes</span>
													<span class="ftg-twitter-item-value" style="color: #'.$user_data['profile_link_color'].'">'.$user_data['favourites_count'].'</span>
												</div>';
											}
										$output .= '
										</div>
									</div>';
					$output .= '</div>';
				}
				$output .= '<div class="ftg-twitter-content-wrapper clearfix">';
					$output .= '<div class="ftg-twitter-sidebar'.($twitter_header_style=='sidebar'?' header-at-sidebar':'').'">';
									if ( $twitter_header_style == 'sidebar' || is_admin() ) {
										$output .= '<div class="ftg-twitter-sidebar-header" style="'.(isset($user_data['profile_banner_url'])&&$user_data['profile_banner_url']!=''?'background: url('.$user_data['profile_banner_url'].'/600x200)':'background-color: #'.$user_data['profile_background_color']).'"></div>';
									}
									$output .= '
									<div class="ftg-twitter-user-image">'.(isset($user_data['profile_image_url_https'])&&$user_data['profile_image_url_https']!=''?'<img src="'.($twitter_header_style=='full'?str_replace('_normal', '_400x400', $user_data['profile_image_url_https']):str_replace('_normal', '_bigger', $user_data['profile_image_url_https'])).'">':'').'</div>
									<div class="ftg-twitter-user-data">
										<span class="ftg-twitter-user-meta"><a href="https://twitter.com/'.$user_data['screen_name'].'" class="ftg-twitter-user-name">'.$user_data['name'].'</a></span>
										<span class="ftg-twitter-user-meta" style="margin-top: 2px;"><a href="https://twitter.com/'.$user_data['screen_name'].'" class="ftg-twitter-screenname">@'.$user_data['screen_name'].'</a></span>';
										if ( $twitter_header_style == 'full' || is_admin() ) {
											$output .= '<p class="ftg-twitter-user-desc">'.$user_data['description'].'</p>';
											$output .= '<div class="ftg-twitter-user-info">';
												if ( $user_data['location'] ) {
													$output .= '<span class="ftg-twitter-user-meta ftgicon-location">'.$user_data['location'].'</span>';
												}
												if ( $user_data['created_at'] ) {
													$output .= '<span class="ftg-twitter-user-meta ftgicon-calendar">Joined '.date(get_option('date_format'), strtotime($user_data['created_at'])).'</span>';
												}
												if ( $user_data['url'] ) {
													$output .= '<span class="ftg-twitter-user-meta ftgicon-link"><a href="'.$user_data['url'].'">'.$user_data['url'].'</a></span>';
												}
											$output .= '</div>';
										}
									$output .= '
									</div>';
									if ( $twitter_header_style == 'sidebar' || is_admin() ) {
										$output .= '<div class="ftg-twitter-sidebar-info clearfix">';
											if ( $twitter_header_tweets === true ) {
												$output .= '
												<div class="ftg-twitter-header-info-item tweets">
													<span class="ftg-twitter-item-name">Tweets</span>
													<span class="ftg-twitter-item-value" style="color: #'.$user_data['profile_link_color'].'">'.$user_data['statuses_count'].'</span>
												</div>';
											}
											if ( $twitter_header_following === true ) {
												$output .= '
												<div class="ftg-twitter-header-info-item following">
													<span class="ftg-twitter-item-name">Following</span>
													<span class="ftg-twitter-item-value" style="color: #'.$user_data['profile_link_color'].'">'.$user_data['friends_count'].'</span>
												</div>';
											}
											if ( $twitter_header_followers === true ) {
												$output .= '
												<div class="ftg-twitter-header-info-item followers">
													<span class="ftg-twitter-item-name">Followers</span>
													<span class="ftg-twitter-item-value" style="color: #'.$user_data['profile_link_color'].'">'.$user_data['followers_count'].'</span>
												</div>';
											}
										$output .= '</div>';
									}
				$output .= '</div>';
				$output .= '<div class="ftg-twitter-middle">';
					$output .= '<div class="ftg-twitter-middle-head">
									<a href="javascript:void(0)" class="ftg-twitter-change-feed-type active" data-type="tweet">Tweets</a>
									<a href="javascript:void(0)" class="ftg-twitter-change-feed-type" data-type="tweet,reply">Tweets & replies</a>
									<a href="javascript:void(0)" class="ftg-twitter-change-feed-type" data-type="media">Media</a>
								</div>';
					$output .= '<div class="ftg-twitter-middle-tweets">';

					$twitter_status_image = (isset($stream_settings['twitter_status_image'])?$stream_settings['twitter_status_image']:true);
					$twitter_status_fullname = (isset($stream_settings['twitter_status_fullname'])?$stream_settings['twitter_status_fullname']:true);
					$twitter_status_name = (isset($stream_settings['twitter_status_name'])?$stream_settings['twitter_status_name']:true);
					$twitter_status_lock = (isset($stream_settings['twitter_status_lock'])?$stream_settings['twitter_status_lock']:true);
					$twitter_status_date = (isset($stream_settings['twitter_status_date'])?$stream_settings['twitter_status_date']:true);
					$twitter_status_text = (isset($stream_settings['twitter_status_text'])?$stream_settings['twitter_status_text']:true);
					$twitter_status_retweet = (isset($stream_settings['twitter_status_retweet'])?$stream_settings['twitter_status_retweet']:true);
					$twitter_status_heart = (isset($stream_settings['twitter_status_heart'])?$stream_settings['twitter_status_heart']:true);

					foreach ( $user_timeline as $status_data ) {
						$status_data = (array)$status_data;

						$status_type = (isset($status_data['entities']->media)?'media':($status_data['in_reply_to_screen_name']===null?'tweet':'reply'));
						$visible = ($status_type=='tweet'||$status_type=='media'?true:false);
						$output .= '<div class="ftg-twitter-status-item'.($visible?' shown':'').'" data-type="'.$status_type.'">';
							if ( $twitter_status_image === true ) {
								$output .= '<div class="ftg-twitter-status-image"><img src="'.$status_data['user']->profile_image_url_https.'" alt=""></div>';
							}
							$output .= '<div class="ftg-twitter-status-content">';
								$output .= '<div class="ftg-twitter-status-head">';
												if ( $twitter_status_fullname === true ) {
													$output .= '<a href="https://twitter.com/'.$status_data['user']->screen_name.'" class="ftg-twitter-content-user-name">'.$status_data['user']->name.'</a>';
												}
												if ( $twitter_status_name === true ) {
													$output .= '<a href="https://twitter.com/'.$status_data['user']->screen_name.'" class="ftg-twitter-content-screenname">@'.$status_data['user']->screen_name.'</a>';
												}
												if ( $status_data['user']->protected === true && $twitter_status_lock === true ) {
													$output .= '<span class="ftg-twitter-status-protected ftgicon-lock"></span>';
												}
												if ( $twitter_status_date === true ) {
													$output .= '<span class="ftg-twitter-status-date">'.date(get_option('date_format'), strtotime($status_data['created_at'])).'</span>';
												}
												
											$output .= '</div>';
											if ( $twitter_status_text === true ) {
												$prepared_text = $status_data['text'];
												foreach ($status_data['entities'] as $entity_type => $entity_type_data) {
													if ( !empty($entity_type_data) ) {
														foreach ($entity_type_data as $entity_value) {
															$entity_value = (array)$entity_value;
															if ( $entity_type == 'urls' ) {
																$prepared_text = str_replace($entity_value['url'], '<a href="'.$entity_value['expanded_url'].'" class="ftg-twitter-status-url">'.$entity_value['display_url'].'</a>', $prepared_text);
															} else if ( $entity_type == 'user_mentions' ) {
																$prepared_text = str_replace('@'.$entity_value['screen_name'], '<a href="https://twitter.com/'.$entity_value['screen_name'].'" class="ftg-twitter-status-mention">@'.$entity_value['screen_name'].'</a>', $prepared_text);
															} else if ( $entity_type == 'media' ) {
																$prepared_text = str_replace($entity_value['url'], '<div class="ftg-twitter-status-media"><img src="'.$entity_value['media_url_https'].'" alt=""></div>', $prepared_text);
															}
														}
													}
												}
												$output .= '<p class="ftg-twitter-status-text">'.$prepared_text.'</p>';
											}
								$output .= '<div class="ftg-twitter-status-meta">';
												if ( $twitter_status_retweet === true ) {
													$output .= '<span class="ftg-twitter-status-retweets ftgicon-retweet">'.($status_data['retweet_count']>0?$status_data['retweet_count']:'').'</span>';
												}
												if ( $twitter_status_heart === true ) {
													$output .= '<span class="ftg-twitter-status-favorites ftgicon-heart-1">'.($status_data['favorite_count']>0?$status_data['favorite_count']:'').'</span>';
												}
											$output .= '	
											</div>';
							$output .= '</div>';
						$output .= '</div>';
					}
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		}
	}

	return $output;
}
add_shortcode('feed_the_grid','ftg_main_shortcode');

function ftg_convert_duration( $youtube_time ) {
	preg_match("/\\d+H/", $youtube_time, $hours);
	preg_match("/\\d+M/", $youtube_time, $minutes);
	preg_match("/\\d+S/", $youtube_time, $seconds);

	if ( !empty($hours) ) {
		$hours = intval(str_replace('H', '', $hours['0']));
		if ( $hours < 10 ) {
			$hours = '0'.$hours;
		}
	} else {
		$hours = '00';
	}

	if ( !empty($minutes) ) {
		$minutes = intval(str_replace('M', '', $minutes['0']));
		if ( $minutes < 10 ) {
			$minutes = '0'.$minutes;
		}
	} else {
		$minutes = '00';
	}

	if ( !empty($seconds) ) {
		$seconds = intval(str_replace('S', '', $seconds['0']));
		if ( $seconds < 10 ) {
			$seconds = '0'.$seconds;
		}
	} else {
		$seconds = '00';
	}

	if ( intval($hours) != 0 ) {
		return $hours.':'.$minutes.':'.$seconds;
	} else {
		return $minutes.':'.$seconds;
	}
}

function ftg_return_new_inst_media_item( $media_value, $comments = '' ) {
	$media_value->insta_comments = $comments;
	$media_value->human_date = human_time_diff($media_value->created_time,current_time('timestamp'));

	return json_encode( $media_value );
}

function ftg_format_numbers( $n, $precision = 2 ) {
	if ($n < 1000) {
        // Anything less than a thousand
       $n_format = number_format($n);
    } else if ($n < 1000000) {
        // Anything less than a million
       $n_format = number_format($n / 1000, $precision) . 'k';
    } else if ($n < 1000000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, $precision) . 'm';
    } else {
        // At least a billion
        $n_format = number_format($n / 1000000000, $precision) . 'b';
    }

    return $n_format;
}

function ftg_return_inst_media_item( $media_value, $comments = '' ) {
	$output = '
	<div class="ftg-media-item">
		<a href="javascript:void(0)">
			<img src="'.$media_value->images->low_resolution->url.'" alt="">
			<div class="ftg-media-item-overlay">
				<div class="ftg-overlay-meta">
					<span class="ftg-media-item-likes ftgicon-heart-1">'.$media_value->likes->count.'</span>
					<span class="ftg-media-item-comments ftgicon-comment-1">'.$media_value->comments->count.'</span>
				</div>
			</div>
		</a>
		<div class="ftg-media-item-dialog" style="display: none;">
			<span class="ftg-media-dialog-close ftgicon-cancel"></span>
			<div class="ftg-media-dialog-image">
				<img src="'.$media_value->images->standard_resolution->url.'" alt="">
			</div>
			<div class="ftg-media-dialog-side">
				<div class="ftg-media-dialog-side-meta">
					<img src="'.$media_value->user->profile_picture.'" class="ftg-media-dialog-profile" alt="">
					<a href="https://instagram.com/'.$media_value->user->username.'" class="ftg-media-dialog-link">'.$media_value->user->username.'</a>
					<a href="'.$media_value->link.'" class="ftg-media-dialog-instagram" target="_blank">View on instagram</a>
					<div class="clearfix"></div>
					<span class="ftg-media-dialog-likes ftgicon-heart-1">'.$media_value->likes->count.'</span>
					<span class="ftg-media-dialog-comments ftgicon-comment-1">'.$media_value->comments->count.'</span>
					<span class="ftg-media-dialog-date">'.human_time_diff($media_value->created_time,current_time('timestamp')).' ago</span>
				</div>
				<div class="ftg-media-dialog-caption">
					<a href="https://instagram.com/'.$media_value->caption->from->username.'">'.$media_value->caption->from->username.'</a>
					<p>'.$media_value->caption->text.'</p>';
					if ( $comments != '' ) {
						$output .= '<div class="ftg-media-dialog-comments-container">';
							foreach ($comments as $comment_key => $comment_value) {
								preg_match('/(@\S+)/', $comment_value->text, $matches);
								if ( !empty($matches) ) {
									foreach ($matches as $user_value) {
										$added_link = '<a href="https://instagram.com/'.str_replace('@', '', $user_value).'">'.$user_value.'</a>';
										$comment_value->text = preg_replace('/(@\S+)/', $added_link, $comment_value->text);
									}
								}
								
								$output .= '<div class="ftg-media-dialog-comment-item">
									<a href="https://instagram.com/'.$comment_value->from->username.'">'.$comment_value->from->username.'</a>
									<p>'.$comment_value->text.'</p>
								</div>';
							}
						$output .= '</div>';
					}
				$output .= '
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>';

	return $output;
}

function ftg_load_stream_data() {
	global $wpdb;
	$output = '';
	$ftg_stream_id = ( isset($_POST['ftg_stream_id']) ? $_POST['ftg_stream_id'] : '' );

	$stream_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'feed_the_grid_streams WHERE ID="'.$ftg_stream_id.'"');

	if ( !empty($stream_data) ) {
		$stream_settings = json_decode($stream_data['0']->stream_settings, true);
		?>
		<div class="ftg-side-settings" data-id="<?php echo $stream_data['0']->ID; ?>" data-type="<?php echo $stream_settings['stream_type']; ?>">
			<div class="ftg-side-setting-tabs clearfix">
				<ul>
					<li class="ftg-tab-item ftgicon-briefcase active" data-tab="source">Source</li>
					<li class="ftg-tab-item ftgicon-th" data-tab="size">Sizes</li>
					<li class="ftg-tab-item ftgicon-newspaper" data-tab="info">Info</li>
					<li class="ftg-tab-item ftgicon-cog" data-tab="ui">UI</li>
				</ul>
			</div>
			<div class="ftg-source-settings ftg-tab-item-content active" data-tab-item="source">
				<?php if ( $stream_settings['stream_type'] == 'instagram' ) { ?>
					<?php 
						if ( function_exists('ftgp_add_source_settings') ) {
							do_action('ftg_source_settings', $stream_settings);
						} else {
							?>
							<div class="frg-row-group clearfix">
								<label class="ftg-row-label" for="ftg-instagram-sources">Instagram source</label>
								<div>
									<?php
										if ( isset($stream_settings['source_list']) && $stream_settings['source_list'] != '' ) {
											$source_full_list = explode('||', $stream_settings['source_list']);
										}
									?>
									<input type="text" id="ftg-instagram-source" class="form-control" value="<?php echo (isset($source_full_list['0'])?$source_full_list['0']:''); ?>">
								</div>
								<p class="form-control-dsc">You can add any public @username, #hashtag or Instagram photo URL.</p>
							</div>
							<div class="frg-row-group ftg-feature-locked clearfix">
								<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
								<label class="ftg-row-label">Filters</label>
								<p class="form-control-dsc">Filter your Instagram photos by setting various conditions, include or exclude images by using different combinations of @username, #hashtag or photo URL.</p>
								<div class="ftg-filter-content clearfix">
									<div class="ftg-filter-left">
										<select class="form-control" disabled>
											<option value="except">Except</option>
											<option value="only">Only</option>
										</select>
									</div>
									<div class="ftg-filter-right">
										<ul id="ftg-instagram-source-filters" class="ftg-tag-list tagit ui-widget ui-widget-content ui-corner-all" style="margin-top: 0;">
											<li class="tagit-new"><input type="text" class="ui-widget-content ui-autocomplete-input" autocomplete="off" style="opacity: 0;" disabled></li>
										</ul>
									</div>
								</div>
								<div class="ftg-other-filter-wrapper clearfix"></div>
								<a href="javascript:void(0)" class="profeature-33 ftg-secondary-button">Add condition</a>
							</div>
							<?php
						}
					 ?>
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-instagram-limit">Limit photos</label>
						<div class="ftg-number-field">
							<span class="ftg-number-minus">-</span><input type="number" id="ftg-instagram-limit" class="form-control number" value="<?php echo (isset($stream_settings['instagram_limit'])?$stream_settings['instagram_limit']:'9')?>" /><span class="ftg-number-plus">+</span>
						</div>
						<p class="form-control-dsc">Set "0" to show all photos.</p>
					</div>
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-instagram-cache">Cache media</label>
						<div class="ftg-number-field ftg-inline">
							<span class="ftg-number-minus">-</span><input type="number" id="ftg-instagram-cache" class="form-control number" value="<?php echo (isset($stream_settings['instagram_cache'])?$stream_settings['instagram_cache']:'3600')?>" /><span class="ftg-number-plus">+</span>
						</div>
						<a href="javascript:void(0)" id="ftg-reset-cache" class="ftg-secondary-button" data-id="<?php echo $ftg_stream_id; ?>" data-type="<?php echo $stream_settings['stream_type']; ?>">Reset cache</a>
						<p class="form-control-dsc">Time in seconds representing how long to cache the media images.</p>
					</div>
				<?php } else if ( $stream_settings['stream_type'] == 'youtube' ) { ?>
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-youtube-source">YouTube video source</label>
						<input type="text" id="ftg-youtube-source" class="form-control" value="<?php echo (isset($stream_settings['source_list'])?$stream_settings['source_list']:''); ?>">
						<p class="form-control-dsc">The URL of your YouTube channel.</p>
					</div>
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-youtube-limit">Limit videos</label>
						<div class="ftg-number-field">
							<span class="ftg-number-minus">-</span><input type="number" id="ftg-youtube-limit" class="form-control number" value="<?php echo (isset($stream_settings['youtube_limit'])?$stream_settings['youtube_limit']:'20')?>" /><span class="ftg-number-plus">+</span>
						</div>
						<p class="form-control-dsc">Set a value from 1 till 50.</p>
					</div>
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-instagram-cache">Cache videos</label>
						<div class="ftg-number-field ftg-inline">
							<span class="ftg-number-minus">-</span><input type="number" id="ftg-youtube-cache" class="form-control number" value="<?php echo (isset($stream_settings['youtube_cache'])?$stream_settings['youtube_cache']:'3600')?>" /><span class="ftg-number-plus">+</span>
						</div>
						<a href="javascript:void(0)" id="ftg-reset-cache" class="ftg-secondary-button" data-id="<?php echo $ftg_stream_id; ?>" data-type="<?php echo $stream_settings['stream_type']; ?>">Reset cache</a>
						<p class="form-control-dsc">Time in seconds representing how long to cache videos.</p>
					</div>
				<?php } else if ( $stream_settings['stream_type'] == 'twitter' ) { ?>
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-twitter-source">Twitter feed source</label>
						<input type="text" id="ftg-twitter-source" class="form-control" value="<?php echo (isset($stream_settings['source_list'])?$stream_settings['source_list']:''); ?>">
						<p class="form-control-dsc">The Twitter user handle.</p>
					</div>
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-twitter-limit">Limit tweets</label>
						<div class="ftg-number-field">
							<span class="ftg-number-minus">-</span><input type="number" id="ftg-twitter-limit" class="form-control number" value="<?php echo (isset($stream_settings['twitter_limit'])?$stream_settings['twitter_limit']:'30')?>" /><span class="ftg-number-plus">+</span>
						</div>
						<p class="form-control-dsc">Set a value from 1 till 500.</p>
					</div>
					<div class="frg-row-group clearfix">
						<label class="ftg-row-label" for="ftg-twitter-cache">Cache tweets</label>
						<div class="ftg-number-field ftg-inline">
							<span class="ftg-number-minus">-</span><input type="number" id="ftg-twitter-cache" class="form-control number" value="<?php echo (isset($stream_settings['twitter_cache'])?$stream_settings['twitter_cache']:'3600')?>" /><span class="ftg-number-plus">+</span>
						</div>
						<a href="javascript:void(0)" id="ftg-reset-cache" class="ftg-secondary-button" data-id="<?php echo $ftg_stream_id; ?>" data-type="<?php echo $stream_settings['stream_type']; ?>">Reset cache</a>
						<p class="form-control-dsc">Time in seconds representing how long to cache videos.</p>
					</div>
				<?php } ?>
			</div>
			<div class="ftg-size-settings ftg-tab-item-content" data-tab-item="size">
				<?php 
				if ( function_exists('ftgp_add_source_bottom_settings') ) {
					do_action('ftg_source_bottom_settings', $stream_settings);
				} else {
					if ( $stream_settings['stream_type'] == 'instagram' || $stream_settings['stream_type'] == 'youtube' ) {
						?>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label" for="ftg-instagram-width">Grid width (in pixels)</label>
							<div class="ftg-number-field ftg-inline">
								<span class="ftg-number-minus">-</span><input type="number" id="profeature1" class="form-control number" placeholder="Auto" value="" disabled /><span class="ftg-number-plus">+</span>
							</div>
							<div class="ftg-checkbox <?php echo ((isset($stream_settings['instagram_width_resp'])&&$stream_settings['instagram_width_resp']=='true')||!isset($stream_settings['instagram_width_resp'])?'active':'')?>">
								<input type="checkbox" id="profeature2" disabled><label for="ftg-instagram-width-resp">Responsive</label>
							</div>
						</div>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label" for="ftg-instagram-height">Grid height (in pixels)</label>
							<div class="ftg-number-field ftg-inline">
								<span class="ftg-number-minus">-</span><input type="number" id="profeature3" class="form-control number" placeholder="Auto" value="" disabled /><span class="ftg-number-plus">+</span>
							</div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature4" disabled><label for="ftg-instagram-height-resp">By content</label>
							</div>
						</div>
						<div class="frg-row-group ftg-feature-locked ftg-m-m-15 ftg-m-b-25 clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<div class="ftg-inline ftg-number ftg-col-3 ftg-p-s-15">
								<label class="ftg-row-label" for="ftg-instagram-columns">Grid columns</label>
								<div class="ftg-number-field">
									<span class="ftg-number-minus">-</span><input type="number" id="profeature5" class="form-control number" value="3" disabled /><span class="ftg-number-plus">+</span>
								</div>
							</div>
							<div class="ftg-inline ftg-number ftg-col-3 ftg-p-s-15">
								<label class="ftg-row-label" for="ftg-instagram-rows">Grid rows</label>
								<div class="ftg-number-field">
									<span class="ftg-number-minus">-</span><input type="number" id="profeature6" class="form-control number" value="3" disabled /><span class="ftg-number-plus">+</span>
								</div>
							</div>
							<div class="ftg-inline ftg-number ftg-col-3 ftg-p-s-15">
								<label class="ftg-row-label" for="ftg-instagram-rows">Grid gutter</label>
								<div class="ftg-number-field">
									<span class="ftg-number-minus">-</span><input type="number" id="profeature7" class="form-control number" value="30" disabled /><span class="ftg-number-plus">+</span>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label" for="ftg-instagram-filters">Responsive breakpoints</label>
							<p class="form-control-dsc ftg-m-b-15">Specify breakpoints to control how your grid looks on different browser widths.</p>
							<a href="javascript:void(0)" class="ftg-add-more-breakpoints ftg-secondary-button" disabled>Add breakpoint</a>
						</div>
						<?php
					}
				}
				?>
			</div>
			<div class="ftg-size-settings ftg-tab-item-content" data-tab-item="info">
				<?php
				if ( function_exists('ftgp_add_info_settings') ) {
					do_action('ftg_info_settings', $stream_settings);
				} else {
					if ( $stream_settings['stream_type'] == 'instagram' ) { ?>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label" for="ftg-instagram-width">Gallery info</label>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature8" checked disabled ><label>Likes counter</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature9" checked disabled><label>Comments counter</label>
							</div>
						</div>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label" for="ftg-instagram-width">Popup info</label>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature10" checked disabled><label>Username</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature11" checked disabled><label>Instagram link</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature12" checked disabled><label>Likes counter</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature13" checked disabled><label>Comments counter</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature14" checked disabled><label>Location</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature15" checked disabled><label>Passed time</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature16" checked disabled><label>Description</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature17" checked disabled><label>Comments</label>
							</div>
						</div>
					<?php } else if ( $stream_settings['stream_type'] == 'youtube' ) { ?>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label">Header info</label>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature21" checked disabled><label>Logo</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature22" checked disabled><label>Banner</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature23" checked disabled><label>Channel name</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature24" checked disabled><label>Channel description</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature25" checked disabled><label>Videos counter</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature26" checked disabled><label>Subscribers count</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature27" checked disabled><label>Views counter</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature28" checked disabled><label>Subscribe button</label>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label">Video info</label>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature29" checked disabled><label>Play icon</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature30" checked disabled><label>Duration</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature31" checked disabled><label>Title</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature32" checked disabled><label>Date</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature33" checked disabled><label>Description</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature34" checked disabled><label>Views counter</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature35" checked disabled><label>Likes counter</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature36" checked disabled><label>Comments counter</label>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label">Popup info</label>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature37" checked disabled><label>Autoplay when popup opened</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature38" checked disabled><label>Title</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature39" checked disabled><label>Subscribe button</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature40" checked disabled><label>Views counter</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature41" checked disabled><label>Likes counter</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature42" checked disabled><label>Dislikes counter</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature43" checked disabled><label>Likes ratio</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature44" checked disabled><label>Date</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature45" checked disabled><label>Description</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature46" checked disabled><label>Description more button</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature47" checked disabled><label>Comments</label>
							</div>
							<div class="clearfix"></div>
						</div>
					<?php } else if ( $stream_settings['stream_type'] == 'twitter' ) { ?>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label">Tweet info</label>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature80" checked disabled><label>Tweet image</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature81" checked disabled><label>Tweet full name</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature82" checked disabled><label>Tweet name</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature83" checked disabled><label>Tweet lock icon</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature84" checked disabled><label>Tweet date</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature85" checked disabled><label>Tweet text</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature86" checked disabled><label>Tweet retweet</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature87" checked disabled><label>Tweet heart</label>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label">Tweet info</label>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature88" checked disabled><label>Header tweets</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="profeature89" checked disabled><label>Header following</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="90" checked disabled><label>Header followers</label>
							</div>
							<div class="clearfix"></div>
							<div class="ftg-checkbox active">
								<input type="checkbox" id="91" checked disabled><label>Header likes</label>
							</div>
							<div class="clearfix"></div>
						</div>
					<?php }
				}
				?>
			</div>
			<div class="ftg-size-settings ftg-tab-item-content" data-tab-item="ui">
				<?php
				if ( function_exists('ftgp_add_ui_settings') ) {
					do_action('ftg_ui_settings', $stream_settings);
				} else {
					if ( $stream_settings['stream_type'] == 'instagram' ) {
						?>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label" for="profeature18">Gallery mode</label>
							<select class="form-control" id="profeature18" disabled>
								<option value="popup" selected>Popup</option>
								<option value="instagram">Instagram</option>
							</select>
						</div>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label" for="profeature19">Gallery layout</label>
							<select class="form-control" id="profeature19" disabled>
								<option value="grid" selected>Grid</option>
								<option value="slider">Horizontal slider</option>
							</select>
						</div>
						<?php } else if ( $stream_settings['stream_type'] == 'youtube' ) { ?>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label" for="profeature20">Video layout</label>
							<select class="form-control" id="profeature20" disabled>
								<option value="slider" selected>Horizontal slider</option>
								<option value="grid">Grid</option>
							</select>
						</div>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label" for="ftg-youtube-header-style">Header style</label>
							<select class="form-control" id="profeature48" disabled>
								<option value="full" selected>Full</option>
								<option value="minimal">Minimal</option>
							</select>
						</div>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label" for="ftg-youtube-video-mode">Video play mode</label>
							<select class="form-control" id="profeature49" disabled>
								<option value="popup" selected>Popup</option>
								<option value="inline">Inline</option>
								<option value="youtube">YouTube</option>
							</select>
						</div>
						<?php
					} else if ( $stream_settings['stream_type'] == 'twitter' ) { ?>
						<div class="frg-row-group ftg-feature-locked clearfix">
							<div class="ftg-locked-text">This feature is only available for the <a href="https://cohhe.com/project-view/feed-grid-pro/">Pro version</a>!</div>
							<label class="ftg-row-label">Header style</label>
							<select class="form-control" id="profeature92" disabled>
								<option value="full">Full</option>
								<option value="sidebar">Sidebar</option>
							</select>
						</div>
					<?php
					}
				}
				?>
			</div>
			<a href="javascript:void(0)" class="ftg-save-main-settings ftg-primary-button">Save</a>
			<span class="ftg-save-main-settings-message ftgicon-ok-circled">Settings saved successfully</span>
		</div>
		<div class="ftg-side-preview">
			<?php echo ftg_main_shortcode( array( 'id'=>$ftg_stream_id ) ); ?>
		</div>
		<div class="clearfix"></div>
	<?php }

	die(0);
}
add_action( 'wp_ajax_ftg_load_stream', 'ftg_load_stream_data' );

function ftg_load_stream_content() {
	global $wpdb;
	$output = '';
	$ftg_stream_id = ( isset($_POST['ftg_stream_id']) ? $_POST['ftg_stream_id'] : '' );

	echo ftg_main_shortcode( array( 'id'=>$ftg_stream_id ) );

	die(0);
}
add_action( 'wp_ajax_ftg_load_stream_content', 'ftg_load_stream_content' );

function ftg_get_settings() {
	$settings = str_replace('\\', '', get_option('ftg_main_settings'));
	return json_decode($settings, true);
}

function ftg_instagram_load_more() {
	$output = '';
	$main_settings = ftg_get_settings();
	$ftg_max_id = ( isset($_POST['ftg_max_id']) ? $_POST['ftg_max_id'] : '' );
	$ftg_user_id = ( isset($_POST['ftg_user_id']) ? $_POST['ftg_user_id'] : '' );
	$media_data = array();

	$insta_user_media = ftg_get_url_content('https://api.instagram.com/v1/users/'.$ftg_user_id.'/media/recent/?access_token='.$main_settings['instagram_auth'].'&count=6&max_id='.$ftg_max_id);
	if ( $insta_user_media === false ) {
		echo 'There was an issue while fetching user media!';
	}

	$instagram_media_data = json_decode($insta_user_media);
	$media_data['pagination'] = $instagram_media_data->pagination->next_max_id;
	foreach ($instagram_media_data->data as $media_value) {
		$media_data['media'] .= ftg_return_inst_media_item( $media_value );	
	}

	echo json_encode($media_data);;

	die(0);
}
add_action( 'wp_ajax_ftg_insta_load_more', 'ftg_instagram_load_more' );
add_action( 'wp_ajax_nopriv_ftg_insta_load_more', 'ftg_instagram_load_more' );

function ftg_update_stream_settings() {
	global $wpdb;
	$ftg_stream_id = ( isset($_POST['ftg_stream_id']) ? $_POST['ftg_stream_id'] : '' );
	$ftg_stream_settings = ( isset($_POST['ftg_stream_settings']) ? $_POST['ftg_stream_settings'] : '' );

	$stream_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'feed_the_grid_streams WHERE ID="'.$ftg_stream_id.'"');
	if ( !empty($stream_data) ) {
		$stream_settings = json_decode($stream_data['0']->stream_settings, true);
		$new_stream_settings = json_decode(stripslashes($ftg_stream_settings), true);

		if ( intval($stream_settings['youtube_limit']) != intval($new_stream_settings['youtube_limit']) ) {
			ftg_generate_youtube_cache( $ftg_stream_id, $new_stream_settings['youtube_limit'] );
		}

		foreach ($new_stream_settings as $new_key => $new_value) {
			$stream_settings[$new_key] = $new_value;
		}

		if ( $wpdb->query('UPDATE '.$wpdb->prefix.'feed_the_grid_streams SET stream_settings='.json_encode(json_encode($stream_settings)).' WHERE ID="'.$ftg_stream_id.'"') === false ) {
			echo '{"save_response": "failed"}';
		} else {
			echo '{"save_response": "'.$wpdb->insert_id.'"}';
		}
	}

	die(0);
}
add_action( 'wp_ajax_ftg_save_stream_settings', 'ftg_update_stream_settings' );

function ftg_generate_media_cache( $stream_id ) {
	global $wpdb;
	$regen_results = false;

	$stream_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'feed_the_grid_streams WHERE ID="'.$stream_id.'"');
	if ( !empty($stream_data) ) {
		$stream_settings = json_decode($stream_data['0']->stream_settings, true);
		$main_settings = ftg_get_settings();
		$stream_cache = array();

		$insta_user_id = '';

		if ( !isset($main_settings['instagram_auth']) ) {
			$regen_results = 'Please fill in the <a href="' . get_admin_url() . 'admin.php?page=feed-the-grid-settings">Instagram settings</a>!';
		}

		if ( isset($stream_settings['source_list']) && $stream_settings['source_list'] != '' ) {
			$main_source = explode('||', $stream_settings['source_list']);
		}

		$test_inst_user = ftg_get_url_content('https://api.instagram.com/v1/users/search?q='.str_replace('@', '', $main_source['0']).'&access_token='.$main_settings['instagram_auth']);
		if ( $test_inst_user === false ) {
			$regen_results = 'There was an issue while validating the username!';
		}

		$insta_username = json_decode($test_inst_user, true);
		$insta_user_id = $insta_username['data']['0']['id'];

		$inst_user_data = ftg_get_url_content('https://api.instagram.com/v1/users/'.$insta_user_id.'/?access_token='.$main_settings['instagram_auth']);
		if ( $inst_user_data === false ) {
			$regen_results = 'There was an issue while fetching user data!';
		}
		$instagram_user_data = json_decode($inst_user_data);
		$stream_cache['insta_user_data'] = $instagram_user_data->data;

		// Check for multiple feeds
		if ( function_exists('ftgp_check_instagram_feeds') && isset($stream_settings['source_list']) && $stream_settings['source_list'] != '' ) {
			$instagram_media_array = ftgp_check_instagram_feeds( $stream_settings, $main_settings );
		}

		foreach ($instagram_media_array as $media_value) {
			if ( $media_value->comments->count > 0 ) {
				$insta_media_comments = ftg_get_url_content('https://api.instagram.com/v1/media/'.$media_value->id.'/comments?access_token='.$main_settings['instagram_auth']);
				if ( $insta_media_comments !== false ) {
					$insta_comments = json_decode($insta_media_comments);
					if ( empty($insta_comments->data) ) {
						$media_value->insta_media_comments = array();
					} else {
						$media_value->insta_media_comments = $insta_comments->data;
					}
				}
			}
		}

		$stream_cache['insta_user_media_data'] = $instagram_media_array;

		if ( $wpdb->query('UPDATE '.$wpdb->prefix.'feed_the_grid_streams SET stream_cache="'.addslashes(json_encode($stream_cache, JSON_UNESCAPED_UNICODE)).'", cache_timestamp="'.time().'" WHERE ID="'.$stream_id.'"') === false ) {
			$regen_results = false;
		} else {
			$regen_results = true;
		}
	}

	return $regen_results;
}

function ftg_generate_youtube_cache( $stream_id, $new_limit = '' ) {
	global $wpdb;
	$regen_results = false;

	$stream_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'feed_the_grid_streams WHERE ID="'.$stream_id.'"');
	if ( !empty($stream_data) ) {
		$stream_settings = json_decode($stream_data['0']->stream_settings, true);
		$main_settings = ftg_get_settings();
		$stream_cache = array();

		$youtube_channel_id = '';
		if ( isset($stream_settings['source_list']) ) {
			$youtube_channel = explode('/', $stream_settings['source_list']);
			$youtube_channel_id = $youtube_channel[count($youtube_channel)-1];
		}

		$get_channel_data = ftg_get_url_content('https://www.googleapis.com/youtube/v3/channels?part=snippet,statistics,brandingSettings&id='.$youtube_channel_id.'&key='.$main_settings['youtube_key']);
		if ( $get_channel_data === false ) {
			$regen_results = 'There was an issue while getting channel data!';
		}
		$youtube_user_channel_data = json_decode($get_channel_data, true);
		$stream_cache['channel_data'] = $youtube_user_channel_data['items']['0'];

		$video_limit = (isset($stream_settings['youtube_limit'])&&$stream_settings['youtube_limit']>=0?$stream_settings['youtube_limit']:'20');

		if ( $new_limit != '' ) {
			$video_limit = $new_limit;
		}

		$get_yt_channel = ftg_get_url_content('https://www.googleapis.com/youtube/v3/search?key='.$main_settings['youtube_key'].'&channelId='.$youtube_channel_id.'&part=snippet,id&order=date&maxResults='.$video_limit);
		if ( $get_yt_channel === false ) {
			$regen_results = 'There was an issue while getting user data!';
		}
		$youtube_channel_data = json_decode($get_yt_channel, true);

		$all_video_ids = array();
		foreach ($youtube_channel_data['items'] as $video_value) {
			if ( isset($video_value['id']['videoId']) ) {
				$all_video_ids[] = $video_value['id']['videoId'];
			}
		}

		$get_yt_channel_videos = ftg_get_url_content('https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics,contentDetails,player&key='.$main_settings['youtube_key'].'&id='.implode(',', $all_video_ids));
		$youtube_channel_video_data = json_decode($get_yt_channel_videos, true);
		// var_dump($youtube_channel_video_data);

		$stream_cache['video_data'] = $youtube_channel_video_data['items'];
		$stream_cache['cache_timestamp'] = time();

		update_option('ftg_stream_'.$stream_id.'_cache', $stream_cache);
		$regen_results = true;
	}

	return $regen_results;
}

function ftg_generate_twitter_cache( $stream_id, $new_limit = '' ) {
	global $wpdb;
	$regen_results = false;

	$stream_data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'feed_the_grid_streams WHERE ID="'.$stream_id.'"');
	if ( !empty($stream_data) ) {
		$stream_settings = json_decode($stream_data['0']->stream_settings, true);
		$main_settings = ftg_get_settings();
		$stream_cache = array();

		if ( !isset($stream_settings['source_list']) ) {
			return 'No source provided';
		}

		require_once (plugin_dir_path( __FILE__ ) . 'includes/twitterOauth/twitteroauth.php');
		$connection = new TwitterOAuth($main_settings['twitter_consumer_key'], $main_settings['twitter_consumer_secret'], $main_settings['twitter_client_token'], $main_settings['twitter_client_secret']);
		
		$user_content = $connection->get("https://api.twitter.com/1.1/users/show.json?screen_name=".$stream_settings['source_list']);
		$stream_cache['user_data'] = (array)$user_content;

		$tweet_limit = (isset($stream_settings['twitter_limit'])&&$stream_settings['twitter_limit']>=0?$stream_settings['twitter_limit']:'30');
		if ( $new_limit != '' ) {
			$tweet_limit = $new_limit;
		}

		$timeline_content = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$stream_settings['source_list']."&count=".$tweet_limit);
		$stream_cache['timeline_data'] = (array)$timeline_content;
		$stream_cache['cache_timestamp'] = time();

		update_option('ftg_stream_'.$stream_id.'_cache', $stream_cache);
		$regen_results = true;
	}

	return $regen_results;
}

function ftg_delete_stream_data() {
	global $wpdb;
	$ftg_stream_id = ( isset($_POST['ftg_stream_id']) ? $_POST['ftg_stream_id'] : '' );

	if ( $wpdb->query('DELETE FROM '.$wpdb->prefix.'feed_the_grid_streams WHERE ID="'.$ftg_stream_id.'"') === false ) {
		echo '{"save_response": "failed"}';
	} else {
		echo '{"save_response": "'.$wpdb->insert_id.'"}';
		delete_option('ftg_stream_'.$ftg_stream_id.'_cache');
	}

	die(0);
}
add_action( 'wp_ajax_ftg_delete_stream', 'ftg_delete_stream_data' );

function ftg_reset_stream_cache() {
	global $wpdb;
	$ftg_stream_id = ( isset($_POST['ftg_stream_id']) ? $_POST['ftg_stream_id'] : '' );
	$ftg_stream_type = ( isset($_POST['ftg_stream_type']) ? $_POST['ftg_stream_type'] : '' );

	if ( $ftg_stream_type == 'instagram' ) {
		$cache_result = ftg_generate_media_cache( $ftg_stream_id );
	} else if ( $ftg_stream_type == 'youtube' ) {
		$cache_result = ftg_generate_youtube_cache( $ftg_stream_id );
	} else if ( $ftg_stream_type == 'twitter' ) {
		$cache_result = ftg_generate_twitter_cache( $ftg_stream_id );
	}

	if ( $cache_result === true ) {
		echo '{"save_response": "'.$ftg_stream_id.'"}';
	} else {
		echo '{"save_response": "'.$cache_result.'"}';
	}

	die(0);
}
add_action( 'wp_ajax_ftg_reset_cache', 'ftg_reset_stream_cache' );

function ftg_get_video_comments() {
	$main_settings = ftg_get_settings();
	$ftg_video_id = ( isset($_POST['ftg_video_id']) ? $_POST['ftg_video_id'] : '' );
	$video_comments = array();

	$get_video_comments = ftg_get_url_content('https://www.googleapis.com/youtube/v3/commentThreads?part=snippet&key='.$main_settings['youtube_key'].'&videoId='.$ftg_video_id);
	if ( $get_video_comments === false ) {
		echo 'failed';
	} else {
		$youtube_video_comments = json_decode($get_video_comments, true);
		foreach ($youtube_video_comments['items'] as $video_value) {
			$video_comments[] = array('snippet' => $video_value['snippet'], 'date_formatted' => human_time_diff(strtotime($video_value['snippet']['topLevelComment']['snippet']['publishedAt']),current_time('timestamp')));
		}
		echo json_encode($video_comments);
	}

	die(0);
}
add_action( 'wp_ajax_ftg_get_comment_data', 'ftg_get_video_comments' );
add_action( 'wp_ajax_nopriv_ftg_get_comment_data', 'ftg_get_video_comments' );

function ftgp_check_instagram_feeds( $stream_settings, $main_settings ) {
	$all_insta_media = array();

	if ( isset($stream_settings['source_list']) && $stream_settings['source_list'] != '' ) {
		$media_sources = explode('||', $stream_settings['source_list']);
		foreach ($media_sources as $media_key => $media_value) {
			if ( ftgp_startsWith($media_value, '@') ) {
				// If source is username
				$test_inst_user = ftg_get_url_content('https://api.instagram.com/v1/users/search?q='.$media_value.'&access_token='.$main_settings['instagram_auth']);
				if ( $test_inst_user === false ) {
					continue;
				}
				$insta_username = json_decode($test_inst_user, true);
				$insta_user_id = $insta_username['data']['0']['id'];
				$insta_user_media = ftg_get_url_content('https://api.instagram.com/v1/users/'.$insta_user_id.'/media/recent/?access_token='.$main_settings['instagram_auth']);
				if ( $insta_user_media === false ) {
					continue;
				}
				$instagram_user_data = json_decode($insta_user_media);

				// Add new images
				if ( !empty($instagram_user_data->data) ) {
					foreach ($instagram_user_data->data as $inner_media_value) {
						$all_insta_media[$inner_media_value->id] = $inner_media_value;
					}
				}
			} else if ( ftgp_startsWith($media_value, '#') ) {
				// If source is hashtag
				$test_inst_hash = ftg_get_url_content('https://api.instagram.com/v1/tags/'.str_replace('#', '', $media_value).'/media/recent?access_token='.$main_settings['instagram_auth']);
				if ( $test_inst_hash === false ) {
					continue;
				}
				$instagram_hash_data = json_decode($test_inst_hash);
				
				// Add new images
				if ( !empty($instagram_hash_data->data) ) {
					foreach ($instagram_hash_data->data as $inner_media_value) {
						$all_insta_media[$inner_media_value->id] = $inner_media_value;
					}
				}
			} else {
				// If source is url
				$insta_image_url = explode('/', $media_value);
				$test_inst_url = ftg_get_url_content('https://api.instagram.com/v1/media/shortcode/'.$insta_image_url['4'].'?access_token='.$main_settings['instagram_auth']);
				if ( $test_inst_url === false ) {
					continue;
				}
				$instagram_url_data = json_decode($test_inst_url);

				// Add new images
				if ( !empty($instagram_url_data->data) ) {
					$all_insta_media[$instagram_url_data->id] = $instagram_url_data->data;
				}
			}
		}
	}

	if ( function_exists('ftgp_sort_images') ) {
		return ftgp_sort_images($all_insta_media, $stream_settings);
	} else {
		return ftg_sort_images($all_insta_media, $stream_settings);
	}
}

function ftg_sort_images( $all_media, $stream_settings ) {
	$new_media_array = array();

	foreach ($all_media as $media_key => $media_value) {
		// if ( ftg_check_filters( $media_value, $stream_settings ) ) {
			$new_media_array[$media_value->created_time] = $media_value;
		// }
	}

	if ( $stream_settings['instagram_limit'] > 0 ) {
		$image_limit = $stream_settings['instagram_limit'];
	} else if ( $stream_settings['instagram_limit'] == 0 ) {
		$image_limit = 9999;
	}

	$new_media_array = array_slice($new_media_array, 0, $image_limit);

	// krsort($new_media_array);

	return $new_media_array;
}

function ftgp_startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function ftg_get_url_content( $url ) {
	try {
		$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);

		if ( empty($buffer) ){
			return false;
		} else {
			return $buffer;
		}
	} catch(Exception $e) {
		return $e->getMessage();
	}
}