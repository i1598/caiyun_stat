/**
 * This is login window controller
 *
 * @author Being
 * @version 1.1
 */
Ext.define('DC.controller.MainTools', {
	extend : 'Ext.app.Controller',
	views : [
			'mainTools.LoginWindow'
		,	'mainTools.MainToolbar'
	],
	store : [
			'mainTools.Login'
	],
	init : function() {
		this.control({
			'loginWindow button[action = login]' : {
				click : this.login
			},
			'loginWindow button[action = reset]' : {
				click : this.reset
			},
			'mainToolbar #logoutMenuOfMainToolbar' : {
				click : this.logout
			}
		});
	},
	/**
	 *
	 * @param {} button
	 */
	login : function(button) {
		var win = button.up('window')
		,	form = win.down('form')
		,	values = form.getValues()
		,	$frm = form.getForm();
		$frm.isValid() && form.submit({
			url : '/cgibin/dologin',
			submitEmptyText : false,
			waitMsg : 'Saving Data...',
			success : function(form, action){
				var response = action.response
				,	result = Ext.decode(response.responseText)
				,	data = result.data;
				if(result.success === true && data){
					Ext.getCmp('logedAdminOfMainToolbar').setText(values.username);
					win.close();
				} else {
					$frm.reset();
				}
			},
			failure : function(form, action){
				Ext.Msg.alert('错误','登录失败！',function(){
					$frm.reset();
				});
			}
		});
	},
	/**
	 *
	 * @param {} button
	 */
	reset : function(button) {
		var win = button.up('window')
		,	form = win.down('form').getForm();
		form.reset();
	},
	/**
	 * 退出系统
	 */
	logout : function(record){
		Ext.Ajax.request({
			url : '/logout',
			success : function(response, options){
				var result = Ext.decode(response.responseText)
				,	msgs = result.message;
				Ext.Msg.alert('提示',msgs,function(){
					window.location.href = '/';
				});
			},
			failure : function(response, options){
				Ext.Msg.alert('错误',response.status);
			}
		});
	}
}); 