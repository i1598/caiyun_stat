/**
 * define a rowEditing plugin for gameUrlList.
 * 
 * @author Being
 * @version 1.1
 */
 
var PlatformListRowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToMoveEditor : 1,
			autoCancel : false
		});

/**
 * define the gameUrlList to show which game URL list we had.
 */
Ext.require([
					'Ext.ux.form.SearchField',
					'Ext.String',
					 'Ext.ux.RowExpander'
				]);
 Ext.QuickTips.init();
Ext.define('DC.view.Stat.StatByCoop', {
			extend : 'Ext.grid.Panel',
			alias : 'widget.statByCoop',
			//requires:['Ext.toolbar.Toolbar','Ext.toolbar.Paging','Ext.grid.column.Column'],
			store : 'Stat.StatByCoop',
			frame : true,
			multiSelect: true,
			
				 
			
			initComponent : function() {
				Ext.apply(this, {
							columns : [{
										header : 'ID',
										dataIndex : 'id',
										flex : 1
										
									}, {
										header : '合作商名称',
										dataIndex : 'title',
										flex : 1
										
									},{
										header : 'LABEL',
										dataIndex : 'username',
										flex : 1
										
									},{
										header : '状态',
										dataIndex : 'is_stop',
										flex : 1,
										renderer:function(value){
										 	//var date1 = new Date(value*1000);
										 	//return Ext.Date.format(date1,'Y-m-d  H:i:s');
											if(value==1){
												return '停用';
											}else{
												return '启用';
											}
										}
									},{
										header : 'URL',
										flex : 2,
										dataIndex:'url'
									},{
										header : '是否付费',
										flex : 1,
										dataIndex:'is_pay',
										renderer:function(value){
											if(value==1){
												return '是';
											}else{
												return '否';
											}
										}
									},{
										header : '是否通知',
										flex : 1,
										dataIndex:'is_notice',
										renderer:function(value){
											if(value==1){
												return '是';
											}else{
												return '否';
											}
										}
									}, {
										header : '总安装量',
										dataIndex : 'total_install',
										flex : 1
										
									}, {
										header : '总卸载量',
										dataIndex : 'total_uninstall',
										flex : 1
										
									}, {
										header : '总活跃量',
										dataIndex : 'total_active',
										flex : 1
										
									},{
										header : '审核',
										flex : 1,
										dataIndex:'is_audit',
										renderer:function(value){
											if(value==1){
												return '已审核';
											}else{
												return '未审核';
											}
										}
									}],
						       // collapsible: true,
						       // animCollapse: false,
						       
						        iconCls: 'icon-grid',
						
						        renderTo: Ext.getBody()
							,dockedItems : [{
								xtype : 'toolbar',
								dock : 'top',
								items : [{
											xtype : 'button',
											itemId : 'coopAdd',
											text : '合作商添加',
											iconCls : 'icon-add',
											
											action : 'coopAdd'
										},'-',{
									xtype: 'combo',
									id : 'combo1',
									fieldLabel : '合作商类型选择',
				        			store : 'Stat.CoopCate',
				        			queryMode:'remote',
				        			displayField:'category_name',
				        			valueField:'id'
				     				,value : '9'
								},{
											xtype: 'combo',
											id :'softlistCombo',
											fieldLabel : '软件列表选择',
						        			store : 'Version.Soft',
						        			displayField:'soft_name',
						        			valueField:'soft_id',
						        			value:'1'
										},{
									xtype : 'button',
									itemId : 'coopStat',
									text : '数据概览',
									iconCls : 'icon-add',
									disabled : true,
									action : 'coopStat'
								}]
							}, {
								xtype : 'pagingtoolbar',
								store : 'Stat.StatByCoop',
								dock : 'bottom',
								displayInfo : true
							}]
							
							
						});
				this.callParent(arguments);
			},
			plugins : [
			
										
        								PlatformListRowEditing
			],
			listeners : {
				/*
				 * 根据下拉列表来制定对应的合作商模式
				 */
				render:function(){
					    		var combo_box1 = this.down('combo');
					    		var stat_store = this.getStore();
					    		var combo_store1 = combo_box1.getStore();
					    		var combo_data1 = combo_store1.data;
					    		
					    		var combo_box2 = combo_box1.nextSibling('combo');
					    		var combo_store2 = combo_box2.getStore();
					    		var combo_data2 = combo_store2.data;
					    		
					    		combo_box1.on('change',function(){
					    			var data1 = combo_data1.getByKey(combo_box1.value).data;
					    			stat_store.on('beforeload',function(){
					    				Ext.apply(stat_store.proxy.extraParams,{type:combo_box1.value});
					    			});
					    			stat_store.load();
					    		});
					    		
					    		combo_box2.on('change',function(){
					    			var data1 = combo_data2.getByKey(combo_box2.value).data;
					    			stat_store.on('beforeload',function(){
					    				Ext.apply(stat_store.proxy.extraParams,{type:combo_box1.value,softid:combo_box2.value});
					    			});
					    			stat_store.load();
					    		});

					    		
					    	}
				,selectionchange : function(selModel, selections) {
					
					this.down('#coopStat').setDisabled(selections.length === 0);
					if(Ext.typeOf(selections[0])!='undefined'){
						coopId = selections[0].data.id;
						//
						var softCombo = Ext.getCmp('softlistCombo');
						
						softId = softCombo.value;
					}
					
					//console.log(Ext.typeOf(selections[0]));
				}
					    
			}
		});