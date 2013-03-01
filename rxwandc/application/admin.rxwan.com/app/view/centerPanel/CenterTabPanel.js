/**
 * Welcome to C-talk Data center.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.centerPanel.CenterTabPanel' ,{
    extend	: 'Ext.tab.Panel',
	alias	: 'widget.centerTabPanel',
	id 		: 'mainContent',
	enableTabScroll: true,
	margins : '5 5 5 0',
   	items: [{
            title: '每日统计',
            iconCls: 'icon listWelcome',
            xtype: 'statPerDay',
            closable: true
        }]
    ,
			listeners : {
				
				beforerender : function(self) {
					self.down('statPerDay').getStore().load();
				}
			}
});