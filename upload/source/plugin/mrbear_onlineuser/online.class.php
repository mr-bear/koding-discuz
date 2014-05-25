<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_mrbear_onlineuser{

}

class plugin_mrbear_onlineuser_forum extends plugin_mrbear_onlineuser {
	function forumdisplay_threadtype_extra(){

	}

	function forumdisplay_leftside_bottom(){
		$fid = $_GET['fid'];
		$online_data = $this->_query($fid);
		$online_count = count($online_data);
		$data_struct = "<h2 style=\"height:22px;margin-left:10px;font-size:14px;color:#4ba733\">在线用户(".$online_count.")</h2>";
		$data_struct .= "<ul>";
		//var_dump($online_data);
		for ($i=0; $i < $online_count; $i++) { 
			$guest = '游客'; //lang
			$uid = $online_data[$i]['uid'];
			$uname = $uid?$online_data[$i]['username']:$guest;

			$guest_icon = "uc_server/images/noavatar_small.gif";
			$user_icon = $uid?"uc_server/avatar.php?uid=".$uid."&size=small":$guest_icon;
			if ($i%2 == 0) {
				$data_struct .= "<li>";
			}
			$data_struct .= "<div style=\"display:inline-block\"><img src=\"$user_icon\" style=\"margin-left:10px\" /><span style=\"display:block;text-align:center;\">".$uname."</span></div>";
			if ($i%2 == 1) {
				$data_struct .= "</li>";
			}
		}

		$data_struct .= "</ul>";

		return $data_struct;
	}
	

	/**
	 * query online data from db
	 * @param  integer $fid     
	 * @param  integer $size    [default:4]
	 * @param  boolean $islogin [query user which not login]
	 * @return array           
	 */
	function _query($fid,$size=4,$islogin=false){
		$threads = array();
		$query = DB::query("SELECT * FROM ".DB::table("common_session")." WHERE fid='".$fid."'");
		while($thread = DB::fetch($query)) {
				$threads[] = $thread;
		}
		return $threads;
	}


	function _queryData($fid){
		$threads = array();
		$query = DB::query("SELECT authorid,count(*) FROM ".DB::table("forum_post")." WHERE fid=".$fid." GROUP BY authorid ORDER BY count(*) DESC");
		while($thread = DB::fetch($query)) {
				$threads[] = $thread;
		}
		return $threads;
	}

}