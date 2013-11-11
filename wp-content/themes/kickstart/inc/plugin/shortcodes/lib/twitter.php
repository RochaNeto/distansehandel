<?php
/*

	Plugin Name: Mini twitter feed
	Plugin URI: http://minitwitter.webdevdesigner.com
	Description: This plugin displays tweets from your feed, from the Twitter Search, from a list or from your favorite users. 
	Author: Web Dev Designer
	Version: 1.4
	Author URI: http://www.webdevdesigner.com
	
	
    Copyright 2012  Web Dev Designer (email : olivier@webdevdesigner.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
*/

function su_get_tweets( $atts, $content=null ) {
	shortcode_atts(array('id'=>null,'username'=>null, 'list'=>null, 'query' => null, 'limit' => null), $atts);
	$options = (($atts['username'])?'username:"'.$atts['username'].'",':'username:"webdevdesigner",');
	$options .= (($atts['limit'])?'limit:'.$atts['limit'].',':'');
	$options .= (($atts['query'])?'query:"'.$atts['query'].'",':'');
	$options .= (($atts['list'])?'list:"'.$atts['list'].'",':'');
	
	return '<div class="tweets"> 
				<div class="content_tweets'.$atts['id'].'"> </div> 
				<div class="tweets_footer">
					<span id="bird"></span>
				</div> 
			</div>
			<script type="text/javascript">
				jQuery(".content_tweets'.$atts['id'].'").miniTwitter({
					'.$options.'
					retweet:true
				});
			</script>';
}
?>