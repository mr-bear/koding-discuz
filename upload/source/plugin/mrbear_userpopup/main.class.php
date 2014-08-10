<?php


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_mrbear_userpopup {
    function plugin_mrbear_userpopup() {
        global $_G;
        $this->showextgroupicon = 1;
        $this->showextgroupsite = $_G['cache']['plugin']['nds_showextgroup']['showextgroupsite'];
    }


}

class plugin_mrbear_userpopup_forum extends plugin_mrbear_userpopup {
    function getStruct() {
        global $postlist;
        $ndsreturneg = array();
        if(empty($postlist) || !is_array($postlist)) return $ndsreturneg;

        foreach ($postlist as $post) {
            $postuid = $post['authorid'];
            $test = $this->getUserHistory($postuid);

            $extgrouptitles = '<p><a href="javascript:showIframe('.$postuid.')"><em>test</em></a></p>';
            $ndsreturneg[] = $extgrouptitles;
        }
        return $ndsreturneg;
    }

    public function getUserHistory($uid,$start=0,$limit=2){
        if(!intval($uid)){
            return array();
        }
        $start = intval($start);
        $limit = intval($limit);
        $queryCon = "SELECT fid,tid,first,subject,message,dateline,position FROM ".DB::table('forum_post')." WHERE authorid = '$uid' limit ".$start.','.$limit;
        $postInfo = DB::fetch_all($queryCon);
//        var_dump($postInfo);
        return $postInfo;
    }

    function viewthread_sidetop_output() {
        return  $this->getStruct();
    }

    function viewthread_sidebottom_output() {
        return  $this->getStruct();
    }

    function viewthread_bottom(){
        global $_G;
        $hisText = '
        <script>
            function showIframe(id){

                var appendHtml = \'<iframe src="'.$_G['siteurl'].'source/mrbear_userpopup/template/main.html" allowtransparency="true" frameborder="0" scrolling="no" id="np-pop-iframe" data-id="\'+id+\'" style="width:100%;height:100%;z-index:999999;position:fixed;_position:absolute;*+position:fixed;_left:100px;top:0px;left:0px;border:none;overflow:hidden;display: none" ></iframe>\';
                var appendObj = document.getElementsByTagName("body")[0];
                appendObj.innerHTML = appendObj.innerHTML+appendHtml;
            }
        </script>
        ';
        return $hisText;
    }

}

?>