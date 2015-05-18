<?php
/**
 *
 * @package WordPress
 * @subpackage PartyOnStillwater
 * Template Name: homePage
 */

get_header(); ?>

<div id="primaryHome" class="centerFloat">
<div id="slideshow">
				<?php if (function_exists('nivoslider4wp_show')) { nivoslider4wp_show(); } ?>
</div>
<div id="homeRight">
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
</div><!-- homeRight -->
<div style="clear:both;"></div>
<br>
<br>
<div id="bottomPics">
	<a href="gift-baskets/"><div id="baskets"></div></a>
	<a href="event-planning/"><div id="planning"></div></a>
	<a href="promotional-products/"><div id="products"></div></a>
</div>
<div id="facebookHome">
	<iframe style="border: none; overflow: hidden; width: 602px; height: 427px;" src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpartyonstillwater&amp;width=602&amp;height=427&amp;show_faces=false&amp;colorscheme=light&amp;stream=true&amp;border_color&amp;header=true" height="240" width="320" frameborder="0" scrolling="no"></iframe>
</div>
<div id="latestHome">
	<?php 	/* Widgetized sidebar, if you have the plugin installed. */
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('latestHome') ) : ?>
 	<?php endif; ?>
</div>
</div><!-- primaryHome -->

<?php get_footer(); ?>