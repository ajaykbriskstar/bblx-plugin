<?php
/*
* Template Name: Newsroom Page
*/

get_header();
?>
<?php
$upload = wp_upload_dir();
$uploadUrl = $upload['baseurl'];

?>
<div class="tab-teaser">
   <div class="container">
      <h1>Newsroom</h1>
      <div class="tab-menu">
         <div class="tab-list">
            <ul>
               <li><a href="#" class="custom-category active all_news" data-rel="tab-1">All news</a></li>
               <?php 
                  $categories = get_terms('categories', array('orderby' => 'title', 'order' => 'ASC'));
                  foreach($categories as $key => $val){ ?>
                     <li><a href="#" class="custom-category newsroomCategory" data-rel="<?php echo $val->name; ?>"><?php echo $val->name; ?></a></li>
               <?php  } ?>
            </ul>
         </div>
         <div class="date-range-block">
            <input name='range' id='cal' value="" class="date_range" placeholder="Date Range">

            <!-- <ul id='ranges'></ul> -->
         </div>
      </div>
   </div>
   <div class="tab-main-box">
      <div class="tab-box" id="tab-1" style="display:block;">
         <section class="newsroom-section">
            <div class="container">
               <div class="list-bg">
                  <div class="ajax-loader" >
                    <img src="<?php echo $uploadUrl.'/2023/01/bblx_white_loder.svg'; ?>" height='100' width='100' />
                  </div>
                  <ul class="flex-list">
                     <?php
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $pageId = ['1898','1901', '1904', '1905', '2264'];
                        $args = array( 'post_type' => 'newsroom', 'posts_per_page' => 12,'paged' => $paged, 'order' => 'DESC' );
                        $query = new WP_Query( $args );
                           if ( $query->have_posts() ) : ?>
                           <?php while ( $query->have_posts() ) : $query->the_post(); 
                              
                              ?>
                              <li class="newsroon-post">
                                 <div class="flex-item">
                                    <a href="<?php echo get_post_meta(get_the_ID(), 'url', TRUE); ?>" target="_blank">
                                    <div class="img-blk">
                                       <img class="post-img" src="<?php echo get_the_post_thumbnail_url() ?>" alt="flex-img-01">
                                    </div>
                                    <div class="content-block">
                                       <div class="date-block">
                                          <span class="category-name"><?php echo the_content(); ?></span>
                                          <span class="post-date"><?php echo get_the_date('M j, Y'); ?></span>
                                       </div>
                                       <h3><?php echo get_the_title(); ?></h3>
                                       <a class="more-link" href="<?php echo get_post_meta(get_the_ID(), 'url', TRUE); ?>" target="_blank">More <span></span></a>
                                    </div>
                                    </a>
                                 </div>
                                 
                                 <?php
                                    if(in_array(get_the_ID(), $pageId)){
                                       echo '<div class="press-release"><span>Press Release</span></div>';
                                    }
                                 ?>
                              </li>

                        <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                        <div class="pagination">
                           <div class="page__numbers">
                              <?php 
                              $big = 999999999; // need an unlikely integer
                              paginate_links([
                                 'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
                                 'format' => '?paged=%#%',
                                 'current' => max( 1, get_query_var('paged') ),
                                 'total' => $query->max_num_pages,
                                 'prev_text' => __('<< Previous'),
                                 'next_text' => __('Next >>'),
                               ]);
                              ?>
                           </div>
                        </div>
                     <?php  endif; ?>
                  
               </div>
            </div>
         </section>
      </div>
   </div>
</div>


<script type="text/javascript">
 jQuery( document ).ready(function() {
   jQuery('.tab-menu li a').on('click', function(){
      jQuery(".flex-list").addClass('newsroom-block');
      var target = jQuery(this).attr('data-rel');
      jQuery('.tab-menu li a').removeClass('active');
        jQuery(this).addClass('active');
        jQuery("#"+target).fadeIn('slow').siblings(".tab-box").hide();
        return false;
   });
   jQuery('.ajax-loader').hide();
   jQuery('li .custom-category').on('click', function(){
      
      var strName = jQuery(this).text();
      var catName = jQuery(this).attr("data-cat");

      jQuery('.date_range').attr("data-apply", 'save');
      jQuery('#cal').removeAttr("data-cancel");
      const cat_name = [];
      if(strName != 'All news'){
         //jQuery('.date-range-block').addClass('date_active');
         jQuery(this).attr("data-cat", strName);

         jQuery("li .custom-category").each(function () {
            var dataCat = jQuery(this).attr("data-cat");
            jQuery('#cal').removeAttr("data-apply");
            if(dataCat != undefined){
               jQuery(this).addClass('active');
               cat_name.push(dataCat);
            }

         });
      }else{
         cat_name.push(strName);
         jQuery("#cal").val('');
         jQuery('.date-range-block').removeClass('date_active');
         jQuery("li .custom-category").each(function () {
            jQuery(this).removeAttr("data-cat");
         });
      }
      if(catName != undefined){
         cat_name.splice(jQuery.inArray(catName, cat_name), 1);
         jQuery(this).removeClass('active');
         jQuery(this).removeAttr("data-cat");
      }
      
     
      var startDate = jQuery('#cal').attr("data-startdate");
      var endDate = jQuery('#cal').attr("data-enddate");

      jQuery('li .custom-category').find('.flex-list').addClass('ajax-active');
      newsroomFilterByDateAndCategory(startDate, endDate, cat_name);
      /*if(strName != ""){
         jQuery("#cal").val('');
      }*/
      /*jQuery.ajax({
         method: "POST",
         beforeSend: function(){
            jQuery('.ajax-loader').show();
         },
         url: ajax_url,
         data: {
               action: 'filterPostByCategory',
               text: cat_name,
           },
           success:function(response){
            jQuery('li.newsroon-post').remove();
           jQuery('ul.flex-list').html(response);
           return false;
         },
         complete: function(){
           jQuery('.ajax-loader').hide();
         },
      });*/
      
   });



});

var dates = [];
jQuery(document).ready(function() {
   jQuery("input[name='range']").daterangepicker({
      autoUpdateInput: false,
       locale: {
           cancelLabel: 'Clear'
       },
       opens: 'right',
       closeText: 'Clear',
   });
   jQuery("#cal").val('');
   // jQuery("#cal").addClass(active);

  
  jQuery("#cal").on('apply.daterangepicker', function(e, picker) {
      jQuery('.date-range-block').addClass('date_active');
      const cat_name = [];
      jQuery("li .custom-category").each(function () {
         dataCat = jQuery(this).attr("data-cat");
         if(dataCat != undefined){
            cat_name.push(dataCat);
         }
      });

      jQuery(".flex-list").addClass('newsroom-block');
      jQuery(this).val(picker.startDate.format('MMM D, YYYY') + ' - ' + picker.endDate.format('MMM D, YYYY'));
      
      e.preventDefault();
      const obj = {
         "key": dates.length + 1,
         "start": picker.startDate.format('MMM D, YYYY'),
         "end": picker.endDate.format('MMM D, YYYY'),
      }
      dates.push(obj);
      showDates();
      var startDate = obj.start;
      var endDate = obj.end;
      jQuery('.date_range').attr("data-startdate", startDate);
      jQuery('.date_range').attr("data-enddate", endDate);
      newsroomFilterByDateAndCategory(startDate, endDate, cat_name);
    
  });
   jQuery('.date_range').on('cancel.daterangepicker', function(ev, picker) {
      jQuery('.date-range-block').removeClass('date_active');
      jQuery('#cal').removeAttr("data-startdate");
      jQuery('#cal').removeAttr("data-enddate");
      jQuery('#cal').removeAttr("data-apply");
      jQuery("li .all_news").addClass('active');
      jQuery(this).attr("data-cancel", 'cancel');
      jQuery(this).attr("data-cancel", 'cancel');
      var startDate = '';
      var endDate = '';
      var cancel = jQuery(this).attr("data-cancel");
      jQuery("li .newsroomCategory").each(function () {
         jQuery(this).removeClass('active');
      });
      
      jQuery('input[name="range"]').data('daterangepicker').setStartDate(moment());
      jQuery('input[name="range"]').data('daterangepicker').setEndDate(moment());
      jQuery(this).val('');
      dateCancelButton(cancel);
      //newsroomFilterByDateAndCategory(startDate, endDate, cancel);
   });
   jQuery(".remove").on('click', function() {
      jQuery('.date_range').val('');
      removeDate(jQuery(this).attr('key'));
   });
});

function newsroomFilterByDateAndCategory(startDate, endDate, cat_name){
   jQuery.ajax({
      method: "POST",
      url: ajax_url,
      beforeSend: function(){
         jQuery('.ajax-loader').show();
      },
      data: {
            action: 'filterCustomPostByDate',
            startDate: startDate,
            endDate: endDate,
            data: cat_name,
        },
        success:function(response){
         jQuery('li.newsroon-post').hide();
         jQuery('ul.flex-list').html(response);
        return false;
      },
      complete: function(){
        jQuery('.ajax-loader').hide();
      },
   });
}


function dateCancelButton(cancel){
   console.log(cancel);
   jQuery.ajax({
      method: "POST",
      url: ajax_url,
      beforeSend: function(){
         jQuery('.ajax-loader').show();
      },
      data: {
            action: 'filterPostByCancelButton',
            text: cancel,
        },
        success:function(response){
         jQuery('li.newsroon-post').hide();
         jQuery('ul.flex-list').html(response);
        return false;
      },
      complete: function(){
        jQuery('.ajax-loader').hide();
      },
   });
}



function showDates() {
  jQuery("#ranges").html("");
  jQuery.each(dates, function() {
    const el = "<li>" + this.start + "-" + this.end + "<button class='remove' onClick='removeDate(" + this.key + ")'>-</button></li>";
    jQuery("#ranges").append(el);
  })
}
function removeDate(i) {
  dates = dates.filter(function(o) {
    return o.key !== i;
  })
  showDates();
}


  </script>
<?php

get_footer();
?>