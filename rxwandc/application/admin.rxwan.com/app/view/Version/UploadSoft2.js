/**
 * build a window to add new game url.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.Version.UploadSoft2', {
			extend : 'Ext.window.Window',
			alias : 'widget.uploadSoft2',
			title : '上传文件',
			layout : 'fit',
			autoShow : true,
			modal : true,
			require:['Ext.form.Panel'],
			initComponent : function() {
				this.addEvents('create');
				Ext.apply(this, {
							items : [{
										xtype : 'form',
										items : [{
													xtype : 'hidden',
													name : 'version_id',
													margin : '5 5 5 5',
													fieldLabel : '版本id'
													
												},{
													xtype : 'hidden',
													name : 'typeon',
													margin : '5 5 5 5',
													fieldLabel : '版本类型'
													
												},{  
					                                   xtype:'filefield',  
					                                   //与后台定义的File属性名必须相同  
					                                   name:'upload_file',  
					                                   fieldLabel:'上传',  
					                                   allowbBlank:false,  
					                                   margin : '5 5 5 5',
					                                   msgTarget:'side',  
					                                   buttonText:'选择文件'
					                                    
                              					 }],
                              			buttons: [{
										                text: '保存',
										                action: 'doSoftUpload'
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
