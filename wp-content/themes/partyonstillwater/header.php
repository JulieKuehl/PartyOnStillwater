<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package PartyOnStillwater
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="swoop"></div>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'partyonstillwater' ); ?></a>
<div id="wrapper">

	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Party-On-Stillwater-logo_tagline.png" alt="Party on Stillwater logo" />
<!--			<h1 class="site-title"><a href="--><?php //echo esc_url( home_url( '/' ) ); ?><!--" rel="home">--><?php //bloginfo( 'name' ); ?><!--</a></h1>-->
<!--			<h2 class="site-description">--><?php //bloginfo( 'description' ); ?><!--</h2>-->
		</div><!-- .site-branding -->
		<div class="social-media-links">
			<a href="http://facebook.com"></a><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook.png" alt="Facebook" /></a>
			<a href="http://twitter.com"></a><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/twitter.png" alt="Twitter" /></a>
			<a href="http://pinterest.com"></a><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/pinterest.png" alt="Pinterest" /></a>
		</div>
		<div class="weddings-site">
			<a href=""></a><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/PartyOnWeddings_Nav-tab.png" alt="Party on Weddings logo" /></a>
		</div>
	</header><!-- #masthead -->

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'partyonstillwater' ); ?></button>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
		</nav><!-- #site-navigation -->


	<div id="content" class="site-content">
