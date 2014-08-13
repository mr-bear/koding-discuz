/**
 * Created by xiongfei on 14-8-7.
 */
$(".np-mask").height($(top.window).height()).width($(top.window).width());
$(top.document.body).find("#np-pop-iframe").removeAttr("style");
$(top.document.body).find("#np-pop-iframe").attr("style","width:100%;height:100%;z-index:999999;position:fixed;_position:absolute;*+position:fixed;_left:100px;top:0px;left:0px;border:none;overflow:hidden;");
$(top.document.body).find("#np-pop-iframe").attr("allowTransparency","true");
$(top.document.body).find("#np-pop-iframe").attr("frameborder","0");
$(top.document.body).find("#np-pop-iframe").attr("frameborder","0");
$(top.document.body).find("#np-pop-iframe").attr("scrolling","no");
if ($.browser.msie && ($.browser.version == '6.0') && !$.support.style) {
    //  ie6
    if($(top.document.body).find('#np-pop-iframe').length != 0){
        $(top.document.body).find('#np-pop-iframe').css('position','absolute');
    }
    //alert("ie6")
    $(top.document.body).find("#np-pop-iframe").css({
        'height':$(top.document.body).height(),
        'width':$(top.document.body).width(),
        'top':$(top).scrollTop()
    });
    $(top).scroll(function() {
        $(top.document.body).find("#np-pop-iframe").css('top', $(top).scrollTop());
    });
}
$(top.document.body).find("#np-pop-iframe").show();/*  |xGv00|f784c6753550ffa04bcdb8c26398c241 */
