<?php
/*
 * @package WordPress
 * @subpackage PartyOnStillwater
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php
		wp_title( '|', true, 'right' ); bloginfo( 'name' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";
	?></title>

	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_directory' ); ?>/reset.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
</head>
<body>
<div id="swoop">
<div id="wrapper" class="centerFloat">
	<div id="header" class="centerFloat">
		<a href="<?php bloginfo( 'url' ); ?>"><div id="logo"></div></a>
	</div>
	<div id="nav" class="centerFloat">
		<?php wp_nav_menu( array( 'theme_location' => 'nav' ) ); ?>
		<a href="https://www.facebook.com/partyonstillwater" target="_blank"><div id="facebook"></div></a>
	</div><!-- nav -->
<div id="content" class="centerFloat">