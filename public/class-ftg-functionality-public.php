<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cohhe.com
 * @since      1.0.0
 *
 * @package    ftg_func
 * @subpackage ftg_func/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ftg_func
 * @subpackage ftg_func/public
 * @author     Cohhe <support@cohhe.com>
 */
class ftg_func_Public {

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
	 * @param      string    $ftg_func       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $ftg_func, $version ) {

		$this->ftg_func = $ftg_func;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( 'animate', plugin_dir_url( __FILE__ ) . 'css/animate.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->ftg_func, plugin_dir_url( __FILE__ ) . 'css/ftg-functionality-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( $this->ftg_func, plugin_dir_url( __FILE__ ) . 'js/ftg-functionality-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->ftg_func, 'ftg_main', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		));

	}

}
