<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cohhe.com
 * @since      1.0.0
 *
 * @package    ftg_func
 * @subpackage ftg_func/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ftg_func
 * @subpackage ftg_func/admin
 * @author     Cohhe <support@cohhe.com>
 */
class ftg_func_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $ftg_func    The ID of this plugin.
	 */
	private $ftg_func;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $ftg_func       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $ftg_func, $version ) {

		$this->ftg_func = $ftg_func;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in ftg_func_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The ftg_func_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( $hook != 'feed-the-grid_page_feed-the-grid-settings' && $hook != 'toplevel_page_feed-the-grid' ) {
			return;
		}

		wp_enqueue_style( $this->ftg_func, plugin_dir_url( __FILE__ ) . 'css/ftg-functionality-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'ftg-grid', plugin_dir_url( __FILE__ ) . 'css/grid.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'ftg-preview-css', plugin_dir_url( __FILE__ ) . 'css/ftg-preview-css.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in ftg_func_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The ftg_func_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( $hook != 'feed-the-grid_page_feed-the-grid-settings' && $hook != 'toplevel_page_feed-the-grid' ) {
			return;
		}

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'classie', plugin_dir_url( __FILE__ ) . 'js/classie.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'ftg-modernizr', plugin_dir_url( __FILE__ ) . 'js/modernizr.custom.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->ftg_func, plugin_dir_url( __FILE__ ) . 'js/ftg-functionality-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'ftg-animation', plugin_dir_url( __FILE__ ) . 'js/ftg-animation.js', array( 'jquery' ), $this->version, true );

	}

}
