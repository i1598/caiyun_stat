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

Ext.define('DC.view.Advert.Advertise', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.advertise',
    store: 'Advert.Advertise',
    frame: true,
    multiSelect: true,
    initComponent: function() {
        Ext.apply(this, {
            columns: [{
                header: 'ID',
                dataIndex: 'id',
                flex: 1
            }, {
                header: '广告信息',
                dataIndex: 'info',
                flex: 2,
                editor: {
                    allowBlank: false
                }
            }, {
                header: '跳转地址',
                dataIndex: 'url',
                flex: 2,
                editor: {
                    allowBlank: false
                }
            }, {
                header: '广告类型',
                dataIndex: 'type',
                flex: 2
            }, {
                header: '权重',
                dataIndex: 'weight',
                flex: 1
            }, {
                header: '开始时间',
                dataIndex: 'start_time',
                renderer:function(value){
				 	var date1 = new Date(value*1000);
				 	return Ext.Date.format(date1,'Y-m-d  H:i:s');
				},
                flex: 2
            }, {
                header: '结束时间',
                dataIndex: 'end_time',
                renderer:function(value){
				 	var date1 = new Date(value*1000);
				 	return Ext.Date.format(date1,'Y-m-d  H:i:s');
				},
                flex: 2
            }],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [{
                    xtype: 'button',
                    text: '添加',
                    iconCls: 'icon-add',
                    action: 'addAdvertise'
                }, {
                    xtype: 'button',
                    itemId: 'deleteAdvertise',
                    iconCls: 'icon-delete',
                    text: '删除',
                    disabled: true,
                    action: 'deleteAdvertise'
                },{
					xtype: 'combo',
					fieldLabel : '广告位选择',
        			store : 'Advert.AdvertPosition',
        			displayField:'title',
        			valueField:'id'
				}, '-',
                {
                    width: 400,
                    fieldLabel: '搜索',
                    labelWidth: 40,
                    xtype: 'searchfield',
                    store: Ext.data.StoreManager.lookup('Advert.Advertise')
                }]
            }, {
                xtype: 'pagingtoolbar',
                store: 'Advert.Advertise',
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
					this.down('#deleteAdvertise').setDisabled(selections.length === 0);
					if(selections.length > 1){
						this.down('#deleteAdvertise').setText("批量删除");
					}else if(selections.length == 1){
						this.down('#deleteAdvertise').setText("删除");
					}
		},
        render:function(){
					    		var combo_box1 = this.down('combo');
					    		var stat_store = this.getStore();
					    		var combo_store1 = combo_box1.getStore();
					    		var combo_data1 = combo_store1.data;
					    		
//					    		var combo_box2 = combo_box1.nextSibling('combo');
//					    		var combo_store2 = combo_box2.getStore();
//					    		var combo_data2 = combo_store2.data;
					    		
					    		combo_box1.on('change',function(){
					    			stat_store.on('beforeload',function(){
					    				Ext.apply(stat_store.proxy.extraParams,{advert_position_id:combo_box1.value});
					    			});
					    			stat_store.load();
					    		});
					    		
//					    		combo_box2.on('change',function(){
//					    			var data1 = combo_data2.getByKey(combo_box2.value).data;
//					    			stat_store.on('beforeload',function(){
//					    				Ext.apply(stat_store.proxy.extraParams,{type:combo_box1.value,softid:combo_box2.value});
//					    			});
//					    			stat_store.load();
//					    		});

					    		
    	}
     
    }
});