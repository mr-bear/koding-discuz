/* update: 2014-06-12 7:37 */

(function(W, D){
    Function.prototype.before = function(func) {
        var __self = this;
        return function() {
            if (func.apply(this, arguments) === false) {
                return false;
            }
            return __self.apply(this, arguments);
        }
    }
    var Fixed = W['Fixed'] = function(o){
        return new _Fixed(o);
    },
    _Fixed = function(o){
        this.id = o.id;               // obj id
        this.distance = o.distance != undefined ? o.distance : 0;  
        this.stay = o.stay != undefined ? o.stay : 0;   
        this.isTop = o.isTop != undefined ? o.isTop : false;
        this.init();
    }
    _Fixed.prototype = {
        setFixed: function (){
            var _this = this;
            var obj = D.getElementById(_this.id);
            var scrollTop = D.body.scrollTop || D.documentElement.scrollTop;
            var ie6 = !W.XMLHttpRequest;
            if(ie6){
                obj.className = obj.className;
            }
            if(!_this.isTop){  // top
                if(scrollTop > _this.distance - _this.stay){
                    obj.style.display = "block";
                    if(ie6){
                        obj.style.position = "absolute";
                    }else{
                        obj.style.position = "fixed";
                    }
                }else{
                    obj.style.display = "none";
                }
            }else{  // menu
                if(scrollTop > _this.distance - _this.stay){
                    if(ie6){
                        obj.style.top = scrollTop + _this.stay + "px";
                    }else{
                        obj.style.position = "fixed";
                        obj.style.top = _this.stay + "px";
                    }
                }else{
                    obj.style.position = "absolute";
                    obj.style.top = _this.distance + "px";
                }
            }
        },
        init: function(){
            var _this = this;
            W.onscroll = (W.onscroll || function(){}).before(function(){
                _this.setFixed();
            });
            _this.setFixed();
        }
    }
})(window, document);


(function(W, D){
    var countDown = W['countDown'] = function(o){
        return new _countDown(o);
    },
    _countDown = function(o){
        this.endTime = o.endTime;
        this.startTime = o.startTime;;
        this.dom = D.getElementById(o.domId);
        this.i = 0;
        this.flag = o.flag;
        this.init();
    };
    _countDown.prototype = {
        auto: function(){
            var _this = this;
            setTimeout(function(){
                _this.init(_this.endTime, _this.domId);
            }, 1000);
        },
        ten: function(t){
            if(t < 10){
                t = "0" + t;
            }
            return t;
        },
        init: function(){
            var _this = this,
                time = 0;
            _this.i ++;
            if(_this.flag){
                time = (new Date(_this.endTime).getTime() - new Date(_this.startTime).getTime() + 1000 * _this.i) / 1000 
            }else{
                time = (new Date(_this.endTime).getTime() - new Date(_this.startTime).getTime() - 1000 * _this.i) / 1000 
            }
            var day = _this.ten(Math.floor(time / (60 * 60 * 24))),
                hour = _this.ten(Math.floor(time /(60 * 60)) - day * 24),
                minute = _this.ten(Math.floor(time /60) - (day * 24 * 60) - (hour * 60)),
                second = _this.ten(Math.floor(time) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60));
            if( day > 0 ){
                _this.dom.innerHTML = "<span class='day'>" + day + "</span>\u5929";
            }else{
                _this.dom.innerHTML = "<span class='hour'>" + hour + "</span>\u5c0f\u65f6";
            }
            _this.auto();
        }
    }
})(window, document);


(function(jQuery){
    var scrollBar = window['scrollBar'] = function(o){
        return new _scrollBar(o);
    },
    _scrollBar = function(o){
        var _this = this;
        this.mod = jQuery("#" + o.mod);
        this.con = jQuery("#" + o.con);
        this.bar = jQuery("#" + o.bar);
        this.btn = jQuery("#" + o.btn);
        this.dis = o.direction;
        this.range = o.range || 10;
        this.modH = this.dis == "y" ? this.mod.height() : this.mod.width();
        this.conH = this.dis == "y" ? this.con.height() : this.con.width();
        this.btnH = (this.modH * this.modH / this.conH) < 20 ? 20 : (this.modH * this.modH / this.conH);
        this.TxtScroll();
    };
    _scrollBar.prototype = {
        TxtScroll: function(){
            var _this = this;
            if(_this.conH - _this.modH > 0){
                if(_this.dis == "x"){
                    _this.bar.css({"width": _this.modH + "px"});
                }else{
                    _this.bar.css({"height": _this.modH + "px"});
                }
                _this.bar.show();
                _this.startDrag(_this.btn);
            }else{
                _this.bar.hide();
            }
            if(_this.dis == "x"){
                _this.btn.css({"width": _this.btnH + "px"});
            }else{
                _this.btn.css({"height": _this.btnH + "px"});
            }
        },
        startDrag: function(btn){
            var _this = this,
                _move = false,  
                _y; 
            btn.click(function(){}).mousedown(function(e){
                _move = true;
                if(_this.dis == "x"){
                    _y = e.pageX - parseInt(btn.css("left"));
                }else{
                    _y = e.pageY - parseInt(btn.css("left"));
                }
                btn.fadeTo(20, 0.9);    
                return false;
            });
            jQuery(document).mousemove(function(e){
                if(_move){  
                    if(_this.dis == "x"){
                        _this.drag(e.pageX - _y);
                    }else{
                        _this.drag(e.pageY - _y);
                    }
                }
            }).mouseup(function(){
                _move = false;
                btn.fadeTo("fast", 1);
            });
            _this.con.bind('mousewheel DOMMouseScroll', function(e, delta){
                if(_this.bar.css('display') == 'none') return;
                e.stopImmediatePropagation();
                e.stopPropagation();
                e.preventDefault();
                var delta = parseInt(e.originalEvent.wheelDelta || - e.originalEvent.detail);
                var y = parseInt(btn.css("left"));
                if(delta < 0){
                    y += _this.range;
                }else{
                    y -= _this.range;
                }
                _this.drag(y);
                return false;
            });
        },
        drag: function (y){
            var _this = this;
            if(y <= 0){
                y = 0;
            }else if( y >= (_this.modH - _this.btnH)){
                y = (_this.modH - _this.btnH)
            }
            var t = (_this.modH - _this.conH) * y / (_this.modH - _this.btnH);
            if(_this.dis == "x"){
                _this.btn.css({"left": y});
                _this.con.css({"left": t});
            }else{
                _this.btn.css({"top": y});
                _this.con.css({"top": t});
            }
            return this;
        },
        scrollTo: function(t){
            var _this = this;
            var y = -(_this.modH - _this.btnH) * t / (_this.modH - _this.conH);
            if(y <= 0){
                y = 0;
            }else if( y >= (_this.modH - _this.btnH)){
                y = (_this.modH - _this.btnH)
            }
            var t = (_this.modH - _this.conH) * y / (_this.modH - _this.btnH);
            if(_this.dis == "x"){
                _this.btn.animate({"left": y});
                _this.con.animate({"left": t});
            }else{
                _this.btn.animate({"top": y});
                _this.con.animate({"top": t});
            }
            return this;
        }
    }
})(jQuery);

/**
 * @version 0.2
 * @author  jianminlu
 * @update  2013-07-02 11:19
 */
(function(jQuery){

    jQuery.qqScroll = {
        defaults:{
            direction:"right",  
            step:1,             
            speed:800,          
            time:4000,         
            auto:true,         
            prev:".prev",       
            next:".next",        
            inner:".inner",      
            list:".list",        
            split:".split"        
        }
    }

    jQuery.fn.qqScroll = function (options){

        var opts = jQuery.extend({}, jQuery.qqScroll.defaults, options),
            obj = jQuery(this),
            scroller = {};

            scroller.box = obj.find(opts.inner);
            scroller.list = scroller.box.find(opts.list);
            scroller.items = scroller.list.find(opts.split);
            scroller.itemSum = scroller.items.length;
            scroller.prevBtn = obj.find(opts.prev);
            scroller.nextBtn = obj.find(opts.next);
            scroller.itemWidth = scroller.items.outerWidth();
            scroller.itemHeight = scroller.items.outerHeight();

        scroller.fn = {
            start: function() {
                if (!opts.auto) {
                    return;
                }
                scroller.fn.stop();
                scroller.run = setTimeout(function() {
                    scroller.fn.goto(opts.direction);
                }, opts.time);
            },
            stop: function() {
                if (typeof(scroller.run) !== "undefined") {
                    clearTimeout(scroller.run);
                }
            },
            addControl: function() {
                if (scroller.prevBtn.length) {
                    scroller.prevBtn.bind("click", function() {
                        scroller.fn.goto(scroller.prevVal);
                    });
                }
                if (scroller.nextBtn.length) {
                    scroller.nextBtn.bind("click", function() {
                        scroller.fn.goto(scroller.nextVal);
                    });
                }
            },
            removeControl: function() {
                if (scroller.prevBtn.length) {
                    scroller.prevBtn.unbind("click");
                }
                if (scroller.nextBtn.length) {
                    scroller.nextBtn.unbind("click");
                }
            },
            goto: function(d) {
                scroller.fn.stop();
                scroller.fn.removeControl();
                scroller.box.stop(true);
                var _max;
                var _dis;
                switch (d) {
                    case "left":
                    case "top":
                        _max = 0;
                        if (d == "left") {
                            if (parseInt(scroller.box.scrollLeft(), 10) == 0) {
                                scroller.box.scrollLeft(scroller.itemSum * scroller.moveVal);
                            }
                            _dis = scroller.box.scrollLeft() - (scroller.moveVal * opts.step);

                            if (_dis < _max) {
                                _dis = _max
                            }
                            scroller.box.animate({"scrollLeft": _dis}, opts.speed, function() {
                                if (parseInt(scroller.box.scrollLeft(), 10) <= _max) {
                                    scroller.box.scrollLeft(0);
                                }
                                scroller.fn.addControl();
                            });
                        } else {
                            if (parseInt(scroller.box.scrollTop(), 10) == 0) {
                                scroller.box.scrollTop(scroller.itemSum * scroller.moveVal);
                            }
                            _dis = scroller.box.scrollTop() - (scroller.moveVal * opts.step);
                            if (_dis < _max) {
                                _dis = _max
                            }
                            scroller.box.animate({"scrollTop": _dis}, opts.speed, function() {
                                if (parseInt(scroller.box.scrollTop(), 10) <= _max) {
                                    scroller.box.scrollTop(0);
                                }
                                scroller.fn.addControl();
                            });
                        }
                        break;
                    case "right":
                    case "bottom":
                        _max = scroller.itemSum * scroller.moveVal;
                        if (d == "right") {
                            _dis = scroller.box.scrollLeft() + (scroller.moveVal * opts.step);
                            if (_dis > _max) {
                                _dis = _max
                            }
                            scroller.box.animate({"scrollLeft": _dis}, opts.speed, function() {
                                if (parseInt(scroller.box.scrollLeft(), 10) >= _max) {
                                    scroller.box.scrollLeft(0);
                                }
                            });
                        } else {
                            _dis = scroller.box.scrollTop() + (scroller.moveVal * opts.step);
                            if (_dis > _max) {
                                _dis = _max
                            }
                            scroller.box.animate({"scrollTop": _dis}, opts.speed, function() {
                                if (parseInt(scroller.box.scrollTop(), 10) >= _max) {
                                    scroller.box.scrollTop(0);
                                };
                            });
                        }
                        break;
                }
                scroller.box.queue(function() {
                    scroller.fn.addControl();
                    scroller.fn.start();
                    jQuery(this).dequeue();
                });
            },

            init: function(){

                if (scroller.itemSum <= 1) {
                    return;
                }

                if (opts.direction == "left" || opts.direction == "right") {
                    if (scroller.itemWidth * scroller.itemSum <= scroller.box.outerWidth()) {return;}
                    scroller.prevVal = "left";
                    scroller.nextVal = "right";
                    scroller.moveVal = scroller.itemWidth;
                } else {
                    if (scroller.itemHeight * scroller.itemSum <= scroller.box.outerHeight()) {return;}
                    scroller.prevVal = "top";
                    scroller.nextVal = "bottom";
                    scroller.moveVal = scroller.itemHeight;
                }

                scroller.list.append(scroller.list.html());
                if (opts.direction == "left" || opts.direction == "right") {
                    scroller.list.css({
                        width: scroller.itemWidth * scroller.itemSum * 2 + "px"
                    })
                }

                scroller.box.hover(function() {
                    scroller.fn.stop();
                }, function() {
                    scroller.fn.start();
                });
                scroller.fn.addControl();
                scroller.fn.start();
            }
        }

        scroller.fn.init();
    }
})(jQuery);

(function(jQuery){
    /**
     * @name    qqfocus    
     * @param   {Object}    
     */
    jQuery.fn.qqfocus = function(options){
        var focuser = {};
        var opts = jQuery.extend({}, {
            event: 'mouseover',     //mouseover click
            conbox: '.focus_con',   
            condot: '.focus_dot',   
            conitem: '.item',       
            dotitem: 'a',           
            current: 'current',     
            effect: 'fade',         
            speed: 1000,           
            space: 3000,            
            auto: true,             
            prev: ".prevBtn",
            next: ".nextBtn"
        }, options);
        focuser.timer = "";
        focuser.index = 0;
        focuser.last_index = 0;
        focuser.conbox = jQuery(this).find(opts.conbox);
        focuser.conitem = focuser.conbox.find(opts.conitem);
        focuser.condot = jQuery(this).find(opts.condot);
        focuser.dotitem = focuser.condot.find(opts.dotitem);
        focuser.prev = jQuery(this).find(opts.prev);
        focuser.next = jQuery(this).find(opts.next);

        focuser.fn = {
            slide: function () {
                if (focuser.index >= focuser.conitem.length){
                    focuser.index = 0;
                }
                focuser.dotitem.removeClass(opts.current).eq(focuser.index).addClass(opts.current);
                switch (opts.effect) {
                    case 'scrollx':
                        focuser.conitem.css({"float":"left"});
                        focuser.conbox.css({"position": "relative"});
                        focuser.conbox.width(focuser.conitem.length * focuser.conitem.width());
                        focuser.conbox.stop().animate({left:-focuser.conitem.width() * Math.abs(focuser.index) + 'px'}, opts.speed);
                        break;
                    case 'scrolly':
                        focuser.conitem.css({display:'block'});
                        focuser.conbox.css({"position": "relative"});
                        focuser.conbox.stop().animate({top:-focuser.conitem.height() * Math.abs(focuser.index) + 'px'}, opts.speed);
                        break;
                    case 'fade':
                        if(focuser.conbox.css('opacity') == 1){
                            focuser.conbox.css('opacity', 0);
                        }
                        focuser.conbox.animate({'opacity':1}, opts.speed / 2);
                        focuser.conitem.eq(focuser.last_index).stop().css('display', "none").end().eq(focuser.index).css('display', "block").stop();
                        break;
                    case 'none':
                        focuser.conitem.hide().eq(focuser.index).show();
                        break;
                }
                focuser.last_index = focuser.index;
                focuser.index ++;
            },
            next: function(){
                focuser.fn.stop();
                focuser.fn.slide();
                focuser.fn.play();
            },
            prev: function () {
                focuser.index = focuser.index <= -focuser.conitem.length+2 ? 8 : focuser.index - 2;
                focuser.fn.stop();
                focuser.fn.slide();
                focuser.fn.play();
            },
            stop: function(){
                clearInterval(focuser.timer);
            },
            play: function(){
                if (opts.auto) {
                    focuser.timer = setInterval(focuser.fn.slide, opts.space);
                }
            },
            init: function(){
                if (opts.effect == 'fade') {
                    focuser.conitem.eq(focuser.index).css({'display':"block"}).siblings().css({'display':"none"});
                }
                if (opts.auto){
                    focuser.fn.play();
                }else{
                    focuser.fn.stop();
                }
                focuser.dotitem.bind(opts.event, function() {
                    focuser.index = jQuery(this).index();
                    focuser.fn.stop();
                    focuser.fn.slide();
                    focuser.fn.play();
                });
                focuser.conbox.hover(focuser.fn.stop, focuser.fn.play);
                focuser.fn.slide();
                focuser.prev.bind("click", focuser.fn.prev);
                focuser.next.bind("click", focuser.fn.next);
            }
        };
        focuser.fn.init();
    }
})(jQuery);


//(function(){


    var groupTab = {
        tab: function(i){
            jQuery("#rank_tabs .hd_item").removeClass("hover").eq(i).addClass("hover");
            jQuery("#rank_tabs .bd_item").hide().eq(i).show();
        },
        init: function(){
            var _this = this;
            _this.tab(0);
            jQuery("#rank_tabs .hd_item").each(function(index, obj){
                jQuery(obj).hover(function(){
                    _this.tab(index);
                }, function(){
                    _this.tab(index);
                });
            });
        }
    };


    var matchAll = {
        hideTip: function(){
            var _btn = jQuery("#match_all_btn");
            _btn.css({"z-index":"2"});
            _btn.find(".txt").html("\u5168\u90e8\u8d5b\u7a0b");
            _btn.find("i").removeClass("up");
            jQuery("#page_tips").animate({height:"0"}, function(){
                jQuery("#page_tips").hide();
                jQuery("#page_bg").hide();
            })
        },
        showTip: function(){
            var _btn = jQuery("#match_all_btn");
            _btn.css({"z-index":"10003"});
            _btn.find(".txt").html("\u9690\u85cf\u8d5b\u7a0b");
            _btn.find("i").addClass("up");
            jQuery("#page_bg").show();
            jQuery("#page_tips").show().animate({height:"805px"}, 600);
        },
        tab: function(index){
            jQuery("#match_all .hd h2").removeClass("current").eq(index).addClass("current");
            jQuery("#match_all .tab_item").hide().eq(index).show();
        },
        init: function(){
            var _this = this;
            // tab
            _this.tab(0);
            jQuery("#match_all .hd h2").each(function(index, obj){
                jQuery(obj).hover(function(){
                    _this.tab(index);
                });
            });
            // tips
            var _h = jQuery(document).height();
            jQuery("#page_bg").css({height: _h + "px"}).bind("click", function(){
                _this.hideTip();
            });
            jQuery("#match_all_btn").bind("click", function(){
                if(!jQuery("#match_all_btn i").hasClass("up")){
                    _this.showTip();
            
                    dingYue.init();
                }else{
                    _this.hideTip();
                }
            });
        }
    }


    var matchScroll = {
        domSC: jQuery("#sc_list"),
        setWidth: function(){
            var _this = this;
            var len = _this.domSC.find(".item").length;
            var w = _this.domSC.find(".item").outerWidth();
            _this.domSC.css({width: len * w + "px"});
        },
        hover: function(){
            this.domSC.find(".item").hover(function(){
                jQuery(this).find(".ft").stop().animate({top: "-55px"});
            }, function(){
                jQuery(this).find(".ft").stop().animate({top: "0px"});
            });
        },
        bar: function(){
            var _this = this;
            var len = _this.domSC.find(".status_3").length;
            var bar = scrollBar({
                direction: "x",
                range: 10,
                mod: "mod_top_sc",
                con: "sc_list",
                bar: "scroll_bar",
                btn: "scroll_btn"
            });
            if(len){
                bar.scrollTo(170 * (len - 2));
            }
            return bar;
        },
        init: function(){
            this.setWidth();
            this.hover();
            this.bar();
        }
    };

   
    var mainNav = {
        sh: 300,
        side: jQuery("#nav_side"),
        falg: 0,
        template: function(){
            jQuery.getScript("http://mat1.gtimg.com/news/tenyears/pc/template.js");
        },
        vlist: function(obj, idx, vids, vtype){
            var _this = this;
            var vid = vids.join();
            var arrData = {
                list: []
            };
            var _adHtml = "";
            jQuery.ajax({
                url: "http://data.video.qq.com/fcgi-bin/data?tid=" + vtype + "&appid=10001009&appkey=c5a3e1529a7ba805&otype=json&idlist=" + vid,
                dataType: "jsonp",
                success: function(res){
                    if(res.results){
                   
                        if(vtype == 44 && res.results.length > 0){
                            for(var i = 0, l = res.results.length; i < l; i ++){
                                arrData.list.push(res.results[i]["fields"]);
                            }
                        }
                     
                        if(vtype == 43 && res.results.length > 0){
                            for(var i = 0, l = res.results.length; i < l; i ++){
                                //arrData.list = res.results[i]["fields"]["c_vids"];
                                arrData.list.push(res.results[i]["fields"]);
                            }
                            for(var i = 0; i < arrData.list.length; i ++){
                                arrData.list[i].c_pic_228_128 = arrData.list[i].c_pic2_url;
                                arrData.list[i].c_play_url = arrData.list[i].c_url;
                            }
                        }
             
                        if(jQuery(obj).attr("data-ad")){
                            var _src = jQuery(obj).attr("data-ad");
                            var _url = jQuery(obj).attr("data-ad-url");
                            arrData.list = arrData.list.length > 4 ? arrData.list.slice(0, 4) : arrData.list;
                            jQuery(".mod_v[tips-item=" + idx + "] .v_list").addClass("ad_list");
                            _adHtml = '<li class="ad_box"><a href="' + _url + '" target="_blank"><img src="' + _src + '"></a></li>';
                        };
                        arrData.list.length = arrData.list.length > 8 ? 8 :arrData.list.length;
                 
                        jQuery(".mod_v[tips-item=" + idx + "] .v_list").html(_adHtml + template.render("vlistHtml", arrData));

                        var _info = jQuery("#nav_info");
                        var _fh = _this.side.find(".bd").offset().top;
                        var _wh = jQuery(window).height() + jQuery(window).scrollTop();
                        var _ih = _info.outerHeight();
                        var _idx = jQuery(obj).attr("data-item");
                        var _idlm = jQuery(obj).attr("data-id");

                        var _top = jQuery(obj).offset().top > (_wh - _ih) ? (_wh - _ih - _fh) : jQuery(obj).offset().top - _fh;
                        _top = _top < -1 ? -1 : _top;
                        _this.side.find(".n_item").removeClass("current");
                        jQuery(obj).addClass("current");
                        _info.find(".mod_v").hide();
                        _info.find(".mod_v[tips-item="+_idx+"]").show();
                        _info.stop().animate({top: _top + "px"}, 50).show();

                    }
                }
            });
        },
        vlm: function(obj, lmid, idx){
            var _this = this,
                vids = [],
                vtype = "";
            var _adHtml = "";
            jQuery.ajax({
                url: "http://v.qq.com/worldcup2014/json/" + lmid + ".json",
                dataType: "script",
                success: function() {
                    var res = QZOutputJson;
                    if(res.data && res.data.length > 0){
                        var _len = res.data.length > 8 ? 8 : res.data.length
                        for(var i = 0; i < _len; i ++){
                            if(res.data[i].video_id){
                                vids.push(res.data[i].video_id);
                                vtype = 44;
                            }
                            if(res.data[i].cover_id){
                                vids.push(res.data[i].cover_id);
                                vtype = 43;
                            }
                        }
                        _this.vlist(obj, idx, vids, vtype);
                        _this.flag = 1;
                    }else{
                        if(jQuery(obj).attr("data-ad")){
                            var _src = jQuery(obj).attr("data-ad");
                            var _url = jQuery(obj).attr("data-ad-url");
                            jQuery(".mod_v[tips-item=" + idx + "] .v_list").addClass("ad_list");
                            _adHtml = '<li class="ad_box"><a href="' + _url + '" target="_blank"><img src="' + _src + '"></a></li>';
                        }else{
                            _adHtml = "";
                        }
                        jQuery(".mod_v[tips-item=" + idx + "] .v_list").html('<li class="no-data">\u6682\u65e0\u6570\u636e</li>' + _adHtml);
                        _this.flag = 0;
                    }
                    jQuery(obj).attr({"flag": _this.flag});
                }
            });
        },
        showTips: function(obj){

            var _this = this;
            var _info = jQuery("#nav_info");
            var _fh = _this.side.find(".bd").offset().top;
            var _wh = jQuery(window).height() + jQuery(window).scrollTop();
            var _ih = _info.outerHeight();
            var _idx = jQuery(obj).attr("data-item");
            var _idlm = jQuery(obj).attr("data-id");

            var _top = jQuery(obj).offset().top > (_wh - _ih) ? (_wh - _ih - _fh) : jQuery(obj).offset().top - _fh;
            _top = _top < -1 ? -1 : _top;
            _this.side.find(".n_item").removeClass("current");
            jQuery(obj).addClass("current");
            _info.find(".mod_v").hide();
            _info.find(".mod_v[tips-item="+_idx+"]").show();
            _info.stop().animate({top: _top + "px"}, 50).show();

            if(jQuery(obj).attr("data") == undefined || jQuery(obj).attr("flag") == 0){
                _this.vlm(obj, _idlm, _idx);
                jQuery(obj).attr({"flag": _this.flag});
            }
        },
        hideTips: function(){
            jQuery("#nav_info").stop().hide();
            this.side.find(".n_item").removeClass("current");
        },
        hideSide: function(){
            this.hideTips();
            jQuery("#nav_side .bd").animate({top: "45px"});
        },
        scroll: function(){
            var _st = jQuery(document).scrollTop();
            var _t = this.sh - _st + 45 < -762 ? -762 : this.sh - _st + 45;
            if(_st > this.sh){
                jQuery("#mod_nav_inner").addClass("fixed");
                if(jQuery.browser.msie && jQuery.browser.version <= 6){
                    jQuery("#nav_side .bd").css({top: "45px"});
                }else{
                    jQuery("#nav_side .hd").css({"cursor": "pointer"});
                    jQuery("#nav_side .bd").css({top: _t + "px"});
                    jQuery("#nav_side .hd i").show().addClass("up");
                }
                jQuery("#nav_info").hide();
                jQuery("#nav_side .hd .n_item").removeClass("current");
            }else{
                jQuery("#nav_side .hd i").hide().addClass("up");
                jQuery("#nav_side .hd").css({"cursor": "default"});
                jQuery("#mod_nav_inner").removeClass("fixed");
                jQuery("#nav_side .bd").css({top: "45px"});
            }
        },
        toggle: function(){
            var _this = this;
            jQuery("#nav_side .hd").bind("click", function(){
                var _i = jQuery(this).find("i");
                var _st = jQuery(document).scrollTop();
                if(_st > _this.sh){
                    if(_i.hasClass("up")){
                        _i.removeClass("up");
                        jQuery("#nav_side .bd").animate({top: "45px"});
                    }else{
                        _i.addClass("up");
                        jQuery("#nav_side .bd").animate({top: _this.sh - _st + 45 + "px"});
                        jQuery("#nav_info").hide();
                        jQuery("#nav_side .hd .n_item").removeClass("current");
                    }
                }
            });
        },
        init: function(){
            var _this = this;
            _this.toggle();
            _this.template();

            var flag = true;
            jQuery("#nav_side .n_item").each(function(index, obj){
                jQuery(obj).mouseenter(function(){
                    _this.showTips(this);
                });
            });
            jQuery("#nav_side").mouseleave(function(){
                _this.hideTips();
            });
            jQuery("#nav_info .info_close").bind("click", function(){
                _this.hideTips();
            });
        }
    }

 
    

//})(jQuery);/*  |xGv00|3d7b6fc2852160c8eeacff6e37ad6d9d */