<?php
/**
 *
 * @package WordPress
 * @subpackage PartyOnStillwater
 */
?>
<div style="clear:both;"></div>
<div id="footer">
	<?php 	/* Widgetized sidebar, if you have the plugin installed. */
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer') ) : ?>
 	<?php endif; ?>
	<?php wp_footer(); ?>
</div><!-- footer -->
</div><!-- content -->
</div><!-- wrapper -->
</div><!-- swoop -->
</body>
</html>