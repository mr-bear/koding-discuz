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

require_once DISCUZ_ROOT.'/source/function/function_post.php';
//params init
global $_G;
$limit = 20;
$offset = 0;
$current = Date('Y-m-d H:i:s');
$returnStruct = array(
	'now' => $current,
	'ok' => false,
	'limit' => $limit,
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

//$hottime = dintval(str_replace('-', '', $current));
//if($hottime && checkdate(substr($hottime, 4, 2), substr($hottime, 6, 2), substr($hottime, 0, 4))) {
//
//	$calendartime = abs($hottime);
//
//} else {
//	$calendartime = dgmdate(strtotime(dgmdate(TIMESTAMP, 'Y-m-d')) - 86400, 'Ymd');
//
//}
//$calendartime = '20140612'; //TODO test
//$caldata = C::t('forum_threadcalendar')->fetch_all_by_dateline($calendartime);
//
//if(empty($caldata)){
//    echo json_encode($returnStruct);
//    exit();
//}
//
//$hotNum = $caldata['hotnum'];
//$threadlist = array();
//
//$hottids = C::t('forum_threadhot')->fetch_all_tid_by_cid($caldata['cid']);
//$threadlist = C::t('forum_thread')->fetch_all_by_tid($hottids);
//
//var_dump($caldata);
//if(empty($threadlist)){
//    echo json_encode($returnStruct);
//    exit();
//}

$hotTime = strtotime($current) - 86400*2;
$hotNum = getHotNum($hotTime);
$threadlist = C::t('forum_thread')->fetch_all_by_dateline($hotTime,$offset,$limit,'views','ASC');

foreach($threadlist as $itemThread){
    //authorInfo
    $authorId = $itemThread['authorid'];
    $authorName = pluginIconv($itemThread['author']);
    //threadInfo
    $tid = $itemThread['tid'];
    $fid = $itemThread['fid'];
    $tableId = $itemThread['posttableid'];
    $postTime = $itemThread['dateline'];
    $title = pluginIconv($itemThread['subject']);
    //viewInfo
    $views = $itemThread['views'];
    $replies = $itemThread['replies'];
    $postGMT = date(DATE_ATOM,mktime(date('H',$postTime),date('i',$postTime),date('s',$postTime),date('m',$postTime),date('d',$postTime),date('Y',$postTime)));

    //get thread
    $itemThread = C::t('forum_post')->fetch_all_by_tid($tableId,$tid,true,'',0,1,1,0,$authorId,$fid);
    if(empty($itemThread)){
        continue;
    }
    foreach($itemThread as $itemData){
        $itemThread = $itemData;
    }

    //get forums
    $itemForums = C::t('forum_forum')->fetch_all_name_by_fid($fid);
    if(empty($itemForums)){
        continue;
    }
    foreach($itemForums as $itemData){
        $itemForums = $itemData;
    }
    $forumName = pluginIconv($itemForums['name']);

    //get summary

    $itemSummary = '';
    $messageInfo = getTMessage($tid);
    if(!empty($messageInfo)){
        $message = messagecutstr($messageInfo[0]['message'], 200);
        $itemSummary = pluginIconv($message);
    }
    //get image
    $itemImage = getThreadImageByTid($tid);
    $itemImgStruct = array();
    $itemImgUrl = '';
    if(!empty($itemImage)){
        $itemImgUrl = 'data/attachment/forum/'.$itemImage[0]['attachment'];
        $imgSize = getimagesize($itemImgUrl);
        $width = 0;
        $height = 0;
        if(!empty($imgSize)){
            $width = $imgSize[0];
            $height = $imgSize[1];
        }
        $itemImgStruct = array(
            'url' => $itemImgUrl,
            'width' => $width,
            'height' => $height,
        );
    }

    $itemAuthorStruct = array(
        'followers_count' => 0,
        'ukey' => 0,
        'is_exists' => true,
        'title' => '',
        'url' => $_G['siteurl'].'home.php?mod=space&uid='.$authorId,
        'gender' => null,
        'resource_url' => '',
        'nickname' => $authorName,
        'is_title_authorized' => '',
        'avatar' => array(),
    );

    $itemResStruct = array(
        'image' => '',
        'is_replyable' => true,
        'channels' => array(
//           0 => array(
//               'url' => $_G['siteurl'].'plugin.php?id=mrbear_hotfall:main',
//               'date_created' => '',
//               'name' => '热点',//TODO CONST name value = \u70ed\u70b9
//               'key' => 'hot',
//               'articles_count' => $hotNum
//            ),
        ),
        'channel_keys' => array('hot'),
        'preface' => '',
        'id' => $tid,
        'subject' => array(
            'url' => $_G['siteurl'].'forum.php?mod=forumdisplay&fid='.$fid,
            'date_created' => '',
            'name' => $forumName,
            'key' => 'earth', //TODO  key
            'articles_count' => '',
        ),
        'copyright' => 'owned_by_site',
        'author' => $itemAuthorStruct,
        'image_description' => '',
        'is_show_summary' => false,
        'minisite_key' => null,
        'image_info' => $itemImgStruct,
        'subject_key' => $forumName,
        'minisite' => null,
        'tags' => array(),
        'date_published' => $postGMT,
        'replies_count' => $replies,
        'is_author_external' => false,
        'recommends_count' => 0, //TODO
        'title_hide' => $title,
        'date_modified' => $postGMT,
        'url' => $_G['siteurl'].'forum.php?mod=viewthread&tid='.$tid,
        'title' => $title,
        'small_image' => $itemImgUrl,
        'summary' => $itemSummary,
        'ukey_author' => 'kovctu',
        'date_created' => $postGMT,
        'resource_url' => ''

    );
    $returnStruct['result'][] = $itemResStruct;
}
$returnStruct['ok'] = true;
$returnStruct['offset'] = $offset;
$returnStruct['total'] = $hotNum;
echo json_encode($returnStruct);

function pluginIconv($inData){
    global $_G;
    $charset = $_G['charset'];
    $outData = $inData;
    if('UTF-8' != $charset){
        $outData = diconv($inData,$charset,'UTF-8');
    }
    return $outData;

}


function getThreadImageByTid($tid){
    if(!intval($tid)){
        return array();
    }
    $queryCon = 'select attachment from '.DB::table('forum_threadimage').' where tid='.$tid.' limit 0,1';
    $threadImgInfo = DB::fetch_all($queryCon);
    return $threadImgInfo;
}


function getHotNum($hotTime){
    $queryCon = 'select count(1) as count from '.DB::table('forum_thread').' where dateline>'.$hotTime;
    $countInfo = DB::fetch_all($queryCon);
    $count = 0;
    if(!empty($countInfo)){
        $count = $countInfo[0]['count'];
    }

    return $count;
}


function getTMessage($tid){
    if(!intval($tid)){
        return array();
    }
    $queryCon = 'SELECT message FROM '.DB::table('forum_post').' WHERE tid='.$tid;
    $postInfo = DB::fetch_all($queryCon);
    return $postInfo;
}