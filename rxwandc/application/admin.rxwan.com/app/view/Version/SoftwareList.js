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
Ext.define('DC.view.Version.SoftwareList', {
			extend : 'Ext.grid.Panel',
			alias : 'widget.softwareList',
			
			requires:['Ext.toolbar.Toolbar','Ext.toolbar.Paging','Ext.grid.column.Column'],
			store : 'Version.Soft',
			frame : true,
			multiSelect: true,
			initComponent : function() {
				Ext.apply(this, {
							columns : [{
										header : 'ID',
										dataIndex : 'soft_id',
										flex : 1
									}, {
										header : '文件名',
										dataIndex : 'soft_name',
										flex : 3
									}, {
										header :'添加时间',
										dataIndex : 'dateline',
										flex : 2,
										editor : {
											allowBlank : true
										},
										renderer:function(value){
										 	var date1 = new Date(value*1000);
										 	return Ext.Date.format(date1,'Y-m-d  H:i:s');
										}
									}],
							dockedItems : [{
								xtype : 'toolbar',
								dock : 'top',
								items : [{
											xtype : 'button',
											itemId : 'addSoftware',
											text : '添加新软件',
											iconCls : 'icon-add',
											action : 'addSoftware'
										},'-', {
											width : 400,
											fieldLabel : '搜索',
											labelWidth : 40,
											xtype : 'searchfield',
											store : Ext.data.StoreManager.lookup('Version.Soft')
										}]
							}, {
								xtype : 'pagingtoolbar',
								store : 'Version.Soft',
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