/**
 * build a window to add new game url.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.Advert.AdvertAdd', {
			extend : 'Ext.window.Window',
			alias : 'widget.advertAdd',
			title : '添加广告',
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
								                	fieldLabel : '广告位选择',
								                    xtype : 'combobox',
								                    name : 'advert_position_id',
								                    margin : '5 5 5 5',
								                    allowBlank : false,
								                    autoSelect : true,
								                    store : 'Advert.AdvertPosition',
								                    valueField : 'id',
								                    displayField : 'title'
             									  },
             									  {
													xtype : 'textfield',
													name : 'info',
													margin : '5 5 5 5',
													fieldLabel : '广告名称'
												},{
													xtype : 'textfield',
													name : 'weight',
													margin : '5 5 5 5',
													fieldLabel : '广告权重'
												},{
													xtype : 'textfield',
													name : 'url',
													margin : '5 5 5 5',
													fieldLabel : '跳转地址'
												},{
													xtype : 'datefield',
													name : 'start_time',
													margin : '5 5 5 5',
													fieldLabel : '开始时间'
												},{
													xtype : 'datefield',
													name : 'end_time',
													margin : '5 5 5 5',
													fieldLabel : '开始时间'
												},{
								                	fieldLabel : '广告类型',
								                    xtype : 'combobox',
								                    name : 'type',
								                    margin : '5 5 5 5',
								                    allowBlank : false,
								                    autoSelect : true,
								                    store : 'Advert.AdvertType',
								                    valueField : 'id',
								                    displayField : 'name'
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
										                action: 'saveAdvertise'
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
