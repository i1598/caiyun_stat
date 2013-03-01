Ext.define('DC.store.Advert.Advertise', {
    extend: 'Ext.data.Store',
    model: 'DC.model.Advert.Advertise',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'ajax',
        api: {
            read    : '/advertise/lists'
            ,create : '/advertise/add'
            ,update : '/advertise/edit'
            ,destroy: '/advertise/del'
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