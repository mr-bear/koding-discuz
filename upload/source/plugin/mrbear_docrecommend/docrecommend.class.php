<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_mrbear_docrecommend{
	//save_syscache('test','test');

}

class plugin_mrbear_docrecommend_forum extends plugin_mrbear_docrecommend {
	function viewthread_avatar_output(){
		$tid = $_GET['tid'];
		$query = DB::query("SELECT * FROM ".DB::table("common_session")." WHERE tid='".$tid."'");
		while($thread = DB::fetch($query)) {
				$threads[] = $thread;
		}
		var_dump($threads);
	}

	function viewthread_useraction(){
		global $_G,$postlist;
		loadcache('plugin');
		//var_dump($postlist);
		$div_label = "<div style=\"display:block\"><h2 style=\"line-height:2;padding-bottom:5px;margin-bottom;20px;border-bottom:1px solid #e8e8e8;font-weight:normal;\"></h2>";
		$recommend_content = "
		<ul style=\"width:700px;margin:0 auto;\">
			<li style=\"width:132px;float:left;margin-left:30px\">
				<img width=\"132\" height=\"102\" src=\"http://r.aicaicdn.com/news/image/2014/4/9/201404091343000014.jpg\">
				<a href=\"http://www.xici.net\" style=\"font-size:12px;line-height:18px;word-wrap:break-word;font-family:Arial,Helvetica,sans-serif;height:36px;overflow:hidden;white-space:normal;color:#0078b6;background:white;padding-left:0;text-align:left;\" target=\"_blank\">化纸为云：将书写与云端相连的智能笔</a>
			</li>
			<li style=\"width:132px;float:left;margin-left:30px\">
				<img width=\"132\" height=\"102\" src=\"http://r.aicaicdn.com/news/image/2014/4/9/201404091343000014.jpg\">
				<a href=\"http://www.xici.net\" style=\"font-size:12px;line-height:18px;word-wrap:break-word;font-family:Arial,Helvetica,sans-serif;height:36px;overflow:hidden;white-space:normal;color:#0078b6;background:white;padding-left:0;text-align:left;\" target=\"_blank\">化纸为云：将书写与云端相连的智能笔</a>
			</li>
			<li style=\"width:132px;float:left;margin-left:30px\">
				<img width=\"132\" height=\"102\" src=\"http://r.aicaicdn.com/news/image/2014/4/9/201404091343000014.jpg\">
				<a href=\"http://www.xici.net\" style=\"font-size:12px;line-height:18px;word-wrap:break-word;font-family:Arial,Helvetica,sans-serif;height:36px;overflow:hidden;white-space:normal;color:#0078b6;background:white;padding-left:0;text-align:left;\" target=\"_blank\">化纸为云：将书写与云端相连的智能笔</a>
			</li>
			<li style=\"width:132px;float:left;margin-left:30px\">
				<img width=\"132\" height=\"102\" src=\"http://r.aicaicdn.com/news/image/2014/4/9/201404091343000014.jpg\">
				<a href=\"http://www.xici.net\" style=\"font-size:12px;line-height:18px;word-wrap:break-word;font-family:Arial,Helvetica,sans-serif;height:36px;overflow:hidden;white-space:normal;color:#0078b6;background:white;padding-left:0;text-align:left;\" target=\"_blank\">化纸为云：将书写与云端相连的智能笔</a>
			</li>
			
		</ul>";
		$div_label_close = "</div>";
		$show_content = $div_label.$recommend_content.$div_label_close;
		return $show_content;
	}

}