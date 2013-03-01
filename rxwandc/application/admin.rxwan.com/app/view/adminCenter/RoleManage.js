/**
 * 管理员列表 adminList.js
 *
 * @author Being
 * @Editor Pluto 2012.05.09
 * @version 1.1
 */
/**
 * 为管理员列表增加行内编辑功能RowEditing
 */
var RoleManageRowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
    clicksToMoveEditor: 1,
    autoCancel: false
});

/**
 * 添加搜索框
 */
Ext.require(['Ext.ux.form.SearchField','Ext.form.field.ComboBox']);
Ext.define('DC.view.adminCenter.RoleManage', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.roleManage',
    store: 'adminCenter.AdminRoleStore',
    frame: true,
    multiSelect: true,
    initComponent: function() {
        Ext.apply(this, {
        	id : 'roleManagePanel',
            columns: [{
                header: 'ID',
                dataIndex: 'id',
                flex: 1
            }, {
                header: '角色名称',
                dataIndex: 'name',
                flex: 2,
                editor: {
                    allowBlank: false
                }
            },{
            	header: '资源列表',
            	dataIndex:'resource_id',
            	flex:2
            	
            }],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [{
                    xtype: 'button',
                    text: '添加',
                    iconCls: 'icon-add',
                    action: 'addAdminRole'
                }, {
                    xtype: 'button',
                    itemId: 'deleteRole',
                    iconCls: 'icon-delete',
                    text: '删除',
                    disabled: true,
                    action: 'deleteAdminRole'
                }, '-',
                {
                    xtype: 'button',
                    itemId: 'rightsAssign',
                    iconCls: 'icon-data',
                    text: '权限分配',
                    disabled: true,
                    action: 'rightsAssign'
                }]
            }, {
                xtype: 'pagingtoolbar',
                store: 'adminCenter.AdminRoleStore',
                dock: 'bottom',
                displayInfo: true
            }]
        });
        this.callParent(arguments);
    },
    plugins: [
        RoleManageRowEditing
    ],
    listeners: {
        /**
         * Used to the game URL list action which selection action changed.
         *  if the selection is null ,the button of delete is disabled.
         * @param {object} selModel
         * @param {object} selections
         */
        selectionchange: function(selModel, selections) {
            //selection change action for button style.
            var self = this
            ,	dele = self.down('#deleteRole')
            ,	assg = self.down('#rightsAssign')
            ,	len = selections.length;

            assg.setDisabled(len === 0);
            dele.setDisabled(len === 0).setText(len > 1 ? "批量删除" : "删除");
        }
    }
});