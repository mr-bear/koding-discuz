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
this.Side = this.options.Side;
this.scroller = oScroller; 
this.speed = this.options.Speed; 
this.timer = null; 
this.pauseHeight = 0; 
this.pauseWidth = 0; 
this.pause = 0; 
this.side = 0; 

this.heightScroller = parseInt(oScroller.style.height) || oScroller.offsetHeight; 
this.heightlist = oScrollMid.offsetHeight; 

this.widthScroller = parseInt(oScroller.style.width) || oScroller.offsetWidth; 
this.widthlist = oScrollMid.offsetWidth; 


oScroller.style.overflow = "hidden"; 
oScrollMid.appendChild(oScrollMid.cloneNode(true)); 
oScrollMid.appendChild(oScrollMid.cloneNode(true)); 
addEventHandler(oScroller, "mouseover", function() { oThis.Stop(); }); 
addEventHandler(oScroller, "mouseout", function() { oThis.Start(); }); 
this.Start(); 
}, 
 
SetOptions: function(options) { 
this.options = { 
Step: 1,
Speed: 40, 
Side: ["left"],
PauseHeight: 0,
PauseWidth: 0, 

PauseStep: 2000 
}; 
Object.extend(this.options, options || {}); 
}, 

Turn: function() { 

this.Side.push(this.Side.shift().toLowerCase()); 
}, 

ScrollUpDown: function() { 
this.pause = this.pauseHeight; 
this.scroller.scrollTop = this.GetScroll(this.scroller.scrollTop, this.heightScroller, this.heightlist, 
this.options.PauseHeight); 
this.pauseHeight = this.pause; 
var oThis = this; 
this.timer = window.setTimeout(function(){ oThis.Start(); }, this.speed); 
}, 

ScrollLeftRight: function() { 
this.pause = this.pauseWidth; 

this.scroller.scrollLeft = this.GetScroll(this.scroller.scrollLeft, this.widthScroller, this.widthlist, 
this.options.PauseWidth); 
this.pauseWidth = this.pause; 
var oThis = this; 
this.timer = window.setTimeout(function(){ oThis.Start(); }, this.speed); 
}, 

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

Start: function() { 


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

Stop: function() { 
clearTimeout(this.timer); 
} 
}; 