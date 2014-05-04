<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_bump.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$lang = array
(
	'bump_name' => '提升卡',
	'bump_forum' => '允许使用本道具的版块',
	'bump_desc' => '可以提升某个主题',
	'bump_info' => '提升指定的主题，请输入主题的 ID',
	'bump_info_nonexistence' => '请指定要提升的主题',
	'bump_succeed' => '您操作的主题已提升',
	'bump_info_noperm' => '对不起，主题所在版块不允许使用本道具',

	'bump_notification' => '您的主题 {subject} 被 {actor} 使用了{magicname}，<a href="forum.php?mod=viewthread&tid={tid}">快去看看吧！</a>',
);

?>