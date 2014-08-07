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

            $extgrouptitles = '<p><a href="javascript:showHis('.$postuid.')"><em>test</em></a></p>';
            $ndsreturneg[] = $extgrouptitles;
        }
        return $ndsreturneg;
    }

    public function showHistory(){
        $content = '<link href="http://mat1.gtimg.com/www/coral2.0/css/coral_v9.6.css" rel="stylesheet" type="text/css" media="screen">';
        $content .= '<div class="np-popframe" style="width: 640px; left: 631.5px; top: 42px;">
            <!-- person info -->
            <div class="np-person-info">
                <img class="np-avatar" src="http://q3.qlogo.cn/g?b=qq&amp;k=s4hjVZoqMUWnzJRVzMIzjQ&amp;s=100&amp;t=1391497166">
               <span class=""><em class="np-user">大辛川菜餐饮</em><a class="" href="http://film.qq.com/vip.html" target="_blank" hidefocus="true"></a></span>               <div>
                    <span class="np-btn-area"><em class="a">中国 宁夏 银川</em></span>
                    <span class="np-btn-upvote"><em class="b">2193</em></span>
                    <span class="np-btn-reply"><em class="c">5</em></span>
                </div>
            </div>
            <div id="np-popframe-content" class="np-popframe-content" style="height: 206px;">
                <!---style="height:400px;overflow-y:scroll"-->
                <div class="scrollbar" style="height: 196px;">
                    <div class="track" style="width: 1px; height: 196px;">
                        <div class="thumb" style="height: 35px; top: 1.9876543209876545px;">
                            <div class="end"></div>
                        </div>
                        <div class="bottom"></div>
                    </div>
                </div>
                <div class="viewport" style="height: 196px;">
                    <div class="overview" style="top: -4px;">
                        <!-- timeline -->
                        <ul class="np-timeline"><li class="np-post"><div class="np-post-header"><span class="np-time">4小时前</span>发表评论</div><div class="np-post-content">    <p>地处山区、飞机无法降落、只有空投了、！！</p>    <a target="_blank" href="http://news.qq.com/a/20140807/018109.htm" class="np-link-weak">组图：解放军直升机向地震灾区空投食品</a></div><div class="np-post-footer"><span class="np-btn-upvote">2193</span><span class="np-btn-reply">11</span></div></li><li class="np-post"><div class="np-post-header"><span class="np-time">2014-7-14</span>发表评论</div><div class="np-post-content">    <p>三胖要挑事？还是有意和习总难看？做为盟国难道没领会习总发展世界和平意思？我看三胖是吓呼人！谁都不敢开第一枪。</p>    <a target="_blank" href="http://news.qq.com/a/20140714/034010.htm" class="np-link-weak">朝鲜向半岛东部海域发射100余枚火箭炮</a></div><div class="np-post-footer"><span class="np-btn-upvote">0</span><span class="np-btn-reply">0</span></div></li><li class="np-post"><div class="np-post-header"><span class="np-time">2014-7-5</span>发表评论</div><div class="np-post-content">    <p>什么情况？又是恐怖袭击？小编说清楚点！</p>    <a target="_blank" href="http://news.qq.com/a/20140705/025916.htm" class="np-link-weak">杭州公交起火细节：火势燃烧迅速 乘客砸窗逃生</a></div><div class="np-post-footer"><span class="np-btn-upvote">0</span><span class="np-btn-reply">0</span></div></li><li class="np-post"><div class="np-post-header"><span class="np-time">2014-7-3</span>发表评论</div><div class="np-post-content">    <p>建议把中国人全部集合到钓鱼岛.岸边集体撒尿，增长河水淹死小日本！</p>    <a target="_blank" href="http://news.qq.com/a/20140703/031841.htm" class="np-link-weak">日本常在末尾为4年份发动战争 中日何去何从</a></div><div class="np-post-footer"><span class="np-btn-upvote">0</span><span class="np-btn-reply">0</span></div></li></ul><!-- load more -->

                    </div>
                </div>
            </div><a class="np-btn-close"></a>
        </div>';
        $content .= '<script type="text/javascript" src="http://mat1.gtimg.com/www/js/tcomment/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="http://mat1.gtimg.com/www/coral2.0/js/scroll.js"></script>

';
        return $content;
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
//        $hisText = $this->showHistory();
//        return $hisText;
    }

    function viewthread_sidebottom_output() {
        return  $this->getStruct();
    }

    function viewthread_bottom(){
        $hisText = '<iframe src="source/plugin/mrbear_userpopup/template/main.html" allowtransparency="true" frameborder="0" scrolling="no" id="np-pop-iframe" data-id="11950765" style="width:100%;height:100%;z-index:999999;position:fixed;_position:absolute;*+position:fixed;_left:100px;top:0px;left:0px;border:none;overflow:hidden;" ></iframe>';
        $hisText .= '
        <script>
            function showHis(id){
                alert(id);
                document.getElementById("np-pop-iframe").style.display="block";
            }

        </script>';
        return $hisText;
    }

}

?>