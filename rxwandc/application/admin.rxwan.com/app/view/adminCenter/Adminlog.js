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

Ext.define('DC.view.adminCenter.Adminlog', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.adminlog',
    store: 'adminCenter.Adminlog',
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
                header: '操作记录',
                dataIndex: 'action',
                flex: 2,
                editor: {
                    allowBlank: false
                }
            }, {
                header: '操作时间',
                dataIndex: 'create_date',
                flex: 2
              
            }
            ],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [
                {
                    width: 400,
                    fieldLabel: '搜索',
                    labelWidth: 40,
                    xtype: 'searchfield',
                    store: Ext.data.StoreManager.lookup('adminCenter.Adminlog')
                }]
            }, {
                xtype: 'pagingtoolbar',
                store: 'adminCenter.Adminlog',
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
        /*
        selectionchange: function(selModel, selections) {
           var len = selections.length;            
            if(len){
            	var rec = selections[0]
            	,	gameid = 1
            	,	chart = this.up('panel').down('chart')
            	,	store = chart.getStore('statisticsCenter.OneGameTodayStore')
            	,	ajax = new Ext.data.proxy.Ajax({
	        			url : '/stat/charts/day?gameId=' + gameid,
	        			model : 'DC.model.statisticsCenter.DurationStatisticsModel',
				        reader: {
				            type: 'json',
				            root: 'data',
				            successProperty: 'success',
				            totalProperty: 'results'
				        }
	        		})
        		,	options = new Ext.data.Operation({
	        			action :'read'
	        		})
	        	,	records ;
        		ajax.doRequest(options,function(data){
        			records = data.resultSet.records;
        			store.loadData(records);
        		});
            }            
        },
        
        show : function(){
		
		},
		render:function(){
			var store = Ext.data.StoreManager.lookup("adminCenter.AdminListStore");
			//console.log(store);
		}
		*/
     
    }
});