/**
 * Created by xiongfei on 14-8-7.
 */
var userid = $(top.document.body).find("#np-pop-iframe").attr("data-id");
var siteurl = $(top.document.body).find("#np-pop-iframe").attr("locsite");
userid = (typeof userid == "undefined") ? "0" : userid;
var lastid = 0;
var reqnum = 10;
var NPAPI = {
    fetch: function(url, event, data, type) {
        if(userid == 0 || userid == ''){
            return ;
        }
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: url,
            success: function(res) {
                if (res.errCode == 0) {
                    //console.log(res)
                    NPPOP.render(res.data);
                } else {
//                    $("body").trigger("np-error", res.errCode);
                    var errhtml = '<li style="background:#fff;text-align:center; padding:50px 0; color:#666;">\u6682\u65e0\u8bc4\u8bba\uff01</li>';
                    $("ul.np-timeline").append(errhtml);
                    $(".np-load-more-loading").remove();
                }
            },
            error: function() {
                //alert(res);
                var errhtml = '<li style="background:#fff;text-align:center; padding:50px 0; color:#666;">\u6682\u65e0\u8bc4\u8bba\uff01</li>';
                $("ul.np-timeline").append(errhtml);
                $(".np-load-more-loading").remove();
            }
        });
    },
    usercomment: function() {
        var url = siteurl+'plugin.php?id=mrbear_userpopup:history&uid='+userid+'&start='+lastid+'&limit='+reqnum;
        this.fetch(url, "np-user-comment-success", {}, "GET");
    }
};
var NPPOP = {

    initialize: function() {
        this.firt = 0;

        this.popup();
        this.load();
        $(".np-load-more").click(this.load).html("\u52a0\u8f7d\u66f4\u591a");
        $(".np-btn-close").click(this.close);
        $(".np-mask").click(this.close);
        $("ul.np-timeline").delegate("a.np-btn-spread", "click", this.showLongtext);


        var _this = this;
        $('#np-popframe-content').delegate('.np-con-img','click',function(){
            $(this).unbind().bind('load',function(){
                _this.scroll.tinyscrollbar_update('relative');
            });
            var srcUrl =$(this).attr('src');
            if(/\/150$/.test(srcUrl)){
                srcUrl = srcUrl.replace(/\/150$/,'/500')
            }else{
                srcUrl = srcUrl.replace(/\/500$/,'/150')
            }

            $(this).attr('src', srcUrl );

            //_this.iframeHeight();
        });

    },

    scroll: $('#np-popframe-content').tinyscrollbar({
        axis: "y",
        sizethumb: 35
    }),

    close: function() {
        $(top.document.body).find("#np-pop-iframe").remove();
    },

    render: function(data) {
        var html = "";
        var max = 0;
        lastid = data.last;
        for (i = 0, len = data.comments.length; i < len; i++) {
            if (data.comments[i].checkstatus == 0  && data.comments[i].content != "undefined" && data.comments[i].content != null) {

                if(data.comments[i].content == ""){
                    data.comments[i].content = '...';
                }
                data.comments[i].time = this.formatTime(data.comments[i].time);
                html += this.template(data.comments[i]);
            }
        }

        if(!data.comments.length){
            html = '<li style="background:#fff;text-align:center; padding:50px 0; color:#666;">\u6682\u65e0\u8bc4\u8bba\uff01</li>';
        }

        $("ul.np-timeline").append(html);

        if (data.retnum < reqnum || data.total <= reqnum) {
            $(".np-load-more-loading").remove();
        } else {
            $(".np-load-more-loading").removeClass("np-load-more-loading");
            $(".np-load-more").css("display", "block");
        }
        if (this.firt === 0) {


            var userinfo = data.usermeta;
            userinfo.region = userinfo.region.replace(/:/g, " ");
            if ($.trim(userinfo.region) == "") {
                userinfo.region = "\u672a\u77e5";
            }
            if ($.trim(userinfo.nick) == "") {
                userinfo.region = "\u672a\u77e5";
            }

            $(".np-person-info img.np-avatar").attr("src", siteurl+"uc_server/avatar.php?uid="+data.usermeta.userid+"&size=middle");

            $(".np-person-info .np-user").html(userinfo.nick);
            $(".np-person-info .np-btn-group em").html(userinfo.groupname);
            $(".np-person-info .np-btn-area em").html(userinfo.region);
            $(".np-person-info .np-btn-reply em").html(userinfo.commentnum);
//            $(".np-person-info .np-btn-upvote em").html(userinfo.friends);

            this.firt = 1;
        }
        this.hideLongtext();
        this.scroll.tinyscrollbar_update("relative");
    },


    popup: function() {
        try {

            if ($(top.window).width() < 640) {
                $('.np-popframe').width($(top.window).width());
                $('.np-popframe').css('left', 0);
            } else {
                $('.np-popframe').width(640);
                $('.np-popframe').css('left', $(top.window).width() / 2 - 320);
            }

            $('.np-popframe').css('top', $(top.window).height());
            $('.np-popframe').stop().animate({
                top: $(top.window).height() * 0.1
            }, 300).show();


            $('.np-popframe-content').height($(top.window).height() * 0.8 - 130);
            $('.np-popframe-content .viewport').height($(top.window).height() * 0.8 - 140);
            $('.np-popframe-content .scrollbar').height($(top.window).height() * 0.8 - 140);
        } catch (err) {

        }
    },


    load: function() {
        $(".np-load-more").addClass("np-load-more-loading");
        NPAPI.usercomment();
    },


    resize: function() {
        try {
            $(".np-mask").height($(top.window).height()).width($(top.window).width());

            $('.np-popframe').height($(top.window).height() * 0.8);
            $('.np-popframe').css('top', $(top.window).height() * 0.1);
            $('.np-popframe-content').height($('.np-popframe').height() - $(".np-person-info").height() - 10);
            $('.np-popframe-content .viewport').height($('.np-popframe').height() - $(".np-person-info").height() - 20);
            $('.np-popframe-content .scrollbar').height($('.np-popframe').height() - $(".np-person-info").height() - 20);

            if ($(top.window).width() < 640) {
                $('.np-popframe').width($(top.window).width());
                $('.np-popframe').css('left', 0);
            } else {
                $('.np-popframe').width(640);
                $('.np-popframe').css('left', $(top.window).width() / 2 - 320);
            }
        } catch (err) {

        }


        NPPOP.scroll.tinyscrollbar_update("relative");
    },

    showLongtext: function() {
        $(this).prev().css('height', '');
        $(this).hide();
        NPPOP.scroll.tinyscrollbar_update("relative");
    },


    hideLongtext: function() {
        var _this = this;
        var posts = $('.np-popframe ul.np-timeline .np-post-content p');
        var height = 24;
        posts.each(function(index, element) {
            if ($(this).height() > height * 3 && !$(this).next('.spreadMoreBtn').length) {
                $(this).css({
                    height: height * 2,
                    'overflow': 'hidden'
                });
                $(this).after('<a class="np-btn np-btn-spread"><i class="np-icon np-icon-spread"></i></a>');
            }
        });
    },


    formatTime: function(time) {
        var number = (new Date()) / 1000 - time;
        if (number < 1) {
            return "\u521a\u521a";
        }
        if (number < 60) {
            return Math.floor(number) + "\u79d2\u949f\u524d";
        }
        if (number < 3600) {
            return Math.floor(number / (60)) + "\u5206\u949f\u524d";
        }
        if (number < 24 * 3600) {
            return Math.floor(number / (3600)) + "\u5c0f\u65f6\u524d";
        }
        if (number < 24 * 3600 * 7) {
            return Math.floor(number / (24 * 3600)) + "\u5929\u524d";
        }
        var d = new Date(time * 1000),
            s = d.getFullYear() + "-";
        s += (d.getMonth() + 1) + "-";
        s += d.getDate();
        return s;
    },

    template: function(item) {


        var imgHtml = '';

        var titHtml ='';

        titHtml= item.targetinfo.title.replace(/</g,'&lt;');
        titHtml= titHtml.replace(/>/g,'&gt;');

        if(item.picture != undefined && item.picture.length){
            imgHtml = '<div><img class="np-con-img" src="'+ item.picture[0].url +'/150" />' +  '</div>';
        }
        html = "";
        html += '<li class="np-post">';
        html += '<div class="np-post-header">';

//        var str = item.content.replace(/\[img]([\s\S]*?)\[\/img\]\s?\s?/ig, '......');
//        var str = item.content.replace(/\[size]([\s\S]*?)\[\/size\]\s?\s?/ig, '......');
//        str = str.replace(/\[img]([\s\S]*?)\[\/img\]\s?\s?/ig, '...');
        var str = item.content;
        if(item.parent == 1){
            html += '<span class="np-time">' + item.time + '</span>\u53d1\u8868\u4e3b\u9898'+ ' : <a target="_blank" href="' + item.targetinfo.url + '" class="np-link-weak">' + titHtml + '</a>';
            html += '</div>'
                + '<div class="np-post-content">'
                + '    <p>' + str + '</p>'
                + '</div>'
                + '<div class="np-post-footer">'
                + '<span class="np-btn-upvote">' + item.up + '</span><span class="np-btn-reply">' + item.repnum + '</span>'
                + '</div>'
                + '</li>';
        }else{
            html += '<span class="np-time">' + item.time + '</span>\u53d1\u8868\u8bc4\u8bba';
            html += '</div>'
                + '<div class="np-post-content">'
                + '    <p>' + str + '</p>'
                + imgHtml
                + '    <a target="_blank" href="' + item.targetinfo.url + '" class="np-link-weak">' + titHtml + '</a>'
                + '</div>'
                + '<div class="np-post-footer">'
                + '<span class="np-btn-upvote">' + item.up + '</span><span class="np-btn-reply">' + item.repnum + '</span>'
                + '</div>'
                + '</li>';
        }



        return html;
    }
};
$(top.window).resize(NPPOP.resize);
NPPOP.initialize();




/*  |xGv00|c0800b7c46362307ec52435d63987e00 */