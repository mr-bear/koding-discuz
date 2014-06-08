<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_mrbear_activeuser{

}

class plugin_mrbear_activeuser_forum extends plugin_mrbear_activeuser {
	
	function forumdisplay_top(){
		global $_G;
		if ($_G['cache']['plugin']['mrbear_activeuser']['position'] != 2) {
			return "";
		}
		$activeCode_lang = lang('plugin/mrbear_activeuser', 'active_english');

		//get num from Back-stage
		$size = intval($_G['cache']['plugin']['mrbear_activeuser']['size'])?$_G['cache']['plugin']['mrbear_activeuser']['size']:10;
		$size = $size>12?12:$size;
		$fid = $_GET['fid'];
		//query 
		$online_data = $this->_queryData($fid,$size);
		$online_count = count($online_data);

		$data_struct = "<div class=\"bm bml pbn\"><div class=\"bm_h cl\" style=\"height:102px;\">";
		$data_struct .= "<h2 style=\"height:22px;font-size:14px;color:#4ba733;background-color:#E8EFF5;padding-bottom:7px;\">$activeCode_lang</h2>";
		for ($i=0; $i < $online_count; $i++) { 
			$uid = $online_data[$i]['authorid'];
			$uname = $online_data[$i]['author'];
			$user_icon = "uc_server/avatar.php?uid=".$uid."&size=small";
			$user_home = "home.php?mod=space&uid=".$uid;
			if ($i == 0) {
				$data_struct .= "<div style=\"display:inline-block;margin-top:3px;*zoom:1;*display:inline;\">";
			}else{
				$data_struct .= "<div style=\"display:inline-block;margin-top:3px;margin-left:10px;*zoom:1;*display:inline;\">";
			}
			$data_struct .= "<img src=\"$user_icon\" alt=\"$uname\"><span style=\"display:block;white-space:normal;overflow:hidden;width:50px;text-align:center;height:31px;\" ><a href=\"$user_home\" target=\"_blank\" title=\"$uname\">$uname</a></span></div>";
		}
		
		$data_struct .= "</div></div>";
		return $data_struct;
	}

	function forumdisplay_leftside_bottom(){
		global $_G;
		if ($_G['cache']['plugin']['mrbear_activeuser']['position'] != 1) {
			return "";
		}

		$activeCode_lang = lang('plugin/mrbear_activeuser', 'active_english');

		//get num from Back-stage
		$size = intval($_G['cache']['plugin']['mrbear_activeuser']['size'])?$_G['cache']['plugin']['mrbear_activeuser']['size']:10;
		$fid = $_GET['fid'];
		//query 
		$online_data = $this->_queryData($fid,$size);
		$online_count = count($online_data);
		$data_struct = "<h2 style=\"height:22px;margin-left:10px;font-size:14px;color:#4ba733\">$activeCode_lang</h2>";
		$data_struct .= "<ul>";
		for ($i=0; $i < $online_count; $i++) { 
			$uid = $online_data[$i]['authorid'];
			$uname = $online_data[$i]['author'];

			$user_icon = "uc_server/avatar.php?uid=".$uid."&size=small";
			$user_home = "home.php?mod=space&uid=".$uid;
			if ($i%2 == 0) {
				$data_struct .= "<li>";
			}
			$data_struct .= "<div style=\"display:inline-block;*zoom:1;*display:inline;\"><img src=\"$user_icon\" style=\"margin-left:10px\" alt=\"$uname\"/><span style=\"display:block;white-space:normal;overflow:hidden;width:50px;height:16px;margin-left:10px;text-align:center;\"><a href=\"$user_home\" target=\"_blank\" title=\"$uname\">".$uname."</a></span></div>";
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
	 * @param  integer $size    [default:10]
	 * @return array           
	 */
	function _queryData($fid,$size=10){
		$threads = array();
		$query = DB::query("SELECT author,authorid,count(*) FROM ".DB::table("forum_post")." WHERE fid=".$fid." GROUP BY authorid ORDER BY count(*) DESC LIMIT ".$size);
		while($thread = DB::fetch($query)) {
				$threads[] = $thread;
		}
		return $threads;
	}

}