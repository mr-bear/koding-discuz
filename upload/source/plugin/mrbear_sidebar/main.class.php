<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_mrbear_sidebar{
	public function global_footer() {
		global $_G;
		include template('mrbear_sidebar:main');
		return $return;

	}
}