<?php
/**
 *
 * @package WordPress
 * @subpackage PartyOnStillwater
  * Template Name: pageSidebar
 */

get_header(); ?>

<div id="primary" class="centerFloat">

		<!-- start The Loop -->
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<h1 class="singleTitle"><?php the_title(); ?></h1>
				<div class="greenDots"></div>
				<div class="entry">
				<?php  if ( has_post_thumbnail() ) { ?>
				<?php  $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');
  					 echo '<a href="' . $large_image_url[0] . '" class="thickbox" >';
  					 the_post_thumbnail('full', array('class' => 'post-pic'));
  					 echo '</a>'; ?>
					<?php the_content('Read the rest of this entry &raquo;'); ?>

				<?php } else { ?>
					<?php the_content('Read the rest of this entry &raquo;'); ?>
				<?php } ?>
				</div>
			</div>
					
		<?php endwhile; else: ?>
		<?php endif; ?>
		<!-- end The Loop -->

</div><!-- primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>