<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       onestop-booking.briskstaruat.com
 * @since      1.0.0
 *
 * @package    Tickers
 * @subpackage Tickers/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tickers
 * @subpackage Tickers/public
 * @author     Briskstar <ajay@1stop.io>
 */
class Tickers_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
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
		 * defined in Tickers_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tickers_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name.'_custom', plugin_dir_url( __FILE__ ) . 'css/custom-style.css', array(), $this->version, 'all' );
		
		wp_enqueue_style( $this->plugin_name.'_public', plugin_dir_url( __FILE__ ).'css/tickers-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $post;

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

		if($post->post_type == 'tickers'){
		
			wp_enqueue_script( $this->plugin_name.'_data', plugin_dir_url( __FILE__ ) . 'js/data.js', array( 'data' ), $this->version, false );
			
			wp_enqueue_script( $this->plugin_name.'_highstock', plugin_dir_url( __FILE__ ) . 'js/highstock.js', array( 'jquery' ), $this->version, false );
			
			wp_enqueue_script( $this->plugin_name.'_custom', plugin_dir_url( __FILE__ ) . 'js/custom.js', array( 'jquery' ), $this->version, false );
			
			wp_enqueue_script( $this->plugin_name.'_public', plugin_dir_url( __FILE__ ) . 'js/tickers-public.js', array( 'jquery' ), $this->version, false );
		}
	}

	public function tickerTemplate( $template ){
		global $post;

		if ($post->post_type == 'tickers' && get_post_type() == 'tickers') {
	        $template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/common-template.php';
	    }
	    return $template;
	}

	public function set_tickers_link($post_link, $post,$leavename ){

		if ( 'tickers' != $post->post_type || 'publish' != $post->post_status ) {
		    return $post_link;
		}

		$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
	    return $post_link;
	}

	public function set_tickers_query($query ){

		global $wp,$post,$wp_query;


		
	   if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
	        return;
	    }

	    if ( ! empty( $query->query['name'] ) ) {
	        $query->set( 'post_type', array( 'post', 'tickers', 'page','e-landing-page' ) );
	    } elseif ( ! empty( $query->query['pagename'] ) && false === strpos( $query->query['pagename'], '/' ) ) {
	        $query->set( 'post_type', array( 'post', 'tickers', 'page','e-landing-page' ) );

	        $query->set( 'name', $query->query['pagename'] );
	    }

	}

	function getPerformanceValue(){
		$result = $_POST['quaterName'];
		$qNumber = $_POST['qNumber'];
		$performanceData = $_POST['performance'];
		
		if($qNumber == 1){
			if($result == "Q1"){
				$qData = "Q-4";
			}else if($result == "Q2"){
				$qData = "Q-3";
			}else if($result == "Q3"){
				$qData = "Q-2";
			}else if($result == "Q4"){
				$qData = "Q-1";
			}else{
				$qData = "T-1";
			}
		}
		if($qNumber == 2){
			if($result == "Q1"){
				$qData = "Q-1";
			}else if($result == "Q2"){
				$qData = "Q-4";
			}else if($result == "Q3"){
				$qData = "Q-3";
			}else if($result == "Q4"){
				$qData = "Q-2";
			}else{
				$qData = "T-1";
			}
		}
		if($qNumber == 3){
			if($result == "Q1"){
				$qData = "Q-2";
			}else if($result == "Q2"){
				$qData = "Q-1";
			}else if($result == "Q3"){
				$qData = "Q-4";
			}else if($result == "Q4"){
				$qData = "Q-3";
			}else{
				$qData = "T-1";
			}
		}
		if($qNumber == 4){
			if($result == "Q1"){
				$qData = "Q-3";
			}else if($result == "Q2"){
				$qData = "Q-2";
			}else if($result == "Q3"){
				$qData = "Q-1";
			}else if($result == "Q4"){
				$qData = "Q-4";
			}else{
				$qData = "T-1";
			}
		}
		
		$avgan = array(
			'nav_performance_annualized' => 'NAV',
			'close_performance_annualized' => 'Market Price',
			'index_performance_annualized' => 'Index',
		);
		$filterByNav = ['y1', 'y2', 'y3','inception'];
		$data = [];

		foreach ($performanceData as $key => $value) {
			if($value['asof_date_type'] == $qData){
				if(in_array($value['range'] , $filterByNav )){
					$data[$value['range']] = $value;
				}
			}
		}
		
		ob_start();
		foreach ($avgan as $key => $value) {
			$val = array('-','-','-','-');
			$index = 0;
			$position = array('y1','y2','y3','inception');
			foreach ($position as $pos) {
				if(isset($data[$pos][$key]) && $data[$pos][$key] != null){
					$val[$index] = number_format($data[$pos][$key]*100, 2).'%';
				}
				$index++;
			}
			if($value == 'NAV'){
				$popupInfo = '<a href="javascript:void(0)" id="nav-popup"><span class="fas fa-info-circle infoIcon"></span></a>';
			}
			if($value == 'Market Price'){
				$popupInfo = '<a href="javascript:void(0)" id="market-price"><span class="fas fa-info-circle infoIcon"></span></a>';
			}
			if($value == 'Index'){
				$popupInfo = '';
			}
			?>
			<tr class="annual-records">
				<td class=""><?php echo $value.$popupInfo ?></span></a></td>
				<td class="performanceRes"><?php echo $val[0]; ?></td>
				<td class="performanceRes"><?php echo $val[1]; ?></td>
				<td class="performanceRes"><?php echo $val[2]; ?></td>
				<td class="performanceRes"><?php echo $val[3]; ?></td>
			</tr>
			<?php
		}
		echo ob_get_clean();
		die;
	}

}
