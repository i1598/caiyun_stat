/**
 * @author Being
 */
var childdiv=$('<div/>');
childdiv.attr('style', 'position:absolute;width:500px;height:400px;left:50%;top:50%;margin-left:-250px;margin-top:-200px;z-index:10000;background-color: #ffffff');
childdiv.append($("#gameid").attr("data"));
$("body").append(childdiv);
//alert($("#gameflash_warp").html());
