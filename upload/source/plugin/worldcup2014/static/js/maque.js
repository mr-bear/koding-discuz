// JavaScript Document
var $$ = function (id) { 
return "string" == typeof id ? document.getElementById(id) : id; 
}; 
var Class = { 
create: function() { 
return function() { 
this.initialize.apply(this, arguments); 
} 
} 
} 
Object.extend = function(destination, source) { 
for (var property in source) { 
destination[property] = source[property]; 
} 
return destination; 
} 
function addEventHandler(oTarget, sEventType, fnHandler) { 
if (oTarget.addEventlistener) { 
oTarget.addEventlistener(sEventType, fnHandler, false); 
} else if (oTarget.attachEvent) { 
oTarget.attachEvent("on" + sEventType, fnHandler); 
} else { 
oTarget["on" + sEventType] = fnHandler; 
} 
}; 
var Scroller = Class.create(); 
Scroller.prototype = { 
initialize: function(idScroller, idScrollMid, options) { 
var oThis = this, oScroller = $$(idScroller), oScrollMid = $$(idScrollMid); 
this.SetOptions(options); 
this.Side = this.options.Side;//���� 
this.scroller = oScroller; //���� 
this.speed = this.options.Speed; //�ٶ� 
this.timer = null; //ʱ�� 
this.pauseHeight = 0; //���� 
this.pauseWidth = 0; //���� 
this.pause = 0; //����(��) 
this.side = 0; //���� 
//�������¹��� 
this.heightScroller = parseInt(oScroller.style.height) || oScroller.offsetHeight; 
this.heightlist = oScrollMid.offsetHeight; 
//�������ҹ��� 
this.widthScroller = parseInt(oScroller.style.width) || oScroller.offsetWidth; 
this.widthlist = oScrollMid.offsetWidth; 

//jsȡ����css���õ�height��width 
oScroller.style.overflow = "hidden"; 
oScrollMid.appendChild(oScrollMid.cloneNode(true)); 
oScrollMid.appendChild(oScrollMid.cloneNode(true)); 
addEventHandler(oScroller, "mouseover", function() { oThis.Stop(); }); 
addEventHandler(oScroller, "mouseout", function() { oThis.Start(); }); 
this.Start(); 
}, 
//����Ĭ������ 
SetOptions: function(options) { 
this.options = {//Ĭ��ֵ 
Step: 1,//ÿ�α仯��px�� 
Speed: 40,//�ٶ�(Խ��Խ��) 
Side: ["left"],//��������:"up"���ϣ�"down"���£�"left"����"right"���� 
PauseHeight: 0,//�����ͣһ�� 
PauseWidth: 0,//�����ͣһ�� 
//�����º�����һ��ʹ��ʱ��������PauseHeight��PauseWidth������ת��λ�� 
PauseStep: 2000//ͣ��ʱ��(PauseHeight��PauseWidth����0�ò�������Ч) 
}; 
Object.extend(this.options, options || {}); 
}, 
//ת�� 
Turn: function() { 
//ͨ�����÷��������������ת�� 
this.Side.push(this.Side.shift().toLowerCase()); 
}, 
//���¹��� 
ScrollUpDown: function() { 
this.pause = this.pauseHeight; 
this.scroller.scrollTop = this.GetScroll(this.scroller.scrollTop, this.heightScroller, this.heightlist, 
this.options.PauseHeight); 
this.pauseHeight = this.pause; 
var oThis = this; 
this.timer = window.setTimeout(function(){ oThis.Start(); }, this.speed); 
}, 
//���ҹ��� 
ScrollLeftRight: function() { 
this.pause = this.pauseWidth; 
//ע��:scrollLeft����1400���Զ����1400 ע�ⳤ�� 
this.scroller.scrollLeft = this.GetScroll(this.scroller.scrollLeft, this.widthScroller, this.widthlist, 
this.options.PauseWidth); 
this.pauseWidth = this.pause; 
var oThis = this; 
this.timer = window.setTimeout(function(){ oThis.Start(); }, this.speed); 
}, 
//��ȡ���ù������� 
GetScroll: function(iScroll, iScroller, ilist, iPause) { 
var iStep = this.options.Step * this.side; 
if(this.side > 0){ 
if(iScroll >= (ilist * 2 - iScroller)){ iScroll -= ilist; } 
} else { 
if(iScroll <= 0){ iScroll += ilist; } 
} 
this.speed = this.options.Speed; 
if(iPause > 0){ 
if(Math.abs(this.pause) >= iPause){ 
this.speed = this.options.PauseStep; this.pause = iStep = 0; this.Turn(); 
} else { 
this.pause += iStep; 
} 
} 
return (iScroll + iStep); 
}, 
//��ʼ 
Start: function() { 

//document.getElementById("test").innerHTML+=sTurn+","; 
//�������� 
switch (this.Side[0].toLowerCase()) { 
case "right" : 

if(this.widthlist < this.widthScroller) return; 
this.side = -1; 
this.ScrollLeftRight(); 
break; 
case "left" : 

if(this.widthlist < this.widthScroller) return; 

this.side = 1; 
this.ScrollLeftRight(); 
break; 
case "down" : 
if(this.heightlist < this.heightScroller) return; 
this.side = -1; 
this.ScrollUpDown(); 
break; 
case "up" : 
default : 
if(this.heightlist < this.heightScroller) return; 
this.side = 1; 
this.ScrollUpDown(); 
} 
}, 
//ֹͣ 
Stop: function() { 
clearTimeout(this.timer); 
} 
}; 