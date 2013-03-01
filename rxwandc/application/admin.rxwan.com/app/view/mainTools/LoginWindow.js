/**
 * @author Being
 */
/**
 * build a window to add new game url.
 *
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.mainTools.LoginWindow', {
	extend : 'Ext.window.Window',
	alias : 'widget.loginWindow',
	title : '登陆',
	layout : 'fit',
	autoShow : true,
	modal : true,
	closable : false,
	requires:['Ext.form.Panel'],
	initComponent : function() {
		this.addEvents('create');
		Ext.apply(this, {
			items : [{
				xtype : 'form',
				items : [{
					xtype : 'textfield',
					name : 'username',
					margin : '5 5 5 5',
					fieldLabel : '账号',
					validator : function(value){
						return /^[\w+^_]\w{4,12}$/.test(value)? true : '用户名长度5-13位，且不允许特殊字符。';
					}
				}, {
					xtype : 'textfield',
					name : 'password',
					inputType : 'password',
					margin : '5 5 5 5',
					fieldLabel : '密码',
					validator : function(value){
						return /^\w{8,16}/.test(value) ? true : '密码长度8-16位。';
					}				
				}]
			}],
			buttons : [{
				text : '登陆',
				action : 'login',
				disabled:true
			}, {
				text : '重置',
				action : 'reset'
			}]

		});
		this.callParent(arguments);
	}
});
