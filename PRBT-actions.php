<?php
/*
* accept the $_POST data, call the related commands execute
*
*/
	
	if($_POST["catID"]&&$_POST["amounts"]&&$_POST["perTime"]>=0&&$_POST["numbers"]>=0){
		//switch timestamp
		$post_time = mktime($_POST["hour"],$_POST["minute"],00,$_POST["month"],$_POST["day"],$_POST["year"]);
		
		//correction illegal time
		if($post_time<current_time("timestamp", 0)){
			$post_time = current_time("timestamp", 0);
		}
		$perTime = $_POST["perTime"];
		$catID = $_POST["catID"];
		$amounts = $_POST["amounts"];
		$numbers = $_POST["numbers"];
		
		//Get the current time zone offset value
		//$current_offset = get_option('gmt_offset');
		if (class_exists("TimingRelease")) {
			//get the difference value between standard UTC time stamp and local time stamp
			$current_offset_time=current_time("timestamp", 1)-current_time("timestamp", 0);
			for($i=0;$i<$numbers;$i++){
				//Set the posts timing
				if($i==0){
					$post_time = $post_time;
				}else{
					$post_time = $post_time + 3600*$perTime;
				}
				$post_date = date('Y-m-d H:i:s',$post_time);
				$post_date_gmt = date('Y-m-d H:i:s',$post_time+$current_offset_time);
				//execute the Timing
				$obj = new TimingRelease($catID, $post_date, $post_date_gmt, $amounts);
				$result = $obj->prbt_timing();
				if($result ==0 && $i==0){
					die("Execute Failed! maybe there are not 'draft' post in this Category");
				}
				if($result ==0){
					break;
				}
			}
			echo "Success! The posts in Category ID:".$catID."&nbsp; were timing release!";
		}
		
	}
	if($_POST["draft"]!= ''){
		//Remove the timing ,change it to 'draft'
		$obj = new TimingRelease($_POST["draft"]);
		$result = $obj->prbt_reset_timing();
		if($result == 0){
			die("Execute Failed! maybe there are not timing posts in this category");
		}else{
			echo "Success! The posts in Category ID:".$_POST["draft"]."&nbsp;were reset the 'draft' posts";
		}
	}
?>