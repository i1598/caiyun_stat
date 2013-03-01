Ext.define('DC.store.adminCenter.AdminRoleStore', {
    extend: 'Ext.data.Store',
    model: 'DC.model.adminCenter.AdminRoleModel',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'ajax',
        api: {
            read    : '/role/lists',
            create  : '/role/create',
            update  : '/role/edit',
            destroy : '/role/del'
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
    }
});