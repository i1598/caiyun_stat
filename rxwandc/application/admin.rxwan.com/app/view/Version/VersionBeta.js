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
Ext.define('DC.view.Versions.Soft.SoftBeta', {
			extend : 'Ext.grid.Panel',
			alias : 'widget.softBeta',
			//requires:['Ext.toolbar.Toolbar','Ext.toolbar.Paging','Ext.grid.column.Column'],
			store : 'Versions.Soft.SoftBeta',
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
										header : '版本类型',
										hideable :true,
										dataIndex : 'typeon',
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
									}
									,{header:"版本操作",
										xtype : 'actioncolumn',
										width :200,
										items : [{
												icon:"/extjs/images/up.gif",
												tooltip:'上传',
												width:40,
												handler:function(grid,rowIndex,colIndex){
													//获取被操作的数据记录
													var rec = grid.getStore().getAt(rowIndex);
													
													
													
													var hidden1 = Ext.widget('uploadVersion').down('form').down('hidden'),
													hidden2 = hidden1.nextSibling('hidden');
													//设置这两个隐藏域的值
													hidden1	.setValue(rec.get('id'));
													hidden2	.setValue(rec.get('typeon'));
												}
										},{
												icon:"/extjs/images/pub_ok.gif",
												tooltip:'开启升级',
												handler:function(grid,rowIndex,colIndex){
													//获取被操作的数据记录
													var rec = grid.getStore().getAt(rowIndex);
													
													
													
													var hidden1 = Ext.widget('uploadVersion').down('form').down('hidden'),
													hidden2 = hidden1.nextSibling('hidden');
													//设置这两个隐藏域的值
													hidden1	.setValue(rec.get('id'));
													hidden2	.setValue(rec.get('typeon'));
												}
										},{
												icon:"/extjs/images/cacel_pub.gif",
												tooltip:'关闭升级',
												handler:function(grid,rowIndex,colIndex){
													//获取被操作的数据记录
													var rec = grid.getStore().getAt(rowIndex);
													
													
													
													var hidden1 = Ext.widget('uploadVersion').down('form').down('hidden'),
													hidden2 = hidden1.nextSibling('hidden');
													//设置这两个隐藏域的值
													hidden1	.setValue(rec.get('id'));
													hidden2	.setValue(rec.get('typeon'));
												}
										}]	
									
									},{
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
											itemId : 'deleteVersion',
											iconCls : 'icon-data',
											text : '删除版本',
											disabled : true,
											action : 'deleteVersion'
										}, '-',{
											xtype : 'button',
											itemId : 'allowDownload',
											iconCls : 'icon-data',
											text : '允许下载',
											disabled : true,
											action : 'allowDownload'
										}, '-',{
											xtype : 'button',
											itemId : 'denyDownload',
											iconCls : 'icon-data',
											text : '禁止下载',
											disabled : true,
											action : 'denyDownload'
										}, '-',{
											width : 400,
											fieldLabel : '搜索',
											labelWidth : 40,
											xtype : 'searchfield',
											store : Ext.data.StoreManager.lookup('Versions.Soft.SoftBeta')
										}]
							}, {
								xtype : 'pagingtoolbar',
								store : 'Versions.Soft.SoftBeta',
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
				selectionchange : function(selModel, selections) {
					
				}
			}
		});