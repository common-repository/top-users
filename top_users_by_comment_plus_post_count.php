<?php
/*
Plugin Name: Top users by comment plus post count
Plugin URI: http://www.tacticaltechnique.com/wordpress/top-users/
Description: List the top contributors by combined comment and post count
Version: 0.110827
Author: Corey Salzano
Email: coreysalzano@gmail.com
Author URI: http://profiles.wordpress.org/users/salzano/
License: GPL2
*/

if( !function_exists("top_users_by_comment_plus_post_count")){
	function top_users_by_comment_plus_post_count( $howManyUsers ){

		if( !is_numeric( $howManyUsers ) || $howManyUsers < 1 ){
			$howManyUsers = 10;
		}

		global $wpdb;

		$q  = "SELECT $wpdb->comments.comment_author, COUNT($wpdb->comments.comment_ID) AS `comment_count`, ( ";
		$q .= 	"SELECT COUNT($wpdb->posts.ID) AS `post_count` FROM $wpdb->posts ";
		$q .= 	"WHERE post_type = 'post' AND post_status = 'publish' AND post_author = $wpdb->comments.user_id ";
		$q .= ") as `post_count`, COUNT($wpdb->comments.comment_ID) + ( ";
		$q .= 	"SELECT COUNT($wpdb->posts.ID) AS `post_count` FROM $wpdb->posts ";
		$q .=	"WHERE post_type = 'post' AND post_status = 'publish' AND post_author = $wpdb->comments.user_id ";
		$q .= ") as `combined_count` ";
		$q .= "FROM $wpdb->comments ";
		$q .= "WHERE $wpdb->comments.comment_approved = 1 AND $wpdb->comments.comment_type = '' ";
		$q .= "GROUP BY $wpdb->comments.comment_author ";
		$q .= "ORDER BY combined_count DESC ";
		$q .= "LIMIT " . $howManyUsers;

		$myrows = $wpdb->get_results( $q );

		echo "<table id=\"top-users-by-comment-plus-post-count\">";

		foreach( $myrows as $row ){
			echo "<tr><td class=\"tu-author\">$row->comment_author</td><td class=\"tu-count\">$row->combined_count</td></tr>";
		}

		echo "</table>";

	}
}

?>