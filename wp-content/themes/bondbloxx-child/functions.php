<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_locale_css' ) ):
    function chld_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_separate', trailingslashit( get_stylesheet_directory_uri() ) . 'ctc-style.css', array( 'hello-elementor','hello-elementor','hello-elementor-theme-style','hello-elementor-newsroom-style' ) );
        wp_enqueue_style( 'hello-elementor-daterangepicker', get_theme_file_uri() . '/assets/css/daterangepicker.css ', [], HELLO_ELEMENTOR_VERSION );
        wp_enqueue_style( 'hello-elementor-newsroom-style', get_theme_file_uri() . '/assets/css/newsroom-style.css', [], HELLO_ELEMENTOR_VERSION );

        //JS
        wp_enqueue_script( 'hello-elementor-moments', get_theme_file_uri() . '/assets/js/moment.min.js ', [], HELLO_ELEMENTOR_VERSION, true );
        wp_enqueue_script( 'hello-elementor-daterangepickerJs', get_theme_file_uri() . '/assets/js/daterangepicker.min.js ', [], HELLO_ELEMENTOR_VERSION, true );
        wp_enqueue_script( 'customJS', get_theme_file_uri() . '/assets/js/custom.js ', [], HELLO_ELEMENTOR_VERSION, true );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 20 );

// END ENQUEUE PARENT ACTION

/*
*   Added Ajax file
*/
add_action('wp_head', 'myplugin_ajaxurl');
function myplugin_ajaxurl() {
    echo '<script type="text/javascript">
       var ajax_url = "' . admin_url('admin-ajax.php') . '";
     </script>';
}
/**
 * Get custom post by Category
 */
add_action( 'wp_ajax_filterPostByCancelButton', 'filterPostByCancelButton' );
add_action( 'wp_ajax_nopriv_filterPostByCancelButton', 'filterPostByCancelButton');
function filterPostByCancelButton(){
    if($_POST['text'] == 'cancel'){
        $args = array( 'post_type' => 'newsroom', 'posts_per_page' => 20, 'order' => 'DESC' );
    }
    $query = new WP_Query( $args );
    $data = $query->posts;
    $response = '';
    $pageId = ['1898','1901', '1904', '1905', '2264'];
    if( $query->have_posts() ){
        while( $query->have_posts() ){
            $query->the_post();
            $cat = "";
            if(in_array(get_the_ID(), $pageId)){
               $cat = '<div class="press-release"><span>Press Release</span></div>';
            }
            //while ( $query->have_posts() ) : $query->the_post();
            $response .= '<li class="newsroon-post"><div class="flex-item">
                            <a href="'.get_post_meta(get_the_ID(), "url", TRUE).'">
                            <div class="img-blk">
                               <img class="post-img" src="'.get_the_post_thumbnail_url(get_the_ID()).'" alt="flex-img-01">
                            </div>
                            <div class="content-block">
                               <div class="date-block">
                                  <span class="category-name">'.get_the_excerpt().'</span>
                                  <span class="post-date">'.get_the_date('M j, Y').'</span>
                               </div>
                               <h3>'.get_the_title().'</h3>
                               <a class="more-link" href="'.get_post_meta(get_the_ID(), "url", TRUE).'">More <span></span></a>
                            </div>
                            </a>
                         </div>'.$cat.'</li>';  
            //endwhile;
        }
    }else{
        $response .= '<div class="no-records"><span>No News Item(s) Found.</span></div>';
    }
    echo $response;
    die;
}


/**
 * Get custom post by Date
 */
add_action( 'wp_ajax_filterCustomPostByDate', 'filterCustomPostByDate' );
add_action( 'wp_ajax_nopriv_filterCustomPostByDate', 'filterCustomPostByDate');
function filterCustomPostByDate(){
    $categories = null;
    $date       = null;
    if(!empty($_POST['data'])){
        $categories =  array(
            'taxonomy' => 'categories',
            'field' => 'slug',
            'terms' => $_POST['data'],
        );
    }
    if($_POST['startDate'] != ''){
        $startDate = date('Y-m-d', strtotime($_POST['startDate']));
        $endDate = date('Y-m-d', strtotime($_POST['endDate']));
        $date = array(
            'column' => 'post_date',
            'after' => $startDate,
            'before' => $endDate,
            'inclusive' => true,
        );
    }
    

    if(!empty($_POST['data']) && (current($_POST['data']) == 'All news' ) ){
        $args = array( 'post_type' => 'newsroom', 'posts_per_page' => 20, 'order' => 'DESC' );

    } else {
        $args = array(
          'post_type' => 'newsroom', 
          'post_status'=> 'publish',
          'order' => 'DESC',
            'tax_query' => array(
                $categories,
            ),
            'date_query' => array(
                $date,
            ),
        );
    }
    $query = new WP_Query( $args );
    //echo "<pre>"; print_r($query); exit;
    $data = $query->posts;
    $response = '';
    $pageId = ['1898','1901', '1904', '1905', '2264'];

    if( $query->have_posts() ){
        while( $query->have_posts() ){
            $query->the_post();
            $cat = "";
            if(in_array(get_the_ID(), $pageId)){
               $cat = '<div class="press-release"><span>Press Release</span></div>';
            }       
            $response .= '<li class="newsroon-post"><div class="flex-item">
                            <a href="'.get_post_meta(get_the_ID(), "url", TRUE).'">
                            <div class="img-blk">
                               <img class="post-img" src="'.get_the_post_thumbnail_url(get_the_ID()).'" alt="flex-img-01">
                            </div>
                            <div class="content-block">
                               <div class="date-block">
                                  <span class="category-name">'.get_the_excerpt().'</span>
                                  <span class="post-date">'.get_the_date('M j, Y').'</span>
                               </div>
                               <h3>'.get_the_title().'</h3>
                               <a class="more-link" href="'.get_post_meta(get_the_ID(), "url", TRUE).'">More <span></span></a>
                            </div>
                            </a>
                         </div>'.$cat.'</li>';
        }
    }else{
        $response .= '<div class="no-records"><span>No News Item(s) Found.</span></div>';
    }
    
    echo $response;
    die;

}



//
/*
* Update Reset Button label name.
*/

add_filter( 'document_library_pro_reset_button', function( $button) {
    return "Show All";
} );

/*
* Update Reset Button label name.
*/
add_filter( 'document_library_pro_search_filter_heading_categories', function( $heading, $table_args ) { 
   return 'Choose a category';
}, 10, 2 );

/*
* Update Filter label name.
*/
add_filter( 'document_library_pro_search_filter_label', function( $label ) {
    return '';
} );

/*
* Change the Link on Categories Name.
*/
add_filter( 'document_library_pro_data_title', function( $title, $post ) {
    $fileId = get_post_meta( $post->ID, '_dlp_attached_file_id', true );
    $fileUrl = wp_get_attachment_url( $fileId );
    if($fileUrl != ""){
        $fileLink = wp_get_attachment_url( $fileId );
    }else{
        $fileLink = get_post_meta( $post->ID, '_dlp_direct_link_url', true );
    }
    $title = '<a href="'.$fileLink.'" class="cat_title" target="_blank">'.$title.'</a>';
    return $title;
}, 10, 2 );

/*
* Update File type with image icon.
*/
add_filter( 'document_library_pro_data_file_type', function( $terms ) {

    if($terms == 'pdf'){
        $terms = '<img src="'.site_url('/wp-content/uploads/2023/01/pdf-icon.svg').'" class="file_pdf">';
    }else if($terms == 'www'){
        $terms = '<img src="'.site_url('/wp-content/uploads/2023/01/Web.svg').'" class="file_www">';
    }else if($terms == 'xls' || $terms == 'xlsx'){
        $terms = '<img src="'.site_url('/wp-content/uploads/2023/01/xls-icon.svg').'" class="file_xls">';
    }else if($terms == 'doc'){
        $terms = '<img src="'.site_url('/wp-content/uploads/2023/01/doc-icon.svg').'" class="file_doc">';
    }else{
        $terms = '<img src="'.site_url('/wp-content/uploads/2023/01/pdf-icon.svg').'" class="file_type">';
    }
    return $terms;
} );

/*
* Update Categories and Sub Category Class and action URL.
*/


add_filter( 'document_library_pro_data_categories', function( $terms) {
    global $post;
    $fileId = get_post_meta( $post->ID, '_dlp_attached_file_id', true );
    $fileUrl = wp_get_attachment_url( $fileId );
    if ( !empty( $terms ) ) {
        $terms = $terms;
    } else {
        $terms = Util::get_the_term_names( $post, $terms );
    }
    return $terms;
} );

/*
* Add Custom icon 
*/
add_filter( 'document_library_pro_icon_svg', function( $icons ){
    // Change any filter you want based on the array in the file 
    $icons['download'] = '<img src="'.site_url('/wp-content/uploads/2023/01/download-icon.svg').'">'; 
    $icons['preview'] = '<img src="'.site_url('/wp-content/uploads/2023/01/eye-icon.svg').'">'; 

    return $icons; 
}, 11, 1); 

/*
* Add Custom PDF link to title
*/
add_filter( 'document_library_pro_data_title_before_link', function( $terms) {
    global $post;
    $fileId = get_post_meta( $post->ID, '_dlp_attached_file_id', true );
    $fileUrl = wp_get_attachment_url( $fileId );
    if($fileUrl != ""){
        $fileLink = wp_get_attachment_url( $fileId );
    }else{
        $fileLink = get_post_meta( $post->ID, '_dlp_direct_link_url', true );
    }
    return $terms = '<a href="'.$fileLink.'" target="_blank">'.$terms.'</a>';

});

add_filter( 'rest_authentication_errors', 'wp_snippet_disable_rest_api' );
function wp_snippet_disable_rest_api( $access ) {
  return new WP_Error( 'rest_disabled', __('The WordPress REST API has been disabled.'), array( 'status' => rest_authorization_required_code()));
}