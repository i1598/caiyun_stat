/**
 * build a window to add new game url.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.Stat.CateAdd', {
			extend : 'Ext.window.Window',
			alias : 'widget.cateAdd',
			title : '添加合作商类型',
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
													xtype : 'textfield',
													name : 'category_name',
													margin : '5 5 5 5',
													 allowBlank : false,
													fieldLabel : '合作商类型'
												}],
                              			buttons: [{
										                text: '保存',
										                action: 'saveCate'
										            }, {
										                text: '取消',
										                scope: this,
										                handler: this.close
										            }]		 
                              					 
                              			/*		 
                              			buttons : [{
										text : '保存',
										scope : this,
										handler:function(){  
					                                        var form = this.down('form').getForm();
					                                        
					                                       
					                                        if(form.isValid()){  
					                                            form.submit({  
					                                                        url:'/operator/create',
					                                                        scope:this,
					                                                        waitMsg:'正在上传', 
					                                                        
					                                                        success:function(fp,o){  
					                                                            this.close();
					                                                            Ext.Msg.show(  
					                                                                    {  
					                                                                        title:'提示信息',  
					                                                                        msg:'文件上传成功', 
					                                                                        buttons:Ext.Msg.OK  
					                                                                    }  
					                                                            ) 
					                                                        } 
					                                                         
					                                             })  
					                                        }  
					                                          
                                   		 }  
										}, {
											text : '取消',
											scope : this,
											handler : this.close
										}]
										*/
									}]
							

						});
				this.callParent(arguments);
			}
		});
