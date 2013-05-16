<?php
/*
Plugin Name: WP-PageNavi / Edited by MNKY Studio (http://mnkystudio.com)
Author: Lester 'GaMerZ' Chan & scribu / Edited by MNKY Studio
Version: 2.83
*/

// Check if plugin already exists
if ( !function_exists( '_pagenavi_init' ) ) {
	
	include dirname( __FILE__ ) . '/scb/load.php';

	function _pagenavi_init() {

		require_once dirname( __FILE__ ) . '/core.php';

		$options = new scbOptions( 'pagenavi_options', __FILE__, array(
			'pages_text'    => __( 'Page %CURRENT_PAGE% of %TOTAL_PAGES%', 'kickstart' ),
			'current_text'  => '%PAGE_NUMBER%',
			'page_text'     => '%PAGE_NUMBER%',
			'first_text'    => __( '&laquo; First', 'kickstart' ),
			'last_text'     => __( 'Last &raquo;', 'kickstart' ),
			'prev_text'     => __( '&laquo;', 'kickstart' ),
			'next_text'     => __( '&raquo;', 'kickstart' ),
			'dotleft_text'  => __( '...', 'kickstart' ),
			'dotright_text' => __( '...', 'kickstart' ),
			'num_pages' => 5,
			'num_larger_page_numbers' => 3,
			'larger_page_numbers_multiple' => 10,
			'always_show' => false,
			'style' => 1
		) );

		PageNavi_Core::init( $options );

		if ( is_admin() ) {
			require_once dirname( __FILE__ ) . '/admin.php';
			new PageNavi_Options_Page( __FILE__, $options );
		}
	}
	scb_init( '_pagenavi_init' );
}
