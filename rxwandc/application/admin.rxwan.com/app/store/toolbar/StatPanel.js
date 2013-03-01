Ext.define('DC.store.toolbar.StatPanel', {
    extend : 'Ext.data.TreeStore',
    model : 'DC.model.toolbar.PanelModel',
    autoLoad : true,
    proxy : {
        type : 'ajax',
        api : {
            read : '/toolbar/statlist'
        },
        reader : {
            type : 'json',
            root : 'data',
            successProperty : 'success'
        }
    }
});
