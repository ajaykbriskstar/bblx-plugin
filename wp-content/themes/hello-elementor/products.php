<?php 
    /*
     * Template Name: BBLX Products
     */

    get_header();
    ?>
<?php

$cats = get_categories();

    foreach ($cats as $cat) {
        $args = array(
        'post_type' => 'tickers',
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $cat->cat_ID,
                ),
            ),
        );
        $query = new WP_Query($args);

        if ( $query->have_posts() ): ?>
            <p><?php echo $cat->cat_name ; ?></p> <?

        while($query -> have_posts()) : $query -> the_post(); ?>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p><?php //the_content(); ?></p> <?php
            endwhile;
        endif; 

        // Added this now 
        wp_reset_query() ; 
    }
   



?>


    <?php  get_footer(); ?>