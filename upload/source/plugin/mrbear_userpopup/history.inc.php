<?php
/**
 * Created by PhpStorm.
 * User: xiongfei
 * Date: 14-8-8
 * Time: 下午2:49
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$returnStruct = array(
    'errCode' => 1,
    'data' => array(),
    'info' => ''
);

if(!isset($_GET['uid']) || !intval($_GET['uid'])){
    echo json_encode($returnStruct);
}
global $_G;
$uid = intval($_GET['uid']);
$start = (isset($_GET['start']))?intval($_GET['start']):0;
$limit = (isset($_GET['limit']))?intval($_GET['limit']):20;

$historyCountInfo = getHistoryCount($uid);
$historyInfo = getUserHistory($uid,$start,$limit);
$userInfo = getUserBaseInfo($uid);

if(empty($userInfo)){
    echo json_encode($returnStruct);
}

$total = (!empty($historyCountInfo))?$historyCountInfo[0]['count']:0;
$retNum = count($historyInfo);
$firstId = 0;
$lastId = 0;

$commentStruct = array();
foreach($historyInfo as $key=>$itemHis){
    $subject = $itemHis['subject'];
    if($itemHis['first'] == 0){
        $threadInfo = getThread($itemHis['tid']);
        if(!empty($threadInfo)){
            $subject = $threadInfo[0]['subject'];
        }
    }
    $itemCommentStruct = array(
        'id' => $itemHis['pid'],
        'targetid' => $itemHis['tid'],
        'appid' => 0,
        'parent' => $itemHis['first'],
        'time' => $itemHis['dateline'],
        'userid' => $uid,
        'content' => $itemHis['message'],
        'up' => 0,
        'repnum' => 0,
        'checkstatus' => $itemHis['invisible'],
        'isdeleted' => $itemHis['invisible'],
        'ip' => 0,
        'isfirst' => $itemHis['first'],
        'position' => $itemHis['position'],
        'targetinfo' => array(
            'title' => $subject,
            'url' => $_G['siteurl'].'forum.php?mod=viewthread&tid='.$itemHis['tid'],
            'checkstatus' => 0,
        ),
    );
    $commentStruct[] = $itemCommentStruct;
    if($key == 0){
        $firstId = $itemHis['pid'];
    }
    $lastId = $itemHis['pid'];
}

$userMeta = array(
    'userid' => $uid,
    'nick' => $userInfo[0]['username'],
    'head' => '',
    'commentnum' => $userInfo[0]['posts'],
    'commentednum' => 0,
    'upnum' => 0,
    'checkstatus' => 0,
    'region' => '',
    'hwvip' => 0,
    'hwlevel' => 0,
    'thirdlogin' => 0
);


$returnStruct['data'] = array(
    'total' => $total,
    'retnum' => $retNum,
    'first' => $firstId,
    'last' => $lastId,
    'comments' => $commentStruct,
    'usermeta' => $userMeta,
);
$returnStruct['errCode'] = 0;

echo json_encode($returnStruct);


function getThread($tid){
    if(!intval($tid)){
        return array();
    }
    $queryCon = 'select subject,views,replies,digest,recommend_add,recommend_sub from '.DB::table('forum_thread ').' where tid='.$tid;
    $threadInfo = DB::fetch_all($queryCon);
    return $threadInfo;
}

function getHistoryCount($uid){
    if(!intval($uid)){
        return array();
    }
    $queryCon = 'select count(1) as count from '.DB::table('forum_post').' where authorid='.intval($uid);
    $postInfo = DB::fetch_all($queryCon);
    return $postInfo;
}


function getUserHistory($uid,$start=0,$limit=20){
    if(!intval($uid)){
        return array();
    }
    $start = intval($start);
    $limit = intval($limit);
    if(!$start){
        $queryCon = "SELECT pid,fid,tid,first,subject,message,dateline,position,invisible FROM ".DB::table('forum_post')." WHERE authorid = ".intval($uid)." order by dateline desc limit ".$limit;
    }else{
        $queryCon = "SELECT pid,fid,tid,first,subject,message,dateline,position,invisible FROM ".DB::table('forum_post')." WHERE authorid = ".intval($uid)." and pid < ".$start." order by dateline desc limit ".$limit;
    }

    $postInfo = DB::fetch_all($queryCon);
    return $postInfo;
}

function getUserBaseInfo($uid){
    if(!intval($uid)){
        return array();
    }
    $queryCon = 'select a.username,a.avatarstatus,a.regdate,a.credits,b.friends,b.posts,b.threads,b.digestposts,b.follower,b.following from '.DB::table('common_member').' a join '.DB::table('common_member_count').' b on  a.uid='.intval($uid).' and a.uid=b.uid';
    $userInfo =  DB::fetch_all($queryCon);
    return $userInfo;
}