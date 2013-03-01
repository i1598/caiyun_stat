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
					'Ext.ux.form.SearchField'
				]);
Ext.define('DC.view.Version.SoftList', {
			extend : 'Ext.grid.Panel',
			alias : 'widget.softList',
			
			requires:['Ext.toolbar.Toolbar','Ext.toolbar.Paging','Ext.grid.column.Column'],
			store : 'Version.SoftList',
			frame : true,
			multiSelect: true,
			initComponent : function() {
				Ext.apply(this, {
							columns : [{
										header : 'ID',
										dataIndex : 'file2version_id',
										flex : 1
									}, {
										header : '文件名',
										dataIndex : 'filename',
										flex : 3
									}, {
										header : '类型',
										dataIndex : 'file_typeon',
										flex : 1,
										editor : {
											allowBlank : false
										},
										renderer:function(value){
											if(value==1){
												return '安装包';
											
											}else if(value==2){
												return '渠道包';
											}else if(value==3){
												return '静默包';
											}else{
												return '未知';
											}
										
										}
									}, {
										header :'上传时间',
										dataIndex : 'dateline',
										flex : 2,
										editor : {
											allowBlank : true
										},
										renderer:function(value){
										 	var date1 = new Date(value*1000);
										 	return Ext.Date.format(date1,'Y-m-d  H:i:s');
										}
									}, {
										header : '文件大小',
										dataIndex : 'size',
										flex : 3,
										editor : {
											allowBlank : true
										}
									}, {
										header : 'MD5值',
										dataIndex : 'md5sum',
										
										flex : 4
										
									}],
							dockedItems : [{
								xtype : 'toolbar',
								dock : 'top',
								items : ['-', {
											width : 400,
											fieldLabel : '搜索',
											labelWidth : 40,
											xtype : 'searchfield',
											store : Ext.data.StoreManager.lookup('Version.SoftList')
										}]
							}, {
								xtype : 'pagingtoolbar',
								store : 'Version.SoftList',
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