<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       onestop-booking.briskstaruat.com
 * @since      1.0.0
 *
 * @package    Tickers
 * @subpackage Tickers/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tickers
 * @subpackage Tickers/admin
 * @author     Briskstar <ajay@1stop.io>
 */
class Tickers_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tickers_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tickers_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tickers-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tickers_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tickers_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tickers-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function create_post_ticker(){
	    // CPT Options
	    $args    =    array(
	            'labels' => array(
	                'name' => __( 'BondBloxx Products' ),
	                'singular_name' => __( 'tickers' ),
	                'all_items'           => __( 'All Tickers', 'BBLXTicker' ),
			        'view_item'           => __( 'View Ticker', 'BBLXTicker' ),
			        'add_new_item'        => __( 'Add New Ticker', 'BBLXTicker' ),
			        'add_new'             => __( 'Add New', 'BBLXTicker' ),
			        'edit_item'           => __( 'Edit Ticker', 'BBLXTicker' ),
			        'update_item'         => __( 'Update Ticker', 'BBLXTicker' ),
			        'search_items'        => __( 'Search Ticker', 'BBLXTicker' ),
			        'not_found'           => __( 'Not Found', 'BBLXTicker' ),
			        'not_found_in_trash'  => __( 'Not found in Trash', 'BBLXTicker' ),
	            ),
	            'public' 		=> true,
	            'has_archive' 	=> true,
	            'rewrite' 		=> array('slug' => 'tickers', 'with_front' => false ,'pages'=>false,'feeds'=>false),
	            //'show_in_rest' 	=> true,
	            'supports'  	=> array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
	            'taxonomies'          => array( 'category' ),
	        );
		$args = apply_filters( 'tickers/post_type/args', $args );
		register_post_type( 'tickers', $args );
	}

	/**
	 * Function is used for change the texonomies categories label name.
	 */
	function renameCategory() {
	    global $wp_taxonomies;
	    $cat = $wp_taxonomies['category'];
	    $cat->label = 'Ticker Categories';
	    $cat->labels->singular_name = 'Ticker Categorie';
	    $cat->labels->name = $cat->label;
	    $cat->labels->menu_name = $cat->label;
	}

	/**
	 * Register submenu
	 *
	 * Function is used by admin_menu hook
	 */
	public function register_submenu_page() {
	    add_submenu_page('tickers', 'API Parameter', 'Settings', "manage_options", 'api-parameter', 'apiParameter', '');
	}


}
