<?php
/**
 *
 * @package WordPress
 * @subpackage PartyOnStillwater
 */

get_header(); ?>

<div id="primary">

		<!-- start The Loop -->
			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<h1 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				<div class="time">Posted on <?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></div>

				<div class="entry">
					<?php the_content('Read the rest of this entry &raquo;'); ?>
				</div>
            	<div style="clear: both;"></div>

				<p class="postmetadata"><?php the_tags('Tags: ', ', ', ''); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?> <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> </p>
			</div>
			<?php endwhile; ?>

<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div class="navigation">
					<div class="alignLeft"><?php next_posts_link( __( '&larr; Older posts') ); ?></div>
					<div class="alignRight"><?php previous_posts_link( __( 'Newer posts &rarr;') ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>

			<?php else : ?>

			<h2 class="singleTitle">Not Found</h2>
			<p class="entry">Sorry, but you are looking for something that isn't here.</p>
			<?php get_search_form(); ?>
			<?php endif; ?>
		<!-- end The Loop -->

</div><!-- primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>