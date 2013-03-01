/**
 * build a window to add new game url.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.Version.SoftwareAdd', {
			extend : 'Ext.window.Window',
			alias : 'widget.softwareAdd',
			title : '添加新软件',
			layout : 'fit',
			autoShow : true,
			modal : true,
			require:['Ext.form.Panel'],
			initComponent : function() {
				this.addEvents('create');
				Ext.apply(this, {
							
							items : [{
										xtype : 'form',
										//width : 500,
										//heidth:500,
										items : [{
													xtype : 'textfield',
													name : 'soft_name',
													margin : '5 5 5 5',
													fieldLabel : '软件名称'
												}],
                              			buttons: [{
										                text: '保存',
										                action: 'saveSoftware'
										            }, {
										                text: '取消',
										                scope: this,
										                handler: this.close
										            }]		 
                              					 
                              			
									}]
							

						});
				this.callParent(arguments);
			}
		});
