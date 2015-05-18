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
					<?php the_content('Read the rest of this entry &raquo;'); ?>
				</div>
			</div>
					
		<?php endwhile; else: ?>
		<?php endif; ?>
		<!-- end The Loop -->

</div><!-- primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>