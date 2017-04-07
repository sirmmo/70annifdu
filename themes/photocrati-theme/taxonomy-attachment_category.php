<?php
/*
Template Name: Page Without Title
*/
?>

<?php get_header(); ?>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js"></script>


<script>
    $( function()
    {
        $( 'img' ).imageLightbox();
    });
</script>
<div class="box">
	<?php $cc = get_query_var('attachment_category'); ?>
	<?php $cat = get_term_by('slug',$cc,'attachment_category'); ?>
	<h1><?php echo $cat->name; ?> </h1>
	<div><?php echo category_description(); ?></div>
</div>


<div class="container">
<?php 

$qq = new WP_Query( array(
  'post_type' => 'attachment',
  'tax_query' => array(
		array(
			'taxonomy' => 'attachment_category',
			'field'    => 'term_id',
			'terms'    => $cat->term_id,
		),
	),
  'post_mime_type' =>'image',
  'posts_per_page' => 100,
  'nopaging' => true,
  'post_status' => 'all',
  'post_parent' => null
) );
?>

 <div class="photocontainer" style="width:90%;margin-left:auto; margin-right:auto;">
<?php 
if ( $qq->have_posts() ) {
	while ( $qq->have_posts() ) {
		$qq->the_post(); 
		
		$url = get_the_guid();

		?>
     
    <a href="<?php echo $url; ?>" data-lightbox="collection"><img src="<?php echo $url; ?>" style="float:left;width:24%; margin:3px;"><?php do_shortcode("[social_share url='".$url."' title='70 Festa de l'Unità/]"); ?></a>
		
		<?php

	} // end while
} // end if
?>
</div>

            
        <?php //get_sidebar(); ?> <!-- Uncomment this (remove the //) to add the sidebar to pages -->
		
<?php get_footer(); ?>