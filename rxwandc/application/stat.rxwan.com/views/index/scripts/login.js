var xmlhttp;
function checkLogin1() {
	
	/**
	 * 这里是提取form表单的值
	 * @type 
	 */
	var username = document.getElementById('username').value,
		 password = document.getElementById('password').value,
		 info = document.getElementById('info');
		
	
		
	
	
		// 1. 创建请求对象
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		// 2. 设置回调函数
		xmlhttp.onreadystatechange = myback;

		// 3. 初始化请求对象
		xmlhttp.open("GET", "/login?username="+username+"&password="+password, true);
		
		//  xmlhttp.open("POST","/login",true);
		//  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		//  xmlhttp.send("username="+username+"&password="+password+"&captcha="+captcha);
		 xmlhttp.setRequestHeader("X-Requested-With",'a');
		 
		// true表示异步的意思，GET表示请求方式，load_data.html表示请求地址
		// 4. 发送请求
		xmlhttp.send();
		
		return false;
}
	// 回调函数是跟踪Ajax请求的
	function myback() {
		// alert("ok");
		// 当请求状态为4时，且响应状态为200时表示ajax请求成功返回
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			// 获取响应结果
			
			 eval('var result = '+xmlhttp.responseText+';');
			
			
			
			if(result.success===true){
				
				window.location='/';
			}else{
				info.innerHTML=result.message+"!";
				
			}
			
			
		}
	}
	

	
