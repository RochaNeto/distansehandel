<?php
function mnky_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Blog Sidebar', 'mnky-admin' ),
		'id' => 'blog-widget-area',
		'description' => __( 'Blog widget area', 'mnky-admin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3><div class="sidebar-line"><span></span></div>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Default Page Sidebar', 'mnky-admin' ),
		'id' => 'primary-widget-area',
		'description' => __( 'Default page widget area', 'mnky-admin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3><div class="sidebar-line"><span></span></div>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Search Page Sidebar', 'mnky-admin' ),
		'id' => 'search-widget-area',
		'description' => __( 'Search page widget area', 'mnky-admin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3><div class="sidebar-line"><span></span></div>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Header Sidebar', 'mnky-admin' ),
		'id' => 'top-widget-area',
		'description' => __( 'Top widget area in header', 'mnky-admin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<li class="widget-title">',
		'after_title' => '</li>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 1', 'mnky-admin' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'mnky-admin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<div class="widget-title"><span>',
		'after_title' => '</span></div><div class="sidebar-line"><span></span></div>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 2', 'mnky-admin' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'mnky-admin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<div class="widget-title"><span>',
		'after_title' => '</span></div><div class="sidebar-line"><span></span></div>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 3', 'mnky-admin' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'mnky-admin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<div class="widget-title"><span>',
		'after_title' => '</span></div><div class="sidebar-line"><span></span></div>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 4', 'mnky-admin' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'mnky-admin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<div class="widget-title"><span>',
		'after_title' => '</span></div><div class="sidebar-line"><span></span></div>',
	) );
}

add_action( 'widgets_init', 'mnky_widgets_init' );
?>