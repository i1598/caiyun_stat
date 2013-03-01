Ext.define('DC.store.toolbar.AccordionBar', {
    extend : 'Ext.data.Store',
    model : 'DC.model.toolbar.AccordionBar',
    autoLoad : true,
    proxy : {
        type : 'ajax',
        api : {
            read : '/toolbar/getTop'
        },
        reader : {
            type : 'json',
            root : 'data',
            successProperty : 'success',
            totalProperty : 'total'
        }
    }
});