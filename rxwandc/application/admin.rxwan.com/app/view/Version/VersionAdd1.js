/**
 * build a window to add new game url.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.Version.VersionAdd1', {
			extend : 'Ext.window.Window',
			alias : 'widget.versionAdd1',
			title : '添加新版本',
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
													name : 'version_title',
													margin : '5 5 5 5',
													fieldLabel : '版本名称'
												},{
													xtype : 'textfield',
													name : 'version_name',
													margin : '5 5 5 5',
													fieldLabel : '完整版本号'
												},{
													xtype : 'datefield',
													name : 'dateline',
													margin : '5 5 5 5',
													fieldLabel : '上线时间'
												},{
								                	fieldLabel : '软件名称',
								                    xtype : 'combobox',
								                    name : 'soft_id',
								                    margin : '5 5 5 5',
								                    allowBlank : false,
								                    autoSelect : true,
								                    store : 'Version.Soft',
								                    valueField : 'soft_id',
								                    displayField : 'soft_name'
             									  },{
													xtype : 'textarea',
													name : 'features',
													margin : '5 5 5 5',
													width : 400,
													fieldLabel : '功能增减'
													
												},{
													xtype : 'textarea',
													name : 'bugfixed',
													margin : '5 5 5 5',
													width : 400,
													fieldLabel : '修复Bug'
													
												},{
													xtype : 'textarea',
													name : 'summary',
													margin : '5 5 5 5',
													width : 400,
													fieldLabel : '优化'
													
												}],
                              			buttons: [{
										                text: '保存',
										                action: 'saveVersion'
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
