Ext.define('DC.store.toolbar.AdvertPanel', {
    extend : 'Ext.data.TreeStore',
    model : 'DC.model.toolbar.PanelModel',
    autoLoad : true,
    proxy : {
        type : 'ajax',
        api : {
            read : '/toolbar/advertlist'
        },
        reader : {
            type : 'json',
            root : 'data',
            successProperty : 'success'
        }
    }
});
