var forumId = 5;
Ext.define('DC.store.adminCenter.Adminlog', {
    extend: 'Ext.data.Store',
    model: 'DC.model.adminCenter.Adminlog',
   autoLoad: false,
    autoSync: true,
    extraParams: {
        forumId: forumId
    },
    proxy: {
        type: 'ajax',
        api: {
            read    : '/adminlog/list'
           
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

                Ext.MessageBox.show({
                    title: 'Error',
                    msg: "admin error",
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK
                });
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
        	alert(1);
            //Ext.widget('FeatureCodesWindow').show();
        }
    }
});