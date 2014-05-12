<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


class plugin_mrbear_qrcode{

}

class plugin_mrbear_qrcode_forum extends plugin_mrbear_qrcode {

	const WIDTH  = 100;
	const HEIGHT = 100;
	const MARGIN = 0;
	const LEVEL  = 'L';

	/**
	 * viewthread_title_extra
	 * @return [type] [description]
	 */
	function viewthread_title_extra(){
		global $_G;
		$qrcode_config = $_G['cache']['plugin']['mrbear_qrcode'];
		$width  = intval($qrcode_config['width']);
		$height = intval($qrcode_config['height']);
		$level  = self::checkLevel($qrcode_config['level']);
		$margin = intval($qrcode_config['margin']);
		//$chl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$chl = self::get_url();
		if (!$width) {
			$width = self::WIDTH;
		}
		if (!$height) {
			$height = self::HEIGHT;
		}
		if (!$margin) {
			$margin = self::MARGIN;
		}
		$qrcode_img = $this->generateQRfromGoogle($chl, $width, $height, $level, $margin);
		$qrCode_lang = lang('plugin/mrbear_qrcode', 'qrcode_english');
		return "<span class=\"xg1\" style=\"position:absolute;margin-top:3px\"><a href=\"javascript:;\" onmousemove=\"javascript:document.getElementById('qrcodespan').style.display='inline-block'\" onmouseout=\"javascript:document.getElementById('qrcodespan').style.display='none'\">[$qrCode_lang]</a></span>"."<span id=\"qrcodespan\" name=\"qrcodespan\" style=\"position:absolute;margin-top:30px;background:#fff;border:1px solid #c7c7c7;display:none\">".$qrcode_img."</span>";
	}

	/**
	 * Generate qrCode by Google Api
	 * @param $chl [qrcode content]
	 * @param $width [img width]
	 * @param $height [img height]
	 * @param $level [errorCorrectionLevel，L-(default)；M；Q；H
	 * @param $margin 
	*/
	function generateQRfromGoogle($chl, $width = '100', $height= '100', $level = 'L', $margin= '0') {
		$chl = urlencode($chl);
		$img = '<img src="http://chart.apis.google.com/chart?chs='.$width.'x'.$height.'&amp;cht=qr&chld='.$level.'|'.$margin.'&amp;chl='.$chl.'" alt="" width="'.$width.'" height="'.$height.'" />';
		return $img;
	}

	/*
	get url
	 */
	static function get_url() {
		$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
		$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
		$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
		$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
		return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
	}

	/**
	 * check legitimate of params
	 */
	static function checkLevel($level){
		if (in_array(strtoupper($level), array('L','M','Q','H'))) {
			return strtoupper($level);
		}else{
			return self::LEVEL;
		}
	}


}