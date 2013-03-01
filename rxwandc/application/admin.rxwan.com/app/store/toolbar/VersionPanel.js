Ext.define('DC.store.toolbar.VersionPanel', {
    extend : 'Ext.data.TreeStore',
    model : 'DC.model.toolbar.PanelModel',
    autoLoad : true,
    proxy : {
        type : 'ajax',
        api : {
            read : '/toolbar/versionlist'
        },
        reader : {
            type : 'json',
            root : 'data',
            successProperty : 'success'
        }
    }
});
