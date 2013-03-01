Ext.define('DC.store.Advert.AdvertChatList', {
    extend: 'Ext.data.Store',
    model: 'DC.model.Advert.AdvertChatList',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'ajax',
        api: {
            read    : '/advertchat/lists'
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