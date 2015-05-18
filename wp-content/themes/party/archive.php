<?php
/**
 *
 * @package WordPress
 * @subpackage PartyOnStillwater
 */

get_header(); ?>

<div id="primary">
		<?php if (have_posts()) : ?>

 		<!-- start The Loop -->
		<?php while (have_posts()) : the_post(); ?>
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<h1 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

				<div class="entry">
					<?php the_post_thumbnail('thumbnail', array('class' => 'alignleft')); ?>
					<?php the_content('Read the rest of this entry &raquo;'); ?>
				</div>
            	<div style="clear: both;"></div>

				<!--<p class="postmetadata"><?php the_tags('Tags: ', ', ', ''); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?> <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> </p>-->
			</div>
			<?php endwhile; ?>
				<div class="navigation">
					<div class="alignLeft"><?php next_posts_link( __( '&larr; More Items') ); ?></div>
					<div class="alignRight"><?php previous_posts_link( __( 'More Items &rarr;') ); ?></div>
				</div><!-- #nav-below -->

		<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2 class='center'>Sorry, but there aren't any posts in the %s category yet.</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2>Sorry, but there aren't any posts with this date.</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2 class='center'>Sorry, but there aren't any posts by %s yet.</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='center'>No posts found.</h2>");
		}
		get_search_form();

		endif; ?>
		<!-- end The Loop -->

</div><!-- primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>