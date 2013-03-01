/**
 * This is the accordion bar controller.
 * 
 * @author Being
 * @version 1.1
 */

Ext.define('DC.controller.AccordionBar', {
	extend : 'Ext.app.Controller',
	views : [	
					//according bar list
					
						'leftPanel.AccordionBar',
						'leftPanel.AdminPanel',
						'leftPanel.StatPanel',
						'leftPanel.VersionPanel'
						,'leftPanel.AdvertPanel'
			],
	stores : [	
					'toolbar.AccordionBar',
					'toolbar.AdminPanel',
					'toolbar.StatPanel',
					'toolbar.VersionPanel'
					,'toolbar.AdvertPanel'
				],
	models : [
					'toolbar.AccordionBar',
					'toolbar.DictionaryPanel',
					'toolbar.PanelModel'
				],
	init : function() {
		 this.control({
		 	'accordionpanel' : {
		 		itemdblclick : this.openWindow
		 	},
			 'adminPanel' : {
			 	itemdblclick : this.openWindow
			 },
			 'statPanel' : {
			 	itemdblclick : this.openWindow
			 },
			 'versionPanel' : {
			 	itemdblclick : this.openWindow
			 }
			 ,
			 'advertPanel' : {
			 	itemdblclick : this.openWindow
			 }
		 });
	},
	/*
	 * Open the tap window.
	 * @param {String/Object} views 
	 * @param {String/Object} rec 
	 * */
	openWindow : function(view,rec){
		var tab = Ext.getCmp('mainContent'),item;
		tabKey = true;
		for(var i = 0;i<tab.items.length;i++){
				if(tab.items.items[i].title == rec.get('text')){
					Ext.getCmp('mainContent').setActiveTab(i);
					tabKey = false;	
				}
		}
		
		if(tabKey == true){
			var tabComponent = Ext.create('Ext.Panel', {
				title 		: rec.get('text'),
				iconCls :rec.get('iconCls'),
				layout 	: 'fit',
				items 	: [
					{
						xtype : rec.get('widget')
					}
		    	],
				closable: true
			});
			//console.log(tabComponent);
			var centerTabPanel = Ext.getCmp('mainContent').add(tabComponent).show();
			var test1 = tabComponent.down(rec.get('widget'));
			
			//动态载入
			// test1.getStore().load();
			var type_chart = Ext.typeOf(test1.down('chart'));
			var type_data = Ext.typeOf(test1.down('advertTodayDataList'));
			if(type_chart=="object" && type_data=="object" ){
				var chart = test1.down('chart');
				var data = test1.down('advertTodayDataList');
				chart.getStore().load();
				data.getStore().load();
			}else{
				test1.getStore().load();
			}
		}
		
	}
});