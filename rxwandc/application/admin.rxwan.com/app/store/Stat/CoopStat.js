var coopId = 5;
var softId = 1;
Ext.define('DC.store.Stat.CoopStat', {
    extend: 'Ext.data.Store',
    model: 'DC.model.Stat.StatPerDay',
    autoLoad: false,
    autoSync: true,
    extraParams: {
        coopId: coopId
        ,softId:softId
    },
    proxy: {
        type: 'ajax',
        api: {
            read    : '/stat/coopstat/lists'
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
            /**
             * alert a warning window to show the Error.
             * @param {} proxy
             * @param {} response
             * @param {} operation
             */
            exception: function(proxy, response, operation) {
				result = Ext.JSON.decode(response.responseText,true);
				
                if(result.success===false){
	                Ext.MessageBox.show({
	                    title: 'Error',
	                    msg: result.message,
	                    icon: Ext.MessageBox.ERROR,
	                    buttons: Ext.Msg.OK
	                });
                }
            }
        }
    },
    listeners: {
        beforeload: function() {
            var params = this.getProxy().extraParams;
            if (params.query) {
                delete params.coopId;
                delete params.softId;
            } else {
                params.coopId = coopId;
            	params.softId = softId;
            }
        },
        add: function(records) {
        	//console.log(records);
        }
    }
});