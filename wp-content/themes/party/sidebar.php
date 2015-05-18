<?php
/**
 *
 * @package WordPress
 * @subpackage PartyOnStillwater
 */
?>
<div id="secondary" class="widget-area">

<ul>
	<?php 	/* Widgetized sidebar, if you have the plugin installed. */
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
 	<?php endif; ?>
</ul>


</div><!-- secondary -->