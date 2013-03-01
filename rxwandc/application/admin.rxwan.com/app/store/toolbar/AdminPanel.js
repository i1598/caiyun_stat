Ext.define('DC.store.toolbar.AdminPanel', {
    extend : 'Ext.data.TreeStore',
    model : 'DC.model.toolbar.PanelModel',
    autoLoad : true,
    proxy : {
        type : 'ajax',
        api : {
            read : '/toolbar/adminlist'
        },
        reader : {
            type : 'json',
            root : 'data',
            successProperty : 'success'
        }
    }
});
