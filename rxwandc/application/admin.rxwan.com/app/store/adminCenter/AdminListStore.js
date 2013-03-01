var forumId = 5;
Ext.define('DC.store.adminCenter.AdminListStore', {
    extend: 'Ext.data.Store',
    model: 'DC.model.adminCenter.AdminModel',
    autoLoad: false,
    autoSync: true,
    extraParams: {
        forumId: forumId
    },
    proxy: {
        type: 'ajax',
        api: {
            read    : '/member/lists',
            create  : '/member/create',
            update  : '/member/edit',
            destroy : '/member/del'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success',
            totalProperty: 'total'
        },
        writer: {
            type: 'json',
            writeAllFields: false,
            root: 'data'
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
                delete params.forumId;
            } else {
                params.forumId = forumId;
            }
        },
        add: function(records) {
        	//console.log(records);
        }
    }
});