<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_mrbear_worldcuplive{

}

class plugin_mrbear_worldcuplive_forum extends plugin_mrbear_worldcuplive {
	function forumdisplay_top(){
		$outHtml = '';
		$outHtml .= '<div id="mod_top_sc" class="mod_top_sc">
    <ul id="sc_list" class="sc_list cf" style="width: 8160px; left: 0px;">
        

    </ul>
    <div id="scroll_bar" class="scroll_bar" style="width: 1000px;">
        <div id="scroll_btn" class="scroll_btn" style="width: 122.54901960784314px; left: 0px; opacity: 1;"><i></i></div>
    </div>
</div>';
        
		$outHtml .= '
        <script type="text/javascript" src="http://mat1.gtimg.com/2014/index/jquery_1.5.2.js"></script>
        <script>
            var jQuery = $.noConflict();
        </script>
        <script src="source/plugin/mrbear_worldcuplive/template/main.js"></script>
        ';
        $endChi = lang('plugin/mrbear_worldcuplive', 'end_english');
        $beginChi = lang('plugin/mrbear_worldcuplive', 'begin_english');
        $liveChi = lang('plugin/mrbear_worldcuplive', 'live_english');
        $monChi = lang('plugin/mrbear_worldcuplive', 'month_english');
        $dayChi = lang('plugin/mrbear_worldcuplive', 'day_english');
        $hourChi = lang('plugin/mrbear_worldcuplive', 'hour_english');

        $outHtml .= '
        <script>
        jQuery.ajax({
            url: "http://newdata.3g.cn/jsonInterface/index.php/Worldcup/Schedule/schedule",
            type: "GET",
            dataType: "jsonp",
            data: "t=0",
            jsonp: "cb",
            success:function(data){
                if(data&&data.schedule.length>0){
                    var scheduleData = data.schedule;
                    var itemHtml="";
                    var nowMonth = new Date().getMonth()+1;
                    var nowDay = new Date().getDate();
                    var j = 0;
                    for (var i = 0; i < scheduleData.length; i++) {
                        if(j>10){
                            break;
                        }
                        var itemMonth = scheduleData[i]["gametime"].substr(5, 2);
                        var itemDay = scheduleData[i]["gametime"].substr(8, 2);
                        if(itemMonth>nowMonth || (itemMonth==nowMonth && itemDay>=(nowDay-1))){
                            j = j+1;
                        }else{
                            continue;
                        }
                        itemHtml += "<li class=\"item\">"; 

                        itemHtml += "<div class=\"hd cf\">";
                        if(scheduleData[i]["gamestate"] == "final"){
                            itemHtml += "<span class=\"status status_3 mrbear_fl\"><i class=\"icons\"></i>'."$endChi".'</span>";
                        }else if(scheduleData[i]["gamestate"] == "begin"){
                            itemHtml += "<span class=\"status status_1 mrbear_fl\"><i class=\"icons\"></i>'."$beginChi".'</span>";
                        }else{
                            itemHtml += "<span class=\"status status_2 mrbear_fl\"><i class=\"icons\"></i>'."$liveChi".'</span>";
                        }
                        itemHtml += "<p class=\"mrbear_fl time\"><i class=\"icons\"></i>"+scheduleData[i]["gametime"].substr(5, 2)+"'."$monChi".'"+scheduleData[i]["gametime"].substr(8, 2)+"'."$dayChi".'"+scheduleData[i]["gametime"].substr(11, 2)+"'."$hourChi".'"+"</p>";
                        itemHtml += "</div>";

                        itemHtml += "<div class=\"bd\">";
                        if(scheduleData[i]["score"] == "VS"){
                            itemHtml += "<div class=\"team mrbear_fl\"><p class=\"score\">-</p><p class=\"name\">"+scheduleData[i]["host"]+"</p></div>";
                            itemHtml += "<div class=\"team mrbear_fr\"><p class=\"score\">-</p><p class=\"name\">"+scheduleData[i]["guest"]+"</p></div>";
                        }else{
                            var scorearr = scheduleData[i]["score"].split("-");
                            itemHtml += "<div class=\"team mrbear_fl\"><p class=\"score\">"+scorearr[0]+"</p><p class=\"name\">"+scheduleData[i]["host"]+"</p></div>";
                            itemHtml += "<div class=\"team mrbear_fr\"><p class=\"score\">"+scorearr[1]+"</p><p class=\"name\">"+scheduleData[i]["guest"]+"</p></div>";
                        }

                        itemHtml += "</div>";
                        itemHtml += "</li>";
                        
                    }
                    jQuery("#sc_list").append(itemHtml);

                    matchScroll.init();

                }
            }
        });
        </script>  
        ';
		return $outHtml;
	}
}