Ext.define('DC.store.Advert.AdvertZone', {
    extend: 'Ext.data.Store',
    model: 'DC.model.Advert.AdvertZone',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'ajax',
        api: {
            read    : '/advertzone/lists'
            ,create : '/advertzone/add',
            update  : '/advertzone/edit'
           ,destroy : '/advertzone/del'
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