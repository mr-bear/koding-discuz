(function() {
	define("lib/jquery/easing", [], function() {
		jQuery.easing.jswing = jQuery.easing.swing, jQuery.extend(jQuery.easing, {
			def: "easeOutQuad",
			swing: function(e, t, n, r, i) {
				return jQuery.easing[jQuery.easing.def](e, t, n, r, i)
			},
			easeInQuad: function(e, t, n, r, i) {
				return r * (t /= i) * t + n
			},
			easeOutQuad: function(e, t, n, r, i) {
				return -r * (t /= i) * (t - 2) + n
			},
			easeInOutQuad: function(e, t, n, r, i) {
				return (t /= i / 2) < 1 ? r / 2 * t * t + n : -r / 2 * (--t * (t - 2) - 1) + n
			},
			easeInCubic: function(e, t, n, r, i) {
				return r * (t /= i) * t * t + n
			},
			easeOutCubic: function(e, t, n, r, i) {
				return r * ((t = t / i - 1) * t * t + 1) + n
			},
			easeInOutCubic: function(e, t, n, r, i) {
				return (t /= i / 2) < 1 ? r / 2 * t * t * t + n : r / 2 * ((t -= 2) * t * t + 2) + n
			},
			easeInQuart: function(e, t, n, r, i) {
				return r * (t /= i) * t * t * t + n
			},
			easeOutQuart: function(e, t, n, r, i) {
				return -r * ((t = t / i - 1) * t * t * t - 1) + n
			},
			easeInOutQuart: function(e, t, n, r, i) {
				return (t /= i / 2) < 1 ? r / 2 * t * t * t * t + n : -r / 2 * ((t -= 2) * t * t * t - 2) + n
			},
			easeInQuint: function(e, t, n, r, i) {
				return r * (t /= i) * t * t * t * t + n
			},
			easeOutQuint: function(e, t, n, r, i) {
				return r * ((t = t / i - 1) * t * t * t * t + 1) + n
			},
			easeInOutQuint: function(e, t, n, r, i) {
				return (t /= i / 2) < 1 ? r / 2 * t * t * t * t * t + n : r / 2 * ((t -= 2) * t * t * t * t + 2) + n
			},
			easeInSine: function(e, t, n, r, i) {
				return -r * Math.cos(t / i * (Math.PI / 2)) + r + n
			},
			easeOutSine: function(e, t, n, r, i) {
				return r * Math.sin(t / i * (Math.PI / 2)) + n
			},
			easeInOutSine: function(e, t, n, r, i) {
				return -r / 2 * (Math.cos(Math.PI * t / i) - 1) + n
			},
			easeInExpo: function(e, t, n, r, i) {
				return t == 0 ? n : r * Math.pow(2, 10 * (t / i - 1)) + n
			},
			easeOutExpo: function(e, t, n, r, i) {
				return t == i ? n + r : r * (-Math.pow(2, -10 * t / i) + 1) + n
			},
			easeInOutExpo: function(e, t, n, r, i) {
				return t == 0 ? n : t == i ? n + r : (t /= i / 2) < 1 ? r / 2 * Math.pow(2, 10 * (t - 1)) + n : r / 2 * (-Math.pow(2, -10 * --t) + 2) + n
			},
			easeInCirc: function(e, t, n, r, i) {
				return -r * (Math.sqrt(1 - (t /= i) * t) - 1) + n
			},
			easeOutCirc: function(e, t, n, r, i) {
				return r * Math.sqrt(1 - (t = t / i - 1) * t) + n
			},
			easeInOutCirc: function(e, t, n, r, i) {
				return (t /= i / 2) < 1 ? -r / 2 * (Math.sqrt(1 - t * t) - 1) + n : r / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + n
			},
			easeInElastic: function(e, t, n, r, i) {
				var s = 1.70158,
					o = 0,
					u = r;
				if (t == 0) return n;
				if ((t /= i) == 1) return n + r;
				o || (o = i * .3);
				if (u < Math.abs(r)) {
					u = r;
					var s = o / 4
				} else var s = o / (2 * Math.PI) * Math.asin(r / u);
				return -(u * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * i - s) * 2 * Math.PI / o)) + n
			},
			easeOutElastic: function(e, t, n, r, i) {
				var s = 1.70158,
					o = 0,
					u = r;
				if (t == 0) return n;
				if ((t /= i) == 1) return n + r;
				o || (o = i * .3);
				if (u < Math.abs(r)) {
					u = r;
					var s = o / 4
				} else var s = o / (2 * Math.PI) * Math.asin(r / u);
				return u * Math.pow(2, -10 * t) * Math.sin((t * i - s) * 2 * Math.PI / o) + r + n
			},
			easeInOutElastic: function(e, t, n, r, i) {
				var s = 1.70158,
					o = 0,
					u = r;
				if (t == 0) return n;
				if ((t /= i / 2) == 2) return n + r;
				o || (o = i * .3 * 1.5);
				if (u < Math.abs(r)) {
					u = r;
					var s = o / 4
				} else var s = o / (2 * Math.PI) * Math.asin(r / u);
				return t < 1 ? -0.5 * u * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * i - s) * 2 * Math.PI / o) + n : u * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * i - s) * 2 * Math.PI / o) * .5 + r + n
			},
			easeInBack: function(e, t, n, r, i, s) {
				return s == undefined && (s = 1.70158), r * (t /= i) * t * ((s + 1) * t - s) + n
			},
			easeOutBack: function(e, t, n, r, i, s) {
				return s == undefined && (s = 1.70158), r * ((t = t / i - 1) * t * ((s + 1) * t + s) + 1) + n
			},
			easeInOutBack: function(e, t, n, r, i, s) {
				return s == undefined && (s = 1.70158), (t /= i / 2) < 1 ? r / 2 * t * t * (((s *= 1.525) + 1) * t - s) + n : r / 2 * ((t -= 2) * t * (((s *= 1.525) + 1) * t + s) + 2) + n
			},
			easeInBounce: function(e, t, n, r, i) {
				return r - jQuery.easing.easeOutBounce(e, i - t, 0, r, i) + n
			},
			easeOutBounce: function(e, t, n, r, i) {
				return (t /= i) < 1 / 2.75 ? r * 7.5625 * t * t + n : t < 2 / 2.75 ? r * (7.5625 * (t -= 1.5 / 2.75) * t + .75) + n : t < 2.5 / 2.75 ? r * (7.5625 * (t -= 2.25 / 2.75) * t + .9375) + n : r * (7.5625 * (t -= 2.625 / 2.75) * t + .984375) + n
			},
			easeInOutBounce: function(e, t, n, r, i) {
				return t < i / 2 ? jQuery.easing.easeInBounce(e, t * 2, 0, r, i) * .5 + n : jQuery.easing.easeOutBounce(e, t * 2 - i, 0, r, i) * .5 + r * .5 + n
			}
		})
	}), define("lib/scrollto", ["lib/jquery/easing"], function(e) {
		"use strict";

		function n(e) {
			var t = window.scrollX || window.pageXOffset || document.documentElement.scrollLeft,
				n = window.scrollY || window.pageYOffset || document.documentElement.scrollTop,
				r = n + (window.innerHeight || document.documentElement.clientHeight),
				i = t + (window.innerWidth || document.documentElement.clientWidth),
				s = e.scrollLeft,
				o = e.scrollTop,
				u = s + (e.width || 0),
				a = o + (e.height || 0),
				f = !1,
				l = !1;
			if (s != null && s < t || u > i) f = !0;
			if (o != null && o < n || a > r) l = !0;
			return f && l ? 3 : l ? 2 : f ? 1 : 0
		}
		var t = $("html,body");
		return function(e, r, i, s) {
			function h() {
				t.animate(a, l || 1e3, "easeInOutCirc", r ?
				function e() {
					e.exed && r(), e.exed = !0
				} : null)
			}
			var o = typeof e == "number",
				u = o ? {
					top: e,
					left: e
				} : e.offset ? e.offset() : e,
				a = {},
				f, l, c;
			typeof i == "number" ? (l = i, c = typeof s == "string" ? s : "top") : typeof i == "string" ? (l = 1e3, c = i) : (l = 1e3, c = o ? "top" : "all");
			switch (c) {
			case "all":
				a.scrollTop = u.top, a.scrollLeft = u.left;
				break;
			case "top":
				a.scrollTop = u.top;
				break;
			case "left":
				a.scrollLeft = u.left;
				break;
			default:
				return
			}
			e.jquery && (a.width = e.width(), a.height = e.height()), f = n(a), e.jquery && (delete a.width, delete a.height);
			switch (f) {
			case 0:
				return r && r(), !1;
			case 1:
				return delete a.scrollTop, h(), !0;
			case 2:
				return delete a.scrollLeft, h(), !0;
			case 3:
				return h(), !0;
			default:
			}
		}
	}), "use strict", define("lib/touchToggle", [], function() {
		
	}), require(["", "", "", "", "lib/scrollto", "lib/touchToggle"], function(e, t, n, r, i, s, o) {
		"use strict;";

//		$("body").delegate("a[data-gaevent]", "click", function() {
//			var e = $(this).data("gaevent").split(":");
//			_gaq.push(["_trackEvent", e[0], $(this).attr("href"), e[e.length - 1]])
//		});
		var b = $(window),
			w = $(document),
			E = ' <div class="gotop-wrap">                            <a id="gotopBtn" href="javascript:void(0);" title="回到顶部">                                <span class="icon icon-totop"></span>                            </a>                        </div>';
		$(".container").append(E), $("#gotopBtn").click(function(e) {
			e.preventDefault(), i(0)
		}), b.scroll(function() {
			w.scrollTop() >= 500 ? $(".gotop-wrap").show() : $(".gotop-wrap").is(":visible") && $(".gotop-wrap").hide()
		})
	}), define("main", function() {})
})();