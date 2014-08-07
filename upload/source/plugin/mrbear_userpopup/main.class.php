<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


class plugin_mrbear_userpopup{

	function viewthread_output() {
        global $_G;

        // if(!in_array($_G['fid'], (array)unserialize($_G['cache']['plugin']['fieah_bigsizeavatar']['forums']))) {
        //     extract($_G['cache']['plugin']['fieah_bigsizeavatar']);
        //     $groups = (array)unserialize($groups);
        //     $lazyload = $_G['setting']['lazyload'] ? 'file' : 'src';
        //     $_G['setting']['pluginhooks']['viewthread_top'] .= '<style>.pls .avatar { text-align: center; }'.($size > 48 ? '.pls { width: '.($size + 40).'px; } .pls .avatar img { height: auto; max-width: '.$size.'px; width: auto; } .ie6 .pls .avatar img { width: expression(this.width > '.$size.' ? '.$size.' : true); } .bui { width: 510px !important; } .bui .m img { height: auto; max-width: '.$size.'px; width: auto; } .ie6 .bui .m img { width: expression(this.width > '.$size.' ? '.$size.' : true); } '.($size >= 180 ? '.pls dt { width: 70px; } .pls dd { width: 85px; }' : '').($size = 'big') : '.pls .avatar img { height: auto; max-width: '.$size.'px; width: auto; }'.($size = 'small')).'</style>';
        //     $_G['setting']['pluginhooks']['viewthread_bottom'] .= $_G['uid'] ? '<script>$C(\'avatar avtm\')[0].innerHTML = \''.addcslashes(avatar($_G['uid'], $size), '\'').'\';</script>' : '';
                
        //     global $postlist;

        //     // var_dump($postlist);
        //     foreach($postlist as $key => $post) {
        //         !in_array($post['groupid'], $groups) && $postlist[$key]['avatar'] = strtr($post['avatar'], array('src' => $lazyload, 'middle' => $size));
        //     }
        // }
    }
}


class plugin_mrbear_userpopup_forum extends plugin_mrbear_userpopup {
	
}