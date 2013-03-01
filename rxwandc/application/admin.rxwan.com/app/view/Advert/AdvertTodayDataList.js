/**
 * Description of TodayDataList
 *
 * @author Being
 */
Ext.require(['Ext.ux.form.SearchField']);

Ext.define('DC.view.Advert.AdvertTodayDataList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.advertTodayDataList',
    store: 'Advert.AdvertTodayDataList',
    frame: true,
    multiSelect: false,
    initComponent: function() {
        Ext.apply(this, {
            columns: [
            {
            	header:'统计时间',
            	dataIndex:'dateline',
            	renderer:function(value){
				 	var date1 = new Date(value*1000);
				 	return Ext.Date.format(date1,'Y-m-d  H:i:s');
				},
            	flex:1
            },
            {
                header: 'UV',
                dataIndex: 'uv',
                flex: 1
            }, {
                header: 'PV',
                dataIndex: 'pv',
                flex: 1
            }, {
                header: '当日总点击量',
                dataIndex: 'click',
                flex: 1
            }, {
                header: '当日独立点击量',
                dataIndex: 'click_unique',
                flex: 1
            },{
            	header:'引用时间',
            	dataIndex:'referer_dateline',
            	renderer:function(value){
				 	var date1 = new Date(value*1000);
				 	return Ext.Date.format(date1,'Y-m-d  H:i:s');
				},
            	flex:1
            
            }],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [
                {
					xtype: 'combo',
					id :'advertCombo1',
					fieldLabel : '广告位选择',
        			store : 'Advert.AdvertPosition',
        			displayField:'title',
        			valueField:'id'
				},{
					xtype: 'combo',
					id :'advertCombo',
					fieldLabel : '广告选择',
        			store : 'Advert.Advertise',
        			displayField:'info',
        			valueField:'id'
				},{
                    width: 400,
                    fieldLabel: '搜索',
                    labelWidth: 40,
                    xtype: 'searchfield',
                    store: Ext.data.StoreManager.lookup('Advert.AdvertTodayDataList')
                }]
            }, {
                xtype: 'pagingtoolbar',
                store: 'Advert.AdvertTodayDataList',
                dock: 'bottom',
                displayInfo: true
            }]
        });
        this.callParent(arguments);
    },
    listeners: {
        /**
         * Used to the game URL list action which selection action changed.
         *  if the selection is null ,the button of delete is disabled.
         * @param {object} selModel
         * @param {object} selections
         */
        selectionchange: function(Model, selections) {
        	function callBack(options){
        			//获取原始的响应数据
        			var responseText = options.response.responseText;
        			//获取记录总条数
        			var totalRecords = options.resultSet.totalRecords;
        			console.log(totalRecords);
        		}
            //selection change action for button style.
            var len = selections.length,
            self = this           
            ,advertId = Ext.getCmp('advertCombo').value;
            if(len){
            	
            	var rec = selections[0]
            	,	referer_dateline = rec.get('referer_dateline')
            	,	url1 = '/advertchat/lists?advertId=' + advertId+'&dateline='+referer_dateline
            	,	chart = this.up('panel').down('chart')
            	,	store = chart.getStore('Advert.AdvertChatList')
            	,	ajax = new Ext.data.proxy.Ajax({
	        			url : url1,
	        			model : 'DC.model.Advert.AdvertChatList',
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
        }
        //点击下拉框产生效果
        ,render:function(){
					    		var combo_box1 = Ext.getCmp('advertCombo');
					    		var stat_store = this.getStore();
					    		var combo_store1 = combo_box1.getStore();
					    		var combo_data1 = combo_store1.data;
					    		
					    		
					    		var combo_box2 = Ext.getCmp('advertCombo1');
					    		
					    		combo_box2.on('change',function(){
					    			combo_store1.on('beforeload',function(){
					    				Ext.apply(combo_store1.proxy.extraParams,{advert_position_id:combo_box2.value});
					    			})
					    			combo_store1.load();
					    		});
					    		
					    		combo_box1.on('change',function(){
					    			stat_store.on('beforeload',function(){
					    				Ext.apply(stat_store.proxy.extraParams,{advertId:combo_box1.value});
					    			});
					    			stat_store.load();
					    		});
					    		
    }
   }
});