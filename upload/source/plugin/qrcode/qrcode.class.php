<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_qrcode{

}

class plugin_qrcode_forum extends plugin_qrcode {

	const WIDTH  = 100;
	const HEIGHT = 100;
	const MARGIN = 0;
	const LEVEL  = 'L';

	/**
	 * 嵌入点
	 * @return [type] [description]
	 */
	function viewthread_title_extra(){
		global $_G;
		$qrcode_config = $_G['cache']['plugin']['qrcode'];
		$width  = intval($qrcode_config['width']);
		$height = intval($qrcode_config['height']);
		$level  = self::checkLevel($qrcode_config['level']);
		$margin = intval($qrcode_config['margin']);
		$chl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

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

		return "<span class=\"xg1\" style=\"position:absolute;margin-top:3px\"><a href=\"javascript:;\" onmousemove=\"javascript:document.getElementById('qrcodespan').style.display='inline-block'\" onmouseout=\"javascript:document.getElementById('qrcodespan').style.display='none'\">[手机二维码]</a></span>"."<span id=\"qrcodespan\" name=\"qrcodespan\" style=\"position:absolute;margin-top:30px;background:#fff;border:1px solid #c7c7c7;display:none\">".$qrcode_img."</span>";
	}

	/**
	 * 使用google api生成二维码
	 * $chl参数：二维码内容
	 * $width参数：生成二维码的尺寸
	 * $height参数：生成二维码的尺寸
	 * $level参数：可选参数，纠错等级，L-(默认)可以识别已损失7%的数据；M-可以识别已损失15%的数据；Q-可以识别已损失25%的数据；H-可以识别已损失30%的数据;
	 * $margin 是指生成的二维码离边框的距离;
	*/
	function generateQRfromGoogle($chl, $width = '100', $height= '100', $level = 'L', $margin= '0') {
		$chl = urlencode($chl);
		$img = '<img src="http://chart.apis.google.com/chart?chs='.$width.'x'.$height.'&amp;cht=qr&chld='.$level.'|'.$margin.'&amp;chl='.$chl.'" alt="" width="'.$width.'" height="'.$height.'" />';
		return $img;
	}

	/**
	 * 检查传入等级合法性
	 */
	static function checkLevel($level){
		if (in_array(strtoupper($level), array('L','M','Q','H'))) {
			return strtoupper($level);
		}else{
			return self::LEVEL;
		}
	}


}