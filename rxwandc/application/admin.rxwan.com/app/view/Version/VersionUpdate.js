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
					'Ext.Date',
					 'Ext.ux.RowExpander'
				]);
 Ext.QuickTips.init();
Ext.define('DC.view.Version.VersionUpdate', {
			extend : 'Ext.grid.Panel',
			alias : 'widget.versionUpdate',
			//requires:['Ext.toolbar.Toolbar','Ext.toolbar.Paging','Ext.grid.column.Column'],
			store : 'Version.VersionUpdate',
			frame : true,
			multiSelect: true,
			
				 
			/**
			 *
				<th width="50">ID</th>
			<th width="150">版本</th>
			<th width="150">上线时间</th>
			<th width="150">发布时间</th>
			<th width="200">版本操作</th>
			<th width="60">允许下载</th>	
			 * 
			 */
			
			initComponent : function() {
				Ext.apply(this, {
							columns : [{
										header : 'ID',
										dataIndex : 'id',
										flex : 1
									}, {
										header : '版本',
										dataIndex : 'version_title',
										flex : 1
										
									}, {
										header : '软件名称',
										dataIndex : 'soft_name',
										flex : 1
										
									}, {
										header : '上线时间',
										dataIndex : 'dateline',
										flex : 2,
										renderer:function(value){
											var time1 = new Date(value*1000);
											return Ext.Date.format(time1,'Y-m-d  H:i:s');
										}
										
									},{
										header : '发布时间',
										dataIndex : 'time_publish',
										flex : 2,
										renderer:function(value){
											var time1 = new Date(value*1000);
											return Ext.Date.format(time1,'Y-m-d  H:i:s');
										}
									},
									{
										header : '允许下载',
										dataIndex : 'is_download',
										flex : 2
										
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
											itemId : 'addVersion',
											text : '添加新版本',
											iconCls : 'icon-add',
											action : 'addVersion'
										}, '-',{
											xtype : 'button',
											itemId : 'uploadSoft',
											iconCls : 'icon-data',
											text : '文件上传',
											disabled : true,
											action : 'uploadSoft'
										}, '-',{
											xtype : 'button',
											itemId : 'softList',
											iconCls : 'icon-data',
											text : '文件列表',
											disabled : true,
											action : 'softList'
										},'-',{
											xtype: 'combo',
											fieldLabel : '软件列表选择',
						        			store : 'Version.Soft',
						        			displayField:'soft_name',
						        			valueField:'soft_id',
						        			value:'请选择软件列表'
										}, '-',{
											width : 400,
											fieldLabel : '搜索',
											labelWidth : 40,
											xtype : 'searchfield',
											store : Ext.data.StoreManager.lookup('Version.VersionUpdate')
										}]
							}, {
								xtype : 'pagingtoolbar',
								store : 'Version.VersionUpdate',
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
				/**
				 * Used to the game URL list action which selection action changed. 
				 *  if the selection is null ,the button of delete is disabled.
				 * @param {object} selModel
				 * @param {object} selections
				 */
				render:function(){
					    		var combo_box = this.down('combo');
					    		var stat_store = this.getStore();
					    		var combo_store = combo_box.getStore();
					    		var combo_data = combo_store.data;
					    		combo_box.on('change',function(){
					    			var data1 = combo_data.getByKey(combo_box.value).data;
					    			stat_store.on('beforeload',function(){
					    				Ext.apply(stat_store.proxy.extraParams,{soft_id:combo_box.value});
					    			});
					    			stat_store.load();
					    		});
					    		
		    	},
				selectionchange : function(selModel, selections) {
						//selection change action for button style.

					//this.down('#deleteVersion').setDisabled(selections.length === 0);
					this.down('#uploadSoft').setDisabled(selections.length === 0);
					this.down('#softList').setDisabled(selections.length === 0);
					//this.down('#gameListEdit').setDisabled(selections.length === 0);
					if(selections.length > 1){
					//	this.down('#deleteVersion').setText("批量删除");
						this.down('#uploadSoft').setDisabled(true);
						this.down('#softList').setDisabled(true);
					}else if(selections.length == 1){
					//	this.down('#deleteVersion').setText("删除");
						//selection change action for feature code.
					}
				}
			}
		});