<?php   
/*
Plugin Name: uSquare
Plugin URI: http://ww.shindiristudio.com/usquare-wp/
Description: HTML grid for items
Author: br0
Version: 1.6.1
Author URI: http://www.shindiristudio.com/
*/

$usquare_version='1.6.1';

if (isset($_GET['get_version'])) {echo $usquare_version; exit;}

if (!class_exists("usquareAdmin")) {
	require_once dirname( __FILE__ ) . '/usquare_class.php';	
	$usquare = new usquareAdmin (__FILE__, 'uSquare', $usquare_version);
}

?>