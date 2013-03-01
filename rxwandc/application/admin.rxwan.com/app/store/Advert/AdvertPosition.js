Ext.define('DC.store.Advert.AdvertPosition', {
    extend: 'Ext.data.Store',
    model: 'DC.model.Advert.AdvertPosition',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'ajax',
        api: {
            read    : '/advertposition/lists'
            ,create : '/advertposition/add'
            ,update : '/advertposition/edit'
            ,destroy: '/advertposition/del'
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