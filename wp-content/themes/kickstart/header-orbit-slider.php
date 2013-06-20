<!DOCTYPE html>
<!--[if IE 6]><html id="ie6" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 7]><html id="ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html id="ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<head>
	
	<meta charset="utf-8" />
	<?php $detect = new Mobile_Detect();
	if ((ot_get_option('responsive_layout') == 'responsive_mobile' && !$detect->isTablet()) || ot_get_option('responsive_layout') == 'responsive_all') { 
		echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
	} ?>

	<title><?php bloginfo('name'); ?>  <?php wp_title('-'); ?></title>

	<?php if (ot_get_option('favicon')){
		echo '<link rel="shortcut icon" href="'. ot_get_option('favicon') .'" />';
	} 

	if (ot_get_option('ipad_retina_icon')){
		echo '<link rel="apple-touch-icon" sizes="144x144" href="'. ot_get_option('ipad_retina_icon') .'" >';
	} 

	if (ot_get_option('iphone_retina_icon')){
		echo '<link rel="apple-touch-icon" sizes="114x114" href="'. ot_get_option('iphone_retina_icon') .'" >';
	}

	if (ot_get_option('ipad_icon')){
		echo '<link rel="apple-touch-icon" sizes="72x72" href="'. ot_get_option('ipad_icon') .'" >';
	} 

	if (ot_get_option('iphone_icon')){
		echo '<link rel="apple-touch-icon" href="'. ot_get_option('iphone_icon') .'" >';
	} ?>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
	
	<!--[if IE 7 ]>
	<link href="<?php echo MNKY_CSS ?>/ie7.css" media="screen" rel="stylesheet" type="text/css">
	<![endif]-->
	<!--[if IE 8 ]>
	<link href="<?php echo MNKY_CSS ?>/ie8.css" media="screen" rel="stylesheet" type="text/css">
	<![endif]-->
	<!--[if lte IE 6]>
	<div id="ie-message">Your browser is obsolete and does not support this webpage. Please use newer version of your browser or visit <a href="http://www.ie6countdown.com/" target="_new">Internet Explorer 6 countdown page</a>  for more information. </div>
	<![endif]-->

	<?php echo ot_get_option('analytics_code'); ?>
	<?php 
		get_template_part('inc/global/orbit-slider/orbit-functions');
		wp_enqueue_style('orbit-slider-style', MNKY_PATH . '/inc/global/orbit-slider/css/orbit-1.2.3.css', false, '', 'all'); 
	?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<!-- Layout wrapper -->
	<div id="layout-wrapper" class="<?php echo ot_get_option('theme_layout', 'full-width'); ?>">
	<!-- Top sub-nav -->
	<div id="top-sub-nav-wrapper">
		<div id="top-sub-nav-container">
			<?php wp_nav_menu( array('theme_location' => 'footer', 'container_id' => 'top-sub-nav', 'fallback_cb' => false));
			?>
		</div>
	</div>
	
<!-- Header -->
	<div id="header-wrapper">
		<div id="header" class="size-wrap">
			
			<div id="logo">
				<?php 
				$default_logo = ot_get_option('logo_upload');
				$retina_logo = ot_get_option('retina_logo_upload');
				if (!$default_logo){
					echo '<a href="'. home_url() .'">
							<h1>', bloginfo('name') .'</h1>
						</a>';
				} else {
					list($width, $height, $type, $attr) = getimagesize($default_logo);
					if ($retina_logo){
						echo '<a href="'. home_url() .'">
							<img src="'. $default_logo .'" '. $attr .' alt="', bloginfo('name') .'" class="default-logo" />
							<img src="'. $retina_logo .'" '. $attr .' alt="', bloginfo('name') .'" class="retina-logo" />
						</a>';
					} else {
						echo '<a href="'. home_url() .'">
							<img src="'. $default_logo .'" '. $attr .' alt="', bloginfo('name') .'" />
						</a>';
					}
				}
				
				?>
			</div>
			<div class="ecommerce-europe-logo">
                <a href="http://www.ecommerce-europe.eu/home">Ecommerce Europe</a>
            </div>
			
			<?php get_sidebar('top') ?>
			<div id="menu-wrapper">
				<?php if (!ot_get_option('header_search')) { ?>
					<a class="toggleMenu" href="#"><?php _e('Menu', 'kickstart'); ?><span></span><div class="clear"></div></a>
					<?php wp_nav_menu( array('theme_location' => 'primary', 'container' => false, 'items_wrap' => '<ul id="primary-main-menu" class=%2$s>%3$s<li class="header-search-toggle"><a href="#">'. __('Søk', 'kickstart') .'</a></li></ul>', 'fallback_cb' => false)); ?>
				<?php } else { ?>
					<a class="toggleMenu" href="#"><?php _e('Menu', 'kickstart'); ?><span></span><div class="clear"></div></a>
					<?php wp_nav_menu( array('theme_location' => 'primary', 'container' => false, 'items_wrap' => '<ul id="primary-main-menu" class=%2$s>%3$s</ul>', 'fallback_cb' => false)); ?>
				<?php } ?>	
				<div class="clear"></div>
			</div>
			
			<?php if (!ot_get_option('header_search')) { 
				echo '<div id="header-search-wrapper" >'; 
					get_search_form();
				echo '</div>';
			} ?>
			
		</div>
	</div>

	<!-- Orbit slider -->
		<script type="text/javascript">   
			var $j = jQuery.noConflict();
			$j(function(){ 
				$j(window).load(function(){         
					$j('#orbit-content').orbit();
				});
			});
		</script>
		
		<div id="orbit-wrapper" class="size-wrap">
			<div id="orbit-content" class="size-wrap"> 
				<?php
				  $orbit_slides = ot_get_option( 'orbit_slides', array() );
				  
				    if ( ! empty( $orbit_slides ) ) {
						foreach( $orbit_slides as $orbit_slide ) {
							
							$orbit_height = ot_get_option('orbit_height', '350px');
							$orbit_height = str_replace("px", "", $orbit_height);
							
							if(empty($orbit_slide['orbit_link'])){
								echo  '<img src="'. aq_resize( $orbit_slide['orbit_image'], '940', $orbit_height, true) .'" alt="'. $orbit_slide['title'] .'" />';
							} 
							else{
								echo  '<a href="'. $orbit_slide['orbit_link']. '"><img src="'. aq_resize( $orbit_slide['orbit_image'], '940', $orbit_height, true) .'" alt="'. $orbit_slide['title'] .'" /></a>';
							} 
						}
					}
				?>
			</div>
		</div>

<!-- Wrapper -->
	<div id="wrapper" class="size-wrap">