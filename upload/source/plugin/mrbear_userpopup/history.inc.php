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
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

require_once DISCUZ_ROOT.'/source/function/function_home.php';

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
$groupName = '';
$userGroupInfo = getUserGroupName($userInfo[0]['groupid']);
if(!empty($userGroupInfo)){
    $groupName = $userGroupInfo[0]['grouptitle'];
}

require_once libfile('function/misc');
$regLoc = $userInfo[0]['regip'].convertip($userInfo[0]['regip']);

$total = (!empty($historyCountInfo))?$historyCountInfo[0]['count']:0;
$retNum = count($historyInfo);
$firstId = 0;
$lastId = 0;

$commentStruct = array();
foreach($historyInfo as $key=>$itemHis){
    $upNum = 0;
    $repNum = 0;
    $subject = $itemHis['subject'];
    $threadInfo = getThreadInfo($itemHis['tid']);
    if($itemHis['first'] == 0){
        //reply
        if(!empty($threadInfo)){
            $subject = $threadInfo[0]['subject'];
        }
        $hotInfo = getHotreply($itemHis['pid'],$itemHis['tid']);

        $replyRepInfo = getReplyRelNum($itemHis['pid'],$itemHis['tid']);
        if(!empty($replyRepInfo)){
            $repNum = $replyRepInfo[0]['count'];
        }
    }else{
        //thread
        $hotInfo = getHotreply(0,$itemHis['tid']);
        if(!empty($threadInfo)){
            $repNum = $threadInfo[0]['replies'];
        }
    }

    if(!empty($hotInfo)){
        $upNum = $hotInfo[0]['support'];
    }

    $itemContent = getstr($itemHis['message'], 200,1,0,1);
    $itemCommentStruct = array(
        'id' => $itemHis['pid'],
        'targetid' => $itemHis['tid'],
        'appid' => 0,
        'parent' => $itemHis['first'],
        'time' => $itemHis['dateline'],
        'userid' => $uid,
//        'content' => $itemHis['message'],
        'content' => $itemContent,
        'up' => $upNum,
        'repnum' => $repNum,
        'checkstatus' => $itemHis['invisible'],
        'isdeleted' => $itemHis['invisible'],
        'ip' => 0,
        'isfirst' => $itemHis['first'],
        'position' => $itemHis['position'],
        'targetinfo' => array(
            'title' => $subject,
            'url' => $_G['siteurl'].'forum.php?mod=viewthread&tid='.$itemHis['tid'].'#pid'.$itemHis['pid'],
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
    'region' => $regLoc,
    'hwvip' => 0,
    'hwlevel' => 0,
    'thirdlogin' => 0,
    'groupname' => $groupName,
    'groupid' => $userInfo[0]['groupid'],
    'friends' => $userInfo[0]['friends']
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


function getThreadInfo($tid){
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
    $queryCon = 'select a.username,a.avatarstatus,a.regdate,a.credits,a.groupid,b.friends,b.posts,b.threads,b.digestposts,b.follower,b.following,c.regip from '.DB::table('common_member').' a join '.DB::table('common_member_count').' b join '.DB::table('common_member_status').' c on  a.uid='.intval($uid).' and a.uid=b.uid and a.uid=c.uid';
    $userInfo =  DB::fetch_all($queryCon);
    return $userInfo;
}


function getUserGroupName($gid){
    if(!intval($gid)){
        return array();
    }
    $queryCon = 'select grouptitle from '.DB::table('common_usergroup').' where groupid='.intval($gid);
    $groupInfo =  DB::fetch_all($queryCon);
    return $groupInfo;

}


function getHotreply($pid,$tid){
    if(!intval($tid)){
        return array();
    }

    if(intval($pid)){
        $queryCon = 'select support,against,total from '.DB::table('forum_hotreply_number').' where pid='.intval($pid).' and tid='.intval($tid);
        $hotInfo =  DB::fetch_all($queryCon);
    }else{
        $queryCon = 'select count(support) as support,count(against) as against from '.DB::table('forum_hotreply_number').' where tid='.intval($tid);
        $hotInfo =  DB::fetch_all($queryCon);
    }

    return $hotInfo;
}


function getReplyRelNum($pid,$tid){
    if(!intval($tid) || !intval($pid)){
        return array();
    }
    $sel = 'mod=redirect&goto=findpost&pid='.intval($pid).'&ptid='.intval($tid);
    $queryCon = 'select count(1) as count from '.DB::table('forum_post').' where tid='.intval($tid).' and message like \'%'.$sel.'%\'';
    $reInfo =  DB::fetch_all($queryCon);
    return $reInfo;

}