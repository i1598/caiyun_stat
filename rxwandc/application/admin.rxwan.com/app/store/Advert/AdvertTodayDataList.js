Ext.define('DC.store.Advert.AdvertTodayDataList', {
    extend: 'Ext.data.Store',
    model: 'DC.model.Advert.AdvertTodayDataList',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'ajax',
        api: {
            read    : '/advertdata/lists'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success',
            totalProperty: 'total'
        },
        
		writer : {
			type : 'json',
			writeAllFields : false,
			root : 'data'
		},
        listeners: {
        }
    },
    listeners: {
        add: function(records) {
        	//console.log(records);
        }
    }
});