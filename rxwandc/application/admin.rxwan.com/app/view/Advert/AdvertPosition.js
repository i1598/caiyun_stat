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

Ext.define('DC.view.Advert.AdvertPosition', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.advertPosition',
    store: 'Advert.AdvertPosition',
    frame: true,
    multiSelect: true,
    initComponent: function() {
        Ext.apply(this, {
            columns: [{
                header: 'ID',
                dataIndex: 'id',
                flex: 1
            }, {
                header: '广告位信息',
                dataIndex: 'title',
                flex: 2,
                editor: {
                    allowBlank: false
                }
            }, {
                header: '广告位置',
                dataIndex: 'advert_zone_id',
                flex: 2,
                editor: {
                    allowBlank: false
                }
            }, {
                header: 'LABEL',
                dataIndex: 'label',
                flex: 2
            }],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [{
                    xtype: 'button',
                    text: '添加',
                    iconCls: 'icon-add',
                    action: 'addPosition'
                }, {
                    xtype: 'button',
                    itemId: 'deletePosition',
                    iconCls: 'icon-delete',
                    text: '删除',
                    disabled: true,
                    action: 'deletePosition'
                }, '-',
                {
                    width: 400,
                    fieldLabel: '搜索',
                    labelWidth: 40,
                    xtype: 'searchfield',
                    store: Ext.data.StoreManager.lookup('Advert.AdvertPosition')
                }]
            }, {
                xtype: 'pagingtoolbar',
                store: 'Advert.AdvertPosition',
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
					this.down('#deletePosition').setDisabled(selections.length === 0);
					if(selections.length > 1){
						this.down('#deletePosition').setText("批量删除");
					}else if(selections.length == 1){
						this.down('#deletePosition').setText("删除");
					}
		}     
    }
});