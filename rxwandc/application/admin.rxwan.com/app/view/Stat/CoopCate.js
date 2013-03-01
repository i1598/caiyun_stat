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

Ext.define('DC.view.Stat.CoopCate', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.coopCate',
    store: 'Stat.CoopCate',
    frame: true,
    multiSelect: true,
    initComponent: function() {
        Ext.apply(this, {
            columns: [{
                header: 'ID',
                dataIndex: 'id',
                flex: 1
            }, {
                header: '合作商类型',
                dataIndex: 'category_name',
                flex: 2
                
            }, {
                header: '时间',
                dataIndex: 'dateline',
                flex: 2,
                renderer:function(value){
				 	var date1 = new Date(value*1000);
				 	return Ext.Date.format(date1,'Y-m-d  H:i:s');
				},
                editor: {
                    allowBlank: false
                }
            }],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [{
					xtype : 'button',
					itemId : 'cateAdd',
					text : '添加',
					iconCls : 'icon-add',
					
					action : 'cateAdd'
				},
                {
                    width: 400,
                    fieldLabel: '搜索',
                    labelWidth: 40,
                    xtype: 'searchfield',
                    store: Ext.data.StoreManager.lookup('Stat.CoopCate')
                }]
            }, {
                xtype: 'pagingtoolbar',
                store: 'Stat.CoopCate',
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
		},
        
        /**
         *修改角色的值 
         */
        show : function(){
		
		},
		render:function(){
		}
     
    }
});