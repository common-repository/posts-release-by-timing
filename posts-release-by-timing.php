<?php
/*
Plugin Name: Posts Release By Timing
Plugin URI: http://www.qchenlei.com/posts-release-by-timing-plugin
Description: Achieve the "draft" posts in some categories timing and quantitative release by setting release time and release amount/number.it need used together with "WP Missed Schedule Fix Failed Future Posts" Plugin which decided the timing posts release amounts and time. 
Version: 1.2
Author: Chen Lei
Author URI: http://www.qchenlei.com
*/

/*  Copyright 2012  Chen Lei  (email : chenleicncl@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(is_admin()){
	// Make sure we don't expose any info if called directly
	if ( !function_exists( 'add_action' ) ) {
		echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
		exit;
	}
	
	//load local language
	add_action('init', 'posts_release_by_timing_textdomain');
	function posts_release_by_timing_textdomain() {
		load_plugin_textdomain('posts-release-by-timing', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/');
	}

	//Function: Posts Release By Timing Option Menu
	add_action('admin_menu','postsReleaseByTiming_menu');
	function postsReleaseByTiming_menu(){
		if(function_exists('add_posts_page')){
			add_posts_page(__('PostsReleaseByTiming','posts-release-by-timing'),__('PostsReleaseByTiming','posts-release-by-timing'),'manage_options',dirname(__FILE__).'/PRBT-options.php');
		}
	}
	

}

?>