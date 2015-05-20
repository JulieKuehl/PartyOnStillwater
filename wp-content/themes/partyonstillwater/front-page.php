<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package PartyOnStillwater
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<div class="slider"><?php if ( function_exists( 'meteor_slideshow' ) ) { meteor_slideshow(); } ?></div><!-- end .slider -->

		<div class="main-content">
			<div class="main-content-col-1">
				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );
						?>

					<?php endwhile; ?>

					<?php the_posts_navigation(); ?>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

				<?php endif; ?>
			</div><!-- end .main-content-col-1 -->
			<div class="main-content-col-2">

			</div><!-- end .main-content-col-2 -->
		</div><!-- end .main-content -->

		<div class="offerings">
			<div class="offering-first">

			</div><!-- end .offering-first -->
			<div class="offering-second">

			</div><!-- end .offering-second -->
			<div class="offering-third">

			</div><!-- end .offering-third -->

		</div><!-- end .offerings -->

		<div class="social-media-feeds">
			<div class="facebook-feed">

			</div><!-- end .facebook-feed -->
			<div class="latest-posts">

			</div><!-- end .latest-posts -->

		</div><!-- end .social-media-feeds -->

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
