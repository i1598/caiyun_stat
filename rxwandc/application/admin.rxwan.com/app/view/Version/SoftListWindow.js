/**
 * Feature codes List window.
 * if you want to see the Feature codes list,you shold to click the Feature button.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.Version.SoftListWindow', {
			extend : 'Ext.window.Window',
			alias : 'widget.softListWindow',
			title : '软件列表',
			layout : 'fit',
			autoShow : true,
			modal : true,
			width : 800,
			height: 500,
			initComponent : function() {
				
				Ext.apply(this, {
							
							items : [{
										xtype : 'softList'
										}]
						});
				this.callParent(arguments);
			},
			listeners : {

				show : function(self) {
					//self.down('softList').getStore().load();
				}
			}
		});
