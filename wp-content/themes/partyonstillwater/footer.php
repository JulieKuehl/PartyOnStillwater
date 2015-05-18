<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package PartyOnStillwater
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
<!--			<a href="--><?php //echo esc_url( __( 'http://wordpress.org/', 'partyonstillwater' ) ); ?><!--">--><?php //printf( esc_html__( 'Proudly powered by %s', 'partyonstillwater' ), 'WordPress' ); ?><!--</a>-->
<!--			<span class="sep"> | </span>-->
<!--			--><?php //printf( esc_html__( 'Theme: %1$s by %2$s.', 'partyonstillwater' ), 'partyonstillwater', '<a href="http://juliekuehl.com" rel="designer">Julie Kuehl</a>' ); ?>
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer') ) : ?>
			<?php endif; ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
