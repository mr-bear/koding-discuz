<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {

	exit('Access Denied');

}
loadcache('plugin');
$_G['cache']['plugin']['mrbear_recommend'] = array();
//C::t('mrbear_docrecommend')->update_batch(array('mrbear_docrecommend'=>array()));

updatecache('plugin');
var_dump($_G['cache']['plugin']);

//showsetting(title,labelname,value,type)
//showtips($templatelang['sitemap']['sitemap_tips']);  
showformheader('plugins&operation=config&do='.$pluginid.'&identifier=mrbear_docrecommend&pmod=recadmin','settingsubmit');  
showtableheader();  
showsetting('文本1','text1','','text');  
showsetting('文本2','text2','','text');  
showsubmit('settingsubmit');  
showtablefooter();  
showformfooter();

