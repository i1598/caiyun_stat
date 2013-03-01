/**
 * This is the DC application.
 *
 * @author Being
 * @version 1.1
 */

/*
Ext.Loader.setPath({
	'Ext' : '/extjs/src',
	'Ext.ux' : '/extjs/ux'
});


Ext.require([
	'Ext.window.Window',
	'Ext.form.Panel'
]);
*/
Ext.onReady(function(){
	var win = Ext.create('Ext.window.Window',{
		title : '管理员登录',
		autoShow : true,
		closeable : false,
		items : [{
			xtype : 'form',
			listeners:{
				fieldvaliditychange:function(){
					var values = this.getValues(),
					 password = Ext.getCmp('password'),
					 username = Ext.getCmp('username');
					
					if(password.validateValue(values.password)&&username.validateValue(values.username)){
						Ext.getCmp('login').setDisabled(false);
					}
				}
			},
			items :[{
				xtype : 'textfield',
				id:'username',
				name : 'username',
				margin : '5 5 5 5',
				fieldLabel : '账号',
				validator : function(value){
					return /^[\w+^_]\w{4,12}$/.test(value)? true : '用户名长度5-13位，且不允许特殊字符。';
				}
				
			}, {
				xtype : 'textfield',
				id : 'password',
				name : 'password',
				inputType : 'password',
				margin : '5 5 5 5',
				fieldLabel : '密码',
				validator : function(value){
					return /^\w{3,16}/.test(value) ? true : '密码长度8-16位。';
				}
			}],
			buttons : [{
				text : '登陆',
				id : 'login',
				disabled:true
			}, {
				text : '重置',
				id : 'reset'
			}]
		}],
	listeners : {
		show : function(){
			Ext.get('loading').remove();
			
		},
		render : function(){
			var login = Ext.getCmp('login')
			,	reset = Ext.getCmp('reset')
			, 	username = Ext.getCmp('username')
			, 	password = Ext.getCmp('password')
			,	resetValue = reset.up('form').getForm()
			,	form = login.up('form').getForm(),
			form1 = this.down('form').getForm();
			
			
			login.on('click',function(){
				
				form.submit({
					
					url : '/login',
					submitEmptyText : false,
					waitMsg : 'Saving Data...',
					success : function(form, operation){
						
						
						
						
						var result = Ext.decode(operation.response.responseText)
						;
						if(result.success===true){
							Ext.Msg.alert('提示',result.message);
							window.location = '/';
						}else{
							Ext.Msg.alert('错误',result.message);
							form.reset();
						}
					},
					failure : function(form, operation){
						//var result = Ext.decode(operation.response.responseText);
						
						Ext.Msg.alert('错误','登陆失败');
						form.reset();
					}
					
				});
				
				
				
			});
			reset.on('click',function(){
				form.reset();
				//login.setDisabled(false);
				
			});
			//var btn = this.up('window').down('form');
			
		}
		
	
		
			
		
	}
	});
});
