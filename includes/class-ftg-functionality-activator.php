<?php

/**
 * Fired during plugin activation
 *
 * @link       https://cohhe.com
 * @since      1.0.0
 *
 * @package    ftg_func
 * @subpackage ftg_func/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    ftg_func
 * @subpackage ftg_func/includes
 * @author     Cohhe <support@cohhe.com>
 */
class ftg_func_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function ftg_activate() {
		global $wpdb;

		$stream_table  = $wpdb->prefix . 'feed_the_grid_streams';
		$charset_collate = $wpdb->get_charset_collate();

		$main_sql = "CREATE TABLE IF NOT EXISTS $stream_table (
				  `ID` int(9) NOT NULL AUTO_INCREMENT,
				  `stream_name` text NOT NULL,
				  `stream_type` text NOT NULL,
				  `stream_settings` text NOT NULL,
				  PRIMARY KEY (`ID`)
				) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $main_sql );

		$cache_sql = "SHOW COLUMNS FROM `".$stream_table."` LIKE 'stream_cache'";
		$cache_results = $wpdb->get_var($cache_sql);
		if ( $cache_results != 'stream_cache' ) {
			$cache_add_sql = "ALTER TABLE ".$stream_table." ADD stream_cache text NOT NULL";
			$wpdb->query($cache_add_sql);

			$cache_time_add_sql = "ALTER TABLE ".$stream_table." ADD cache_timestamp VARCHAR(20) NOT NULL";
			$wpdb->query($cache_time_add_sql);

		}

	}

}