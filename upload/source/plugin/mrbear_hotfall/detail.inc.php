<?php
/**
 * Created by PhpStorm.
 * User: xiongfei
 * Date: 14-8-29
 * Time: 下午5:28
 */

if (!defined('IN_DISCUZ')) {

    exit('Access Denied');

}
global $_G;
$limit = 20;
$offset = 0;
$returnStruct = array(
	'now' => Date('Y-m-d H:i:s'),
	'ok' => false,
	'limit' => limit,
	'result' => array(),
	'offset' => 0,
	'total' => 0
);

if(isset($_GET['offset']) && isset($_GET['limit']) && is_numeric($_GET['offset']) && is_numeric($_GET['limit'])){
	$offset = intval($_GET['offset']);
	$limit = intval($_GET['limit']);
}

if($limit == 0){
	echo json_encode($returnStruct);
	exit();
}

$hottime = dintval(str_replace('-', '', $_GET['time']));
if($hottime && checkdate(substr($hottime, 4, 2), substr($hottime, 6, 2), substr($hottime, 0, 4))) {

	$calendartime = abs($hottime);

} else {

	$calendartime = dgmdate(strtotime(dgmdate(TIMESTAMP, 'Y-m-d')) - 86400, 'Ymd');

}
$calendartime = '20140612'; //TODO test
$caldata = C::t('forum_threadcalendar')->fetch_by_fid_dateline($_G['fid'], $calendartime);

// var_dump($caldata);
if($caldata) {

	$hottids = C::t('forum_threadhot')->fetch_all_tid_by_cid($caldata['cid']);

	$threadlist = C::t('forum_thread')->fetch_all_by_tid($hottids);
	var_dump($threadlist);

}

