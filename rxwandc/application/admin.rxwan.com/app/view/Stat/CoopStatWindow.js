/**
 * Feature codes List window.
 * if you want to see the Feature codes list,you shold to click the Feature button.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.Stat.CoopStatWindow', {
			extend : 'Ext.window.Window',
			alias : 'widget.coopStatWindow',
			title : '统计列表',
			layout : 'fit',
			autoShow : true,
			modal : true,
			width : 800,
			height: 500,
			initComponent : function() {
				
				Ext.apply(this, {
							
							items : [{
										xtype : 'coopStat'
										}]
						});
				this.callParent(arguments);
			},
			listeners : {

				show : function(self) {
					self.down('coopStat').getStore().load();
				}
			}
		});
