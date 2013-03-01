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
var AdminRowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
    clicksToMoveEditor: 1,
    autoCancel: false
});

/**
 * 添加搜索框
 */
Ext.require(['Ext.ux.form.SearchField','Ext.form.field.ComboBox']);

Ext.define('DC.view.adminCenter.AdminList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.adminList',
    store: 'adminCenter.AdminListStore',
    frame: true,
    multiSelect: true,
    initComponent: function() {
        Ext.apply(this, {
            columns: [{
                header: 'ID',
                dataIndex: 'id',
                flex: 1
            }, {
                header: '用户名',
                dataIndex: 'username',
                flex: 2,
                editor: {
                    allowBlank: false
                }
            }, {
                header: '姓名',
                dataIndex: 'realname',
                flex: 2,
                editor: {
                    allowBlank: false
                }
            }, {
                header: '邮箱',
                dataIndex: 'email',
                flex: 2,
                editor: {
                    allowBlank: false,
                    vtype : 'email'
                }
            }, {
                header: '角色',
                dataIndex: 'role',
                flex: 3,
                editor: {
                    xtype : 'combobox',
                    itemId : 'roleid',
                    allowBlank: false,
                    store : 'adminCenter.AdminRoleStore',
                    valueField : 'id',
                    displayField : 'name'
                }
            }, {
                header: '最后登录时间',
                dataIndex: 'lastlogin',
                flex: 4
            }],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [{
                    xtype: 'button',
                    text: '添加',
                    iconCls: 'icon-add',
                    action: 'addAdmin'
                }, {
                    xtype: 'button',
                    itemId: 'deleteAdmin',
                    iconCls: 'icon-delete',
                    text: '删除',
                    disabled: true,
                    action: 'deleteAdmin'
                }, '-',
                {
                    width: 400,
                    fieldLabel: '搜索',
                    labelWidth: 40,
                    xtype: 'searchfield',
                    store: Ext.data.StoreManager.lookup('adminCenter.AdminListStore')
                }]
            }, {
                xtype: 'pagingtoolbar',
                store: 'adminCenter.AdminListStore',
                dock: 'bottom',
                displayInfo: true
            }]
        });
        this.callParent(arguments);
    },
    plugins: [
        AdminRowEditing
    ],
    listeners: {
        /**
         * Used to the game URL list action which selection action changed.
         *  if the selection is null ,the button of delete is disabled.
         * @param {object} selModel
         * @param {object} selections
         */
        selectionchange : function(selModel, selections) {
					//selection change action for button style.
					this.down('#deleteAdmin').setDisabled(selections.length === 0);
					if(selections.length > 1){
						this.down('#deleteAdmin').setText("批量删除");
					}else if(selections.length == 1){
						this.down('#deleteAdmin').setText("删除");
					}
		},
        
        /**
         *修改角色的值 
         */
        show : function(){
		
		},
		render:function(){
			var store = Ext.data.StoreManager.lookup("adminCenter.AdminListStore");
			//console.log(store);
		}
     
    }
});