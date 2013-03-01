/**
 * @author Being
 */
var n = t = 0;
function autoShow() {
	var count = $('#pic>a').length;
	n = n < count - 1 ? n + 1 : 0;
	$('#pic a').filter(":visible").stop(true, true).fadeOut(500).parent().children().eq(n).stop(true, true).fadeIn(1000);
	$('#banner').text($('#pic a').eq(n).children('img').attr('title'));
	$('#circle li').eq(n).addClass('current').siblings().removeClass('current');
}

$(function() {

	$('#circle li').hover(function() {
		clearInterval(t);
		var num = $(this).index();
		n = num;
		$('#circle li').eq(n).addClass('current').siblings().removeClass('current');
		$('#pic a:visible').stop(true, true).fadeOut(500).parent().children().eq(num).stop(true, true).fadeIn(1000);
		$('#banner').text($('#pic a').eq(n).children('img').attr('title'));
	}, function() {
		t = setInterval('autoShow()', 10000);
	});
	t = setInterval('autoShow()', 10000);

	$('.game-header span').click(function() {
		var num = $(this).index();
		$('.game-header span').removeClass('title_click');
		$(this).addClass('title_click');
		$('.new_game').hide();
		$('#newGame' + num).show();
		if (num > 0) {
			$('#newgame_more').show();
			if (num == 1) {
				//$('#newgame_more').attr("href","viewgame.do?act=categroy&GameType=������Ϸ");
				$('#newgame_more').attr("href", "javascript:targetURL('viewgame.do?act=categroy&GameTypeEN=wl')");
			}
			if (num == 2) {
				//$('#newgame_more').attr("href","viewgame.do?act=categroy&GameType=������Ϸ");
				$('#newgame_more').attr("href", "javascript:targetURL('viewgame.do?act=categroy&GameTypeEN=dj')");
			}
			if (num == 3) {
				//$('#newgame_more').attr("href","viewgame.do?act=categroy&GameType=������Ϸ");
				$('#newgame_more').attr("href", "javascript:targetURL('viewgame.do?act=categroy&GameTypeEN=xx')");
			}
			if (num == 4) {
				//$('#newgame_more').attr("href","viewgame.do?act=categroy&GameType=������Ϸ");
				$('#newgame_more').attr("href", "javascript:targetURL('viewgame.do?act=categroy&GameTypeEN=wy')");
			}
		} else {
			$('#newgame_more').hide();
		}
	});

});

