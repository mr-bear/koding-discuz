<?php


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_mrbear_userpopup {
    
    function plugin_mrbear_userpopup() {
        global $_G;
        $userpop_config = $_G['cache']['plugin']['mrbear_userpopup'];
        $this->islogin = $userpop_config['islogin'];
    }   


}

class plugin_mrbear_userpopup_forum extends plugin_mrbear_userpopup {
    function getStruct() {
        global $postlist;
        global $_G;
        $userid = $_G['uid'];
        $ndsreturneg = array();
        if(empty($postlist) || !is_array($postlist)) return $ndsreturneg;

        foreach ($postlist as $post) {
            $postuid = $post['authorid'];
            $title = lang('plugin/mrbear_userpopup','history');
            if($postuid == $userid){
                $title = lang('plugin/mrbear_userpopup','myhistory');
            }
            
            $extgrouptitles = '<p><em><a class="historylogo" style="padding:5px 10px 5px 25px;" href="javascript:showIframe('.$postuid.')">'.$title.'</a></em></p>';
            $ndsreturneg[] = $extgrouptitles;
        }
        return $ndsreturneg;
    }

    function viewthread_sidetop_output() {

        return  $this->getStruct();
    }

    function viewthread_sidebottom_output() {
        //return  $this->getStruct();
    }

    function viewthread_bottom(){
        global $_G;
        $hisText = '
        <script>
            function showIframe(id){
                var appendHtml = \'<iframe src="'.$_G['siteurl'].'source/plugin/mrbear_userpopup/template/main.html" locsite="'.$_G['siteurl'].'" allowtransparency="true" frameborder="0" scrolling="no" id="np-pop-iframe" data-id="\'+id+\'" style="width:100%;height:100%;z-index:999999;position:fixed;_position:absolute;*+position:fixed;_left:100px;top:0px;left:0px;border:none;overflow:hidden;display: none" ></iframe>\';
                var appendObj = document.getElementsByTagName("body")[0];
                appendObj.innerHTML = appendObj.innerHTML+appendHtml;
            }
        </script>
        ';
        return $hisText;
    }

}

?>