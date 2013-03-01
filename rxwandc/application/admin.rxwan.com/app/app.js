/**
 * This is the DC application.
 * 
 * @author Being
 * @version 1.1
 */
/*
 * 数据接口地址
 */


// var JOSNPATH = 'http://dc.caiyun.com/';


 Ext.onReady(function() {
			Ext.Loader.setConfig({
						enabled : true
					});
			
					
		});
		
Ext.require([
	'Ext.grid.plugin.RowEditing',
	'Ext.data.proxy.Rest',
	'Ext.layout.container.Border',
	'Ext.layout.container.Accordion',
	'Ext.toolbar.Paging',
	'Ext.data.proxy.JsonP',
	'Ext.button.Split',
	'Ext.toolbar.Spacer',
	'Ext.form.CheckboxGroup',
	'Ext.form.RadioGroup',
	'Ext.form.field.Checkbox',
	'Ext.form.field.Radio',
	'Ext.util.Cookies',
	'Ext.layout.container.Form',
	'Ext.form.field.Hidden'
	
]);


Ext.application({
	name : 'DC',
	paths : {
		'Ext.ux' : '/extjs/ux'
	},

	controllers : [

			'MainTools',
			'CenterTabPanel',
			'AccordionBar'
			,'AdminCenter'
			,'Stat'
			,'Version'
			,'Advert'
			],

	autoCreateViewport : true

	,
	launch : function() {

		Ext.get('loading').remove();
		
		
		Ext.Ajax.request({
			url : '/isLogin?key=389f3f81bf1f51796ef5b2d9a04f038d',
			//timeout : 180000,
			success : function(response, options) {
				var result = Ext.decode(response.responseText), data = result.success;
				if (data === false) {
					Ext.Msg.alert('登录提示', '您还没有登录，请先登录！', function() {
								Ext.widget('loginWindow');
							});
				} else {
					//console.warn(result.data.username);
					Ext.getCmp('logedAdminOfMainToolbar')
							.setText(result.data.username);
				}
			},
			failure : function(response, options) {
				//Ext.Msg.alert('错误', response.status);
			}
		});

		Ext.Ajax.on('requestexception', function(conn, response, operation) {
					// var view = Ext.widget('loginWindow');
					Ext.MessageBox.show({
								title : 'Error',
								msg : response.status,
								icon : Ext.MessageBox.ERROR,
								buttons : Ext.Msg.OK
							});
				});
		Ext.Ajax.on('requestcomplete', checkStatus,this);
		
			function checkStatus(conn,response,operation){
//					console.log(response.responseText);
					var result = Ext.JSON.decode(response.responseText,true);
					if(result !==null){
						if(result.status == 2){
							window.location.href = "/";	
						}
						
					}
			}
	    
		}

});
