/*!
Code name:  resizeMyBrowser
Build:      1.0.9 (February 23, 2011)
Author:     Chen Luo
Website:    http://resizeMyBrowser.com
*/
window.onload = fnAll;
function fnAll() {
	var ab = document.getElementById("currentLabel");
	var V = 1;
	var z = document.getElementById("divMes");
	var an = 0;
	var n = screen.availWidth;
	var x = screen.availHeight;
	var N;
	var Z;
	var E = 0;
	var F = document.getElementById("switch");
	var ac = document.getElementById("IObutton");
	var aa = 1;
	F.onmouseup = function () {
		if (aa == 1) {
			if (typeof window.outerWidth == "undefined") {
				if (E == 0) {
					var ap = h();
					var i = o();
					window.resizeTo(400, 400);
					N = 400 - h();
					Z = 400 - o();
					window.resizeTo(ap + N, i + Z);
					E = 1
				}
			}
			ac.style.left = "35px";
			e();
			aa = 0
		} else {
			ac.style.left = "0";
			g();
			aa = 1
		}
		z.style.visibility = "hidden"
	};
	var b = document.getElementById("inner");
	var D = document.getElementById("outer");
	b.onclick = function () {
		ac.style.left = "0";
		g();
		aa = 1;
		z.style.visibility = "hidden"
	};
	D.onclick = function () {
		if (typeof window.outerWidth == "undefined") {
			if (E == 0) {
				var ap = h();
				var i = o();
				window.resizeTo(400, 400);
				N = 400 - h();
				Z = 400 - o();
				window.resizeTo(ap + N, i + Z);
				E = 1
			}
		}
		ac.style.left = "35px";
		e();
		aa = 0;
		z.style.visibility = "hidden"
	};
	var y = document.getElementById("div1").getElementsByTagName("ul");
	for (var af = 0; af < y.length; af++) {
		y[af].onmouseover = f;
		y[af].onmouseout = function () {
			this.style.backgroundColor = "#ddedd1";
			this.style.border = "1px solid #ddedd1"
		};
		y[af].onclick = ak
	}
	var ag = document.getElementById("div2").getElementsByTagName("ul");
	for (var af = 0; af < ag.length; af++) {
		ag[af].onmouseover = f;
		ag[af].onmouseout = function () {
			this.style.backgroundColor = "#d3d8ed";
			this.style.border = "1px solid #d3d8ed"
		};
		ag[af].onclick = ak
	}
	var q = document.getElementById("div3").getElementsByTagName("ul");
	for (var af = 0; af < q.length; af++) {
		q[af].onmouseover = f;
		q[af].onmouseout = function () {
			this.style.backgroundColor = "#cdedf6";
			this.style.border = "1px solid #cdedf6"
		};
		q[af].onclick = ak
	}
	var Q = document.getElementById("div4").getElementsByTagName("ul");
	for (var af = 0; af < Q.length; af++) {
		Q[af].onmouseover = f;
		Q[af].onmouseout = function () {
			this.style.backgroundColor = "#f1f2c8";
			this.style.border = "1px solid #f1f2c8"
		};
		Q[af].onclick = ak
	}
	function f() {
		this.style.backgroundColor = "#f5f5f5";
		this.style.border = "1px dashed silver"
	}
	function h() {
		return (typeof window.innerWidth == "undefined") ? document.documentElement.offsetWidth : window.innerWidth
	}
	function o() {
		return (typeof window.innerHeight == "undefined") ? document.documentElement.offsetHeight : window.innerHeight
	}
	window.resizeToInnerOnload = function (ap, at) {
		if (E == 0) {
			if (h() == 400 & o() == 400) {
				window.resizeTo(400, 400)
			}
			N = 400 - h();
			Z = 400 - o();
			var i = parseInt(ap) + N;
			var ar = parseInt(at) + Z;
			var aq = i;
			var au = ar;
			if (aq > n) {
				aq = n
			}
			if (au > x) {
				au = x
			}
			window.resizeTo(aq, au);
			E = 1
		}
	};
	function j() {
		if (typeof window.outerWidth == "undefined") {
			if (E == 1) {
				return document.documentElement.offsetWidth + N
			} else {
				return "N/A"
			}
		} else {
			return window.outerWidth
		}
	}
	function r() {
		if (typeof window.outerHeight == "undefined") {
			if (E == 1) {
				return document.documentElement.offsetHeight + Z
			} else {
				return "N/A"
			}
		} else {
			return window.outerHeight
		}
	}
	function g() {
		I.innerHTML = h();
		R.innerHTML = o();
		ab.innerHTML = "Inner"
	}
	function e() {
		I.innerHTML = j();
		R.innerHTML = r();
		ab.innerHTML = "Outer"
	}
	window.resizeToOuterOnload = function (i, ar) {
		var ap = parseInt(i);
		var aq = parseInt(ar);
		if (ap > n) {
			ap = n
		}
		if (aq > x) {
			aq = x
		}
		window.resizeTo(ap, aq)
	};
	var I = document.getElementById("cWidth");
	var R = document.getElementById("cHeight");
	I.innerHTML = h();
	R.innerHTML = o();
	window.onresize = function () {
		if (an == 0) {
			z.style.visibility = "hidden"
		}
		an = 0;
		if (aa == 1) {
			g()
		} else {
			e()
		}
	};
	if (window.iw) {
		if (h() == 0) {
			setTimeout("window.resizeToInnerOnload(window.iw,window.ih)", 300);
			alert('Because of the "resizeTo" bug of Google Chrome, resizeMyBrowser can\'t always works properly on it.\n\nBy contrast, you can get perfect experience with Safari, Firefox or even IE.\n\nSorry for the inconvenience. Hope Google can fix it somedays in the future.')
		} else {
			window.resizeToInnerOnload(window.iw, window.ih)
		}
		g();
		l(window.iw, window.ih)
	}
	if (window.ow) {
		window.resizeToOuterOnload(window.ow, window.oh);
		ac.style.left = "35px";
		e();
		aa = 0;
		l(window.ow, window.oh)
	}
	if (window.maxinner) {
		window.resizeTo(n, x);
		g()
	}
	if (window.maxouter) {
		window.resizeTo(n, x);
		ac.style.left = "35px";
		e();
		aa = 0
	}
	function K() {
		var i = window.open(window.location.href, "newwindow", "width=400,height=400,resizable=yes,scrollable=yes,scrollbars=yes,toolbar=yes,location=yes");
		i.moveTo(0, 0);
		i.iw = RegExp.$1;
		i.ih = RegExp.$2
	}
	function k() {
		var i = window.open(window.location.href, "newwindowouter", "width=400,height=400,resizable=yes,scrollable=yes,scrollbars=yes,toolbar=yes,location=yes");
		i.moveTo(0, 0);
		i.ow = RegExp.$1;
		i.oh = RegExp.$2
	}
	function T() {
		var i = window.open(window.location.href, "newwindowmaxinner", "width=400,height=400,resizable=yes,scrollable=yes,scrollbars=yes,toolbar=yes,location=yes");
		i.moveTo(0, 0);
		i.maxinner = true
	}
	function s() {
		var i = window.open(window.location.href, "newwindowmaxouter", "width=400,height=400,resizable=yes,scrollable=yes,scrollbars=yes,toolbar=yes,location=yes");
		i.moveTo(0, 0);
		i.maxouter = true
	}
	function l(ap, i) {
		an = 1;
		if (aa == 1) {
			if (ap == h() && i > o()) {
				z.innerHTML = '<p>Resized to <span class="highlight">' + h() + " x " + o() + '</span> instead of <span class="highlight">' + ap + " x " + i + '</span> because the available <span class="highlight">height</span> of your screen isn\'t enough.</p>';
				z.style.visibility = "visible"
			} else {
				if (ap > h() && i == o()) {
					z.innerHTML = '<p>Resized to <span class="highlight">' + h() + " x " + o() + '</span> instead of <span class="highlight">' + ap + " x " + i + '</span> because the available <span class="highlight">width</span> of your screen isn\'t enough.</p>';
					z.style.visibility = "visible"
				} else {
					if (ap > h() && i > o()) {
						z.innerHTML = '<p>Resized to <span class="highlight">' + h() + " x " + o() + '</span> instead of <span class="highlight">' + ap + " x " + i + '</span> because the available <span class="highlight">width and height</span> of your screen isn\'t enough.</p>';
						z.style.visibility = "visible"
					}
				}
			}
		} else {
			if (aa == 0) {
				if (ap == j() && i > r()) {
					z.innerHTML = '<p>Resized to <span class="highlight">' + j() + " x " + r() + '</span> instead of <span class="highlight">' + ap + " x " + i + '</span> because the available <span class="highlight">height</span> of your screen isn\'t enough.</p>';
					z.style.visibility = "visible"
				} else {
					if (ap > j() && i == r()) {
						z.innerHTML = '<p>Resized to <span class="highlight">' + j() + " x " + r() + '</span> instead of <span class="highlight">' + ap + " x " + i + '</span> because the available <span class="highlight">width</span> of your screen isn\'t enough.</p>';
						z.style.visibility = "visible"
					} else {
						if (ap > j() && i > r()) {
							z.innerHTML = '<p>Resized to <span class="highlight">' + j() + " x " + r() + '</span> instead of <span class="highlight">' + ap + " x " + i + '</span> because the available <span class="highlight">width and height</span> of your screen isn\'t enough.</p>';
							z.style.visibility = "visible"
						}
					}
				}
			}
		}
	}
	var L = document.getElementById("overlay");
	function ai() {
		L.style.visibility = (L.style.visibility == "visible") ? "hidden" : "visible"
	}
	var t = document.getElementById("div4");
	var am = document.getElementById("p1");
	var X = document.getElementById("p1s");
	var ao = document.getElementById("p1d");
	var al = document.getElementById("p2");
	var A = document.getElementById("p2s");
	var M = document.getElementById("p2d");
	var aj = document.getElementById("p3");
	var m = document.getElementById("p3s");
	var u = document.getElementById("p3d");
	var ah = document.getElementById("p4");
	var Y = document.getElementById("p4s");
	var a = document.getElementById("p4d");
	function d(i, ap, aq) {
		if (V == 1) {
			am.style.display = "block";
			X.innerHTML = i + " x " + ap;
			ao.innerHTML = aq;
			H.style.display = "block";
			document.cookie = "p1sHTML=" + i + " x " + ap + ";expires=" + O();
			document.cookie = "p1dHTML=" + aq + ";expires=" + O();
			V++
		} else {
			if (V == 2) {
				al.style.display = "block";
				A.innerHTML = i + " x " + ap;
				M.innerHTML = aq;
				H.style.display = "block";
				document.cookie = "p2sHTML=" + i + " x " + ap + ";expires=" + O();
				document.cookie = "p2dHTML=" + aq + ";expires=" + O();
				V++
			} else {
				if (V == 3) {
					aj.style.display = "block";
					m.innerHTML = i + " x " + ap;
					u.innerHTML = aq;
					H.style.display = "block";
					document.cookie = "p3sHTML=" + i + " x " + ap + ";expires=" + O();
					document.cookie = "p3dHTML=" + aq + ";expires=" + O();
					V++
				} else {
					if (V == 4) {
						ah.style.display = "block";
						Y.innerHTML = i + " x " + ap;
						a.innerHTML = aq;
						H.style.display = "block";
						document.cookie = "p4sHTML=" + i + " x " + ap + ";expires=" + O();
						document.cookie = "p4dHTML=" + aq + ";expires=" + O();
						V++
					}
				}
			}
		}
	}
	function P() {
		if (V > 4) {
			ah.style.display = "none";
			if (ad("p4sHTML") != "") {
				document.cookie = "p4sHTML=";
				document.cookie = "p4dHTML="
			}
			V--
		} else {
			if (V > 3) {
				aj.style.display = "none";
				if (ad("p3sHTML") != "") {
					document.cookie = "p3sHTML=";
					document.cookie = "p3dHTML="
				}
				V--
			} else {
				if (V > 2) {
					al.style.display = "none";
					if (ad("p2sHTML") != "") {
						document.cookie = "p2sHTML=";
						document.cookie = "p2dHTML="
					}
					V--
				} else {
					if (V > 1) {
						am.style.display = "none";
						H.style.display = "none";
						if (ad("p1sHTML") != "") {
							document.cookie = "p1sHTML=";
							document.cookie = "p1dHTML="
						}
						V--
					}
				}
			}
		}
	}
	function W(i) {
		var ap = /^[1-9][0-9][0-9][0-9]?$/;
		return ap.test(i)
	}
	function S(i) {
		var ap = /^.{1,25}$/;
		return ap.test(i)
	}
	var p = document.getElementById("overlayMes");
	var J = document.forms[0].elements.width;
	var U = document.forms[0].elements.height;
	var v = document.forms[0].elements.descriptions;
	J.onblur = function () {
		if (W(J.value)) {
			p.style.display = "none";
			this.style.borderColor = "silver"
		} else {
			p.innerHTML = "Input a number between 100 and 9999.";
			p.style.display = "block";
			this.style.borderColor = "#ff4a00";
			this.focus()
		}
	};
	J.onkeyup = function () {
		if (W(J.value)) {
			p.style.display = "none";
			this.style.borderColor = "silver"
		}
	};
	U.onblur = function () {
		if (W(U.value)) {
			p.style.display = "none";
			this.style.borderColor = "silver"
		} else {
			p.innerHTML = "Input a number between 100 and 9999.";
			p.style.display = "block";
			this.style.borderColor = "#ff4a00";
			this.focus()
		}
	};
	U.onkeyup = function () {
		if (W(U.value)) {
			p.style.display = "none";
			this.style.borderColor = "silver"
		}
	};
	v.onblur = function () {
		if (S(v.value)) {
			p.style.display = "none";
			this.style.borderColor = "silver"
		} else {
			p.innerHTML = "Input description up to 25 characters.";
			p.style.display = "block";
			this.style.borderColor = "#ff4a00";
			this.focus()
		}
	};
	v.onkeyup = function () {
		if (S(v.value)) {
			p.style.display = "none";
			this.style.borderColor = "silver"
		} else {
			p.innerHTML = "Input description up to 25 characters.";
			p.style.display = "block";
			this.style.borderColor = "#ff4a00";
			this.focus()
		}
	};
	var w = document.getElementById("add");
	w.onclick = function () {
		if (V > 4) {
			alert("Excuse but you can only add up to 4 presets.\n\nIf you want something more powerful, please try out our Safari Extension.\nhttp://resizeSafari.com")
		} else {
			ai()
		}
	};
	var H = document.getElementById("delete");
	H.onclick = function () {
		P()
	};
	var c = document.getElementById("create");
	c.onclick = function () {
		if (!W(J.value)) {
			p.innerHTML = "Input a number between 100 and 9999.";
			p.style.display = "block";
			J.style.borderColor = "#ff4a00";
			J.focus()
		} else {
			if (!W(U.value)) {
				p.innerHTML = "Input a number between 100 and 9999.";
				p.style.display = "block";
				U.style.borderColor = "#ff4a00";
				U.focus()
			} else {
				if (!S(v.value)) {
					p.innerHTML = "Input description up to 25 characters.";
					p.style.display = "block";
					v.style.borderColor = "#ff4a00";
					v.focus()
				} else {
					ai();
					var ap = document.forms[0].elements.width.value;
					var aq = document.forms[0].elements.height.value;
					var i = document.forms[0].elements.descriptions.value;
					d(ap, aq, i)
				}
			}
		}
		return false
	};
	var ae = document.getElementById("cancel");
	ae.onclick = function () {
		ai();
		p.style.display = "none";
		this.style.borderColor = "silver";
		return false
	};
	function G() {
		if (document.cookie != "") {
			if (ad("p1sHTML") != "" && ad("p1sHTML") != undefined) {
				am.style.display = "block";
				X.innerHTML = ad("p1sHTML");
				ao.innerHTML = ad("p1dHTML");
				H.style.display = "block";
				V = 2
			}
			if (ad("p2sHTML") != "" && ad("p2sHTML") != undefined) {
				al.style.display = "block";
				A.innerHTML = ad("p2sHTML");
				M.innerHTML = ad("p2dHTML");
				H.style.display = "block";
				V = 3
			}
			if (ad("p3sHTML") != "" && ad("p3sHTML") != undefined) {
				aj.style.display = "block";
				m.innerHTML = ad("p3sHTML");
				u.innerHTML = ad("p3dHTML");
				H.style.display = "block";
				V = 4
			}
			if (ad("p4sHTML") != "" && ad("p4sHTML") != undefined) {
				ah.style.display = "block";
				Y.innerHTML = ad("p4sHTML");
				a.innerHTML = ad("p4dHTML");
				H.style.display = "block";
				V = 5
			}
		}
	}
	G();
	function ad(ar) {
		var ap = document.cookie.split("; ");
		for (var aq = 0; aq < ap.length; aq++) {
			if (ar == ap[aq].split("=")[0]) {
				return ap[aq].split("=")[1]
			}
		}
	}
	function O() {
		var i = new Date();
		i.setMonth(i.getMonth() + 12);
		return i.toGMTString()
	}
	var C = window.location;
	function B(i) {
		var ap = /resizemybrowser\.com/i;
		return ap.test(i)
	}
	if (!B(C)) {
		setTimeout(function () {
			alert("Thanks for using My application!\nCopyright 2010 All rights reserved, Chen Luo.");
			location.href = "http://resizemybrowser.com"
		}, 3000)
	}
	function ak() {
		z.style.visibility = "hidden";
		var aK = this.getElementsByTagName("li");
		var aE = aK[0].innerHTML;
		var aF = /(\d*) x (\d*)/;
		if (aF.test(aE)) {
			if (aa == 1) {
				var aI = h();
				var ar = o();
				window.resizeTo(400, 400);
				var aD = h();
				var i = o();
				if (E == 0) {
					if (aD == aI && i == ar && (!(aD == RegExp.$1 && i == RegExp.$2))) {
						K();
						z.innerHTML = "It seems that your browser does not allow to resize current window. As a workaround, a new popup window has been opened.";
						z.style.visibility = "visible"
					} else {
						N = 400 - aD;
						Z = 400 - i;
						E = 1
					}
				}
				if (E == 1) {
					var aJ = parseInt(RegExp.$1) + N;
					var at = parseInt(RegExp.$2) + Z;
					var az = aJ;
					var aG = at;
					if (az > n) {
						az = n;
						window.moveTo(0, 0)
					}
					if (aG > x) {
						aG = x;
						window.moveTo(0, 0)
					}
					var aw = j();
					var aC = r();
					window.resizeTo(az, aG);
					var au = j();
					var aA = r();
					var aq = h();
					var ay = o();
					if ((aq == aD && ay == i) && (!(aq == aJ && ay == at)) && (aw == au && aC == aA)) {
						K();
						z.innerHTML = "It seems that your browser does not allow to resize current window. As a workaround, a new popup window has been opened.";
						z.style.visibility = "visible"
					} else {
						l(RegExp.$1, RegExp.$2)
					}
				}
			} else {
				e();
				var ap = j();
				var aL = r();
				var aB = RegExp.$1;
				var aH = RegExp.$2;
				if (aB > n) {
					aB = n;
					window.moveTo(0, 0)
				}
				if (aH > x) {
					aH = x;
					window.moveTo(0, 0)
				}
				window.resizeTo(aB, aH);
				var ax = j();
				var av = r();
				if ((ap == ax && aL == av) && (!(ax == RegExp.$1 && av == RegExp.$2)) && (!(RegExp.$1 >= n && RegExp.$2 >= x && ax >= n && av >= x)) && (!(RegExp.$2 >= x && av >= x && RegExp.$1 == ax)) && (!(RegExp.$1 >= n && RegExp.$2 == av))) {
					k();
					z.innerHTML = "It seems that your browser does not allow to resize current window. As a workaround, a new popup window has been opened.";
					z.style.visibility = "visible"
				} else {
					l(RegExp.$1, RegExp.$2)
				}
			}
		} else {
			window.moveTo(0, 0);
			window.resizeTo(n, x);
			if (!(j() == n && r() == x)) {
				if (aa == 1) {
					T()
				} else {
					s()
				}
			}
		}
	}
};