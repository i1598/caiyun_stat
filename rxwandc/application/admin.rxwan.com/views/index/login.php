<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../../extjs/resources/css/ext-all.css">
		<link rel="stylesheet" type="text/css" href="../../extjs/style.css">
		<script type="text/javascript" src="../../extjs/ext-all.js"></script>
		<script type="text/javascript">
			Ext.onReady(function() {
				Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
				Ext.QuickTips.init();
				function handler1() {
							form.form.submit({
								url : '/login',
								submitEmptyText : false,
								waitMsg : 'Saving Data...',
								success : function(form, action) {
									console.log(action.result.type);
									 switch(action.result.type){
										 case 3:
										 	Ext.Msg.alert('Warning','密码错误');
										 	break;
										 case 4:
										 	 Ext.Msg.alert('Warning', '登录成功');
											 window.location.href = "/";
											 break;
									 }
								},
								failure : function(form, action) {
									switch (action.failureType) {
										case Ext.form.Action.CLIENT_INVALID:
											Ext.Msg.alert('Error Warning', "用户名或密码不能为空");
											break;
										case Ext.form.Action.CONNECT_FAILURE:
											Ext.Msg.alert('Error Warning', 'network abort！');
											break;
										case Ext.form.Action.SERVER_INVALID:
											Ext.Msg.alert('Error Warning', "The form's values format error！");
											//simple.form.reset();
											break;
									}
								}
							});
						}
				Ext.form.Field.prototype.msgTarget = 'side';
				var form = Ext.create('Ext.form.Panel', {
					layout : 'absolute',
					defaultType : 'textfield',
					border : false,
					items : [{
						fieldLabel : 'username',
						fieldWidth : 60,
						msgTarget : 'side',
						allowBlank : false,
						blankText : 'Account input not null !',
						x : 5,
						y : 25,
						name : 'username',
						anchor : '-5' // anchor width by percentage
					}, {
						id:'passwdInput',
						fieldLabel : 'password',
						fieldWidth : 60,
						inputType : 'password',
						msgTarget : 'side',
						allowBlank : false,
						blankText : 'Account input not null !',
						x : 5,
						y : 55,
						name : 'password',
						anchor : '-5' // anchor width by percentage
						,listeners : {  
		                    specialKey : function(field, e) {  
		                        if (e.getKey() == Ext.EventObject.ENTER) {//响应回车  
		                            //queryHandler();//处理回车事件  
		                            //comName.selectText();//处理回车事件后选中输入框的文字  
		                       		//console.log(33);
		                        	handler1();
		                        }  
	                    }  
               		 }  
					}]
					
				});
				
				var win = Ext.create('Ext.window.Window', {
					title : 'Login RX Manager',
					closable : false,
					width : 400,
					height : 200,
					minWidth : 300,
					minHeight : 100,
					layout : 'fit',
					plain : true,
					items : form,
					buttons : [{
						text : 'Login',
						handler:handler1
						
					}, {
						text : 'Cancel',
						handler : function() {
							form.form.reset();
						}
					}]
					,listeners:{
						beforeshow:function(){
						//	console.log(passwd);
						}
					}
				});
				win.show();
			});
		</script>
		<link type="text/css" rel="stylesheet" href="../../extjs/login.css" />
		<title>RX Management Background</title>
	</head>
	<body></body>
</html>

