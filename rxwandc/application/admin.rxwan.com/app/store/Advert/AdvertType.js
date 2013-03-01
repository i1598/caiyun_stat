Ext.define('DC.store.Advert.AdvertType', {
    extend: 'Ext.data.Store',
    model: 'DC.model.Advert.LabelInfo',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'ajax',
        api: {
            read    : '/adverttype/lists'
            ,create : '/adverttype/add'
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