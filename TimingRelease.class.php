<?php
	/**
	*ClassName:TimingRelease
	* declare a class that handle the database,update the posts status,achieve the posts timing release and reset
	*@param ($catID, $num) category ID,and Posts release amount.
	*@author：chenlei
	*/
 if (!class_exists("TimingRelease")) {
	class TimingRelease{
		//declare the member property: the category ID, the publish time(locale), the publish time(gmt), the release amounts
		private $catID;
		private $post_date;
		private $post_date_gmt;
		private $amounts;
		
		public function __construct($catID, $post_date='0000-00-00 00:00:00', $post_date_gmt='0000-00-00 00:00:00', $amounts=0){
			$this->catID = $catID;
			$this->post_date = $post_date;
			$this->post_date_gmt = $post_date_gmt;
			$this->amounts = $amounts;
		}
		
		/*
		*function:declare a function timing posts, the prefix is "prbt_"
		*@param: the arguments in function __construct()
		*/
		public function prbt_timing(){
			/*
			*IN() subquery，get the posts ID in this category
			*UPADATE the posts will publish time in post table
			*UPDATA requirements：the future posts, no passwd, in this category, sort ASC and LIMIT release amounts
			*/
			global $wpdb;
			//Execute the UPDATE statement and parameters preliminary binding
			$sql = "UPDATE $wpdb->posts SET post_date = %s,post_date_gmt = %s,post_status = 'future' WHERE ID IN(SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d) AND post_status = 'draft' ORDER BY ID ASC LIMIT %d";
			$result = $wpdb->query(
						$wpdb->prepare(
							$sql,
							$this->post_date,
							$this->post_date_gmt,
							$this->catID,
							$this->amounts
						)
					);
			return $result;
			//wp_publish_post()
		}
		
		/*
		*function: reset the posts status 'draft'
		*/
		public function prbt_reset_timing(){
			global $wpdb;
			//UPDATE statement, make the posts status 'future' become the 'draft'
			$sql="UPDATE $wpdb->posts SET post_status = 'draft' WHERE ID IN(SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d) AND post_status = 'future'";
			
			$result = $wpdb->query(
						$wpdb->prepare(
							$sql,
							$this->catID
						)
					);
			return $result;
		}
		
		/*
		* function: SELECT the timing posts amounts in some categroy
		*/
		
		public function prbt_category_future_count(){
			global $wpdb;
			//SELECT statement
			$sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE ID IN(SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d) AND post_status = 'future'";
			$result = $wpdb->get_var(
						$wpdb->prepare(
							$sql,
							$this->catID
						)
					);
			return $result;
		}
		
		//SElECT the last timing posts "date" in some category
		public function prbt_category_lastpost_time(){
			global $wpdb;
			//SELECT statement
			$sql = "SELECT post_date FROM $wpdb->posts WHERE ID IN(SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d) AND post_status = 'future' ORDER BY ID DESC LIMIT 2";
			$result = $wpdb->get_var(
						$wpdb->prepare(
							$sql,
							$this->catID
						)
					);
			return $result;
		}
		
		//SELECT the 'draft' posts amounts in some category
		public function prbt_category_draft_count(){
			global $wpdb;
			//Execute the SELECT statement
			$sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE ID IN(SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d) AND post_status = 'draft'";
			$result = $wpdb->get_var(
						$wpdb->prepare(
							$sql,
							$this->catID
						)
					);
			return $result;
		
		}
	}
}




?>