<?php
/**
 * @package WordPress
 * @subpackage PartyOnStillwater
 */
?>
<?php
if ( function_exists('register_sidebar') ) 

   register_sidebar(array(
   'name' => 'sidebar',
   'before_widget' => '<div id="%1$s" class="widget %2$s">',
   'after_widget' => '</div>',
   'before_title' => '<h3>',
   'after_title' => '</h3>'
    ));

   register_sidebar(array(
   'name' => 'footer',
   'before_widget' => '<div id="%1$s" class="widget %2$s">',
   'after_widget' => '</div>',
   'before_title' => '<h3>',
   'after_title' => '</h3>'
    ));
    
   register_sidebar(array(
   'name' => 'latestHome',
   'before_widget' => '<div id="%1$s" class="widget %2$s">',
   'after_widget' => '</div>',
   'before_title' => '<h3>',
   'after_title' => '</h3>'
    ));
    
   register_sidebar(array(
   'name' => 'categories',
   'before_widget' => '<div id="%1$s" class="widget %2$s">',
   'after_widget' => '</div>',
   'before_title' => '<h3>',
   'after_title' => '</h3>'
    ));

    
register_nav_menu( 'nav', 'Primary Menu' );

add_theme_support( 'post-thumbnails' );

?>