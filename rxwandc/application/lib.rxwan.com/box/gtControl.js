var gtActive = 'null';
var isNo = false;
try{
	gtActive = new ActiveXObject("Gtgame.Gtwebctrl.1");
}catch(e){
	isNo = true;
}
function controlVerify(){
	var type=arguments[2];
	var resid=arguments[1];
	var t = getInst();
	/*
	if(gtInstalled()){
	}else{
		arguments[0].href="softdown?resid="+resid+"&type="+type
	}
	*/
	if(t==''||t=='no active'){
		arguments[0].href="softdown?resid="+resid+"&type="+type;
	}
}

function chkGame(){
	var obj = arguments[0];
	var resid = arguments[1];
	try{
		var ins = getInst();
		if(ins=='new_active'){
			gtActive.Refresh();
			var gameState = gtActive.GetGameState(resid);
			if(gameState==1){
				$(obj).css('background','url(http://pic.youxigt.com/images/page/games/game_one_ico.jpg) no-repeat -3px -720px').unbind().attr('href','javascript:;');
			}else if(gameState==2){
				$(obj).css('background','url(http://pic.youxigt.com/images/page/games/game_one_ico.jpg) no-repeat -3px -660px').unbind().attr('href','ggtd://resid='+resid+'&type=rungame');
			}
		}
	}catch(e){
		
	}
}

function getInst() {
	 var  rt = '';
	 if(typeof(gtActive)=='undefined'){
		rt='no active';
	 }else if(isNo){
		rt='no active';
	 }else{
		try{
			var isDown = gtActive.GetGameState('403DD8F9A180E3BCFADCD128308C201D'); 
			if(typeof(isDown)=='undefined'){
				rt = 'old active';
			}else{
				//rt = isDown;
				rt = 'new_active';
			}
		}catch(e){
			rt = 'old active';
			if(typeof gtActive == "string" || gtActive instanceof String){
				rt = 'no active';
			}
		}
	 }
	 return rt;
}

function getHardwareInfo(){
	var  rt = '';
	 if(typeof(gtActive)=='undefined'){
		rt='no active';
	 }else if(isNo){
		rt='no active';
	 }else{
		 try{
			 var hobj = gtActive.GetInterface('GameState.dll'); 
			 rt = hobj.GetHardWareInfo();
		 }catch(e){
			 rt = 'no new active';
		 }
	 }
	 return rt;
}

function getSoftVersion(){
	var v = 0;
	var act = isNewActive();
	if(act=='new active'){
		try{
			v = gtActive.GetInterface('GameState.dll').getversion();
		}catch(e){
			
		}
	}
	return v;
}

function isNewActive(){
	var  rt = 'n';
	 if(typeof(gtActive)=='undefined'){
		rt='no active';
	 }else if(isNo){
		rt='no active';
	 }else{
		 try{
			 var hobj = gtActive.GetInterface('GameState.dll'); 
			 hobj.getversion();
			 rt = 'new active';
		 }catch(e){
			 rt = 'no new active';
		 }
	 }
	 return rt;
}

function gtInstalled(){
	var exed=true;
	//if(navigator.userAgent.indexOf("MSIE") !=-1){	
		try{
			var cfv = new ActiveXObject("Gtgame.Gtwebctrl.1");
			//alert(cfv.CheckGTInfo());
			
			var info = cfv.CheckGTInfo();
			var version = cfv.GetGTVersion();
			if(info<0){
				exed=false;
			}else{
				exed=true;
			}
			
		}
		catch(e){
			//alert(e);
			exed=false;
		}
	//}else{
	//	exed=true;
	//}
	return exed;
}

