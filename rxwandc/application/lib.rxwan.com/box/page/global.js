//富媒体广告
function show_ad() {
	
		var x = 0;
        var y = 4;
       	ad_num = parseInt(Math.random() * (x - y + 1) + y);

	
	var ad_url = "http://ad.rxwan.com/geturl.php?num="+ad_num;
	var ad_swf = "http://ad.rxwan.com/getad.php?num="+ad_num;
		
	
	document.write("<div id='msg_win' style='display:block;top:900px;visibility:visible;opacity:1;'>");
	document.write("<div class='icos'><a id='msg_min' title='最小化' href='javascript:void 0'>_</a><a id='msg_close' title='关闭' href='javascript:void 0'>×</a></div>");
	document.write("<div id='msg_title'>今日推荐</div>");
	document.write("<div id='msg_content'>");
	
	document.write('<button onclick="window.open(\''+ ad_url +'\')" style="width:320px;height:270px;background:transparent;border:o;padding:0;cursor:hand">');
	//document.write("<a style='display:block' href='"+ ad_url +"'>");
	document.write("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0' width='320' height='270'> ");
	document.write("<param name='movie' value='"+ ad_swf +"'>");
	document.write('<param name="wmode" value="transparent">');
	document.write('<param name="quality" value="high" />');
	document.write("<embed src='"+ ad_swf +"' width='320' height='270' type='application/x-shockwave-flash' quality='high' wmode='transparent'/>");
	document.write("</object>");
	//document.write("</a>");
	document.write('</button>');
	document.write("</div>");
	document.write("</div>");
	
	
	var Message = {
		set : function() {
			var set = this.minbtn.status == 1 ? [0, 1, 'block', this.char[0], '最小化'] : [1, 0, 'none', this.char[1], '恢复'];
			this.minbtn.status = set[0];
			this.win.style.borderBottomWidth = set[1];
			this.content.style.display = set[2];
			this.minbtn.innerHTML = set[3];
			this.minbtn.title = set[4];
			this.win.style.top = this.getY().top;
		},
		close : function() {
			this.win.style.display = 'none';
			window.onscroll = null;
			document.getElementById('msg_content').innerHTML = ""
		},
		setOpacity : function(x) {
			var v = x >= 100 ? '' : 'Alpha(opacity=' + x + ')';
			this.win.style.visibility = x <= 0 ? 'hidden' : 'visible';
			this.win.style.filter = v;
			this.win.style.opacity = x / 100;
		},
		show : function() {
			clearInterval(this.timer2);
			var me = this, fx = this.fx(0, 100, 0.1), t = 0;
			this.timer2 = setInterval(function() {
				t = fx();
				me.setOpacity(t[0]);
				if (t[1] == 0) {
					clearInterval(me.timer2)
				}
			}, 10);
		},
		fx : function(a, b, c) {
			var cMath = Math[(a - b) > 0 ? "floor" : "ceil"], c = c || 0.1;
			return function() {
				return [a += cMath((b - a) * c), a - b]
			}
		},
		getY : function() {
			var d = document, b = document.body, e = document.documentElement;
			var s = Math.max(b.scrollTop, e.scrollTop);
			var h = /BackCompat/i.test(document.compatMode) ? b.clientHeight : e.clientHeight;
			var h2 = this.win.offsetHeight;
			return {
				foot : s + h + h2 + 'px',
				top : s + h - h2 + 'px'
			}
		},
		moveTo : function(y) {
			clearInterval(this.timer);
			var me = this, a = parseInt(this.win.style.top) || 0;
			var fx = this.fx(a, parseInt(y));
			var t = 0;
			this.timer = setInterval(function() {
				t = fx();
				me.win.style.top = t[0] + 'px';
				if (t[1] == 0) {
					clearInterval(me.timer);
					me.bind();
				}
			}, 10);
		},
		bind : function() {
			var me = this, st, rt;
			window.onscroll = function() {
				clearTimeout(st);
				clearTimeout(me.timer2);
				me.setOpacity(0);
				st = setTimeout(function() {
					me.win.style.top = me.getY().top;
					me.show();
				}, 600);
			};
			window.onresize = function() {
				clearTimeout(rt);
				rt = setTimeout(function() {
					me.win.style.top = me.getY().top
				}, 100);
			}
		},
		init : function() {
			function $(id) {
				return document.getElementById(id)
			};
			this.win = $('msg_win');
			var set = {
				minbtn : 'msg_min',
				closebtn : 'msg_close',
				title : 'msg_title',
				content : 'msg_content'
			};
			for (var Id in set) {
				this[Id] = $(set[Id])
			};
			var me = this;
			this.minbtn.onclick = function() {
				me.set();
				this.blur();
			};
			this.closebtn.onclick = function() {
				me.close()
			}; 
			this.char = (!!(document.all && navigator.userAgent.indexOf('Opera') === -1)) ? ['0', '2', 'r'] : ['─', '〓', '×'];
			this.minbtn.innerHTML = this.char[0];
			this.closebtn.innerHTML = this.char[2];
			setTimeout(function() {
				me.win.style.display = 'block';
				/*me.win.style.top=me.getY().foot;*/
				me.win.style.top = me.getY().top;
				me.moveTo(me.getY().top);
			}, 0);
			return this;
		}
	};
	Message.init();
}

//show_ad();
	
//$('#msg_content').load('http://ad.rxwan.com/getad.php');
// $.getScript('http://ad.rxwan.com/getad.php', function(data) {
  // $('#msg_content').html(data);
// });



document.write('<div class="duilian duilian_left">');
document.write('<div class="duilian_con"><a href="http://youxi.baidu.com/yxpm/pm.jsp?pid=11034300340_902875"><img src="http://img.rxwan.com/ad/skycn_ad/jiuzhousanguo_ad.gif"/></a></div>');
document.write('<a href="#" class="duilian_close">关闭</a>');
document.write('</div>');
document.write('<div class="duilian duilian_right">');
document.write('<div class="duilian_con"><a href="http://youxi.baidu.com/yxpm/pm.jsp?pid=11034300340_902875"><img src="http://img.rxwan.com/ad/skycn_ad/jiuzhousanguo_ad.gif"/></a></div>');
document.write('<a href="#" class="duilian_close">关闭</a>');
document.write('</div>');


$(document).ready(function(){

	// var duilian = $("div.duilian");
	// var duilian_close = $("a.duilian_close");
// 	
	// var window_w = $(window).width();
	// if(window_w>1000){duilian.show();}
	// $(window).scroll(function(){
		// var scrollTop = $(window).scrollTop();
		// duilian.stop().animate({top:scrollTop+260});
	// });
	// duilian_close.click(function(){
		// $(this).parent().hide();
		// return false;
	// });
	
});
