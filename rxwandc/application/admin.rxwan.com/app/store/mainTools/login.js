Ext.define('DC.store.mainTools.Login', {
    extend: 'Ext.data.Store',
    fields: ['username'],
    autoLoad: true,
    autoSync: true,
    proxy: {
        type: 'ajax',
        api: '/cgibin/islogin',
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success',
            totalProperty: 'total'
        },
        listeners: {
            /**
             * alert a warning window to show the Error.
             * @param {} proxy
             * @param {} response
             * @param {} operation
             */
            exception: function(proxy, response, operation) {

                Ext.MessageBox.show({
                    title: 'Error',
                    msg: 'Login Error',
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
        }
    }
});