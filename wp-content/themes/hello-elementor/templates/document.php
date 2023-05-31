<?php
/*
* Template Name: Document Page
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
      
   </div>
   <div class="tab-main-box">
      <div class="tab-box" id="tab-1" style="display:block;">
         <section class="newsroom-section">
            <div class="container">
               <?php echo do_shortcode('[doc_library folders="true"]');?>
            </div>
         </section>
      </div>
   </div>
</div>


<?php

get_footer();
?>