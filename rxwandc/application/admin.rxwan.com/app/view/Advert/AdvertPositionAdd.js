/**
 * build a window to add new game url.
 * 
 * @author Being
 * @version 1.1
 */
 //创建数据模型
//		Ext.regModel('LabelInfo', {
//		    fields: [{name: 'id'},{name: 'name'}]
//		});
		//定义组合框中显示的数据源
//		var labelStore = Ext.create('Ext.data.Store',{
//			model : 'DC.model.Advert.LabelInfo',
//			data : [
//				{id:'1',name:'首页面'},
//				{id:'2',name:'子页面'}
//			]
//		});
Ext.define('DC.view.Advert.AdvertPositionAdd', {
			extend : 'Ext.window.Window',
			alias : 'widget.advertPositionAdd',
			title : '添加广告位',
			layout : 'fit',
			autoShow : true,
			modal : true,
			require:['Ext.form.Panel'],
			initComponent : function() {
				this.addEvents('create');
				Ext.apply(this, {
							items : [{
										xtype : 'form',
										items : [ {
													xtype : 'textfield',
													name : 'label',
													margin : '5 5 5 5',
													fieldLabel : 'LABEL'
												},{
													xtype : 'textfield',
													name : 'title',
													margin : '5 5 5 5',
													fieldLabel : '广告位信息'
												},{
								                	fieldLabel : '广告位置选择',
								                    xtype : 'combobox',
								                    name : 'advert_zone_id',
								                    margin : '5 5 5 5',
								                    allowBlank : false,
								                    autoSelect : true,
								                    store : 'Advert.AdvertZone',
								                    valueField : 'id',
								                    displayField : 'name'
             									  }],
                              			buttons: [{
										                text: '保存',
										                action: 'savePosition'
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
