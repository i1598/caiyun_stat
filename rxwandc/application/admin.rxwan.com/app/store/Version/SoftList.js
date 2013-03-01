Ext.define('DC.store.Version.SoftList', {
	extend : 'Ext.data.Store',
	model : 'DC.model.Version.SoftList',
	autoLoad : false,
	autoSync : true,
	proxy : {
		type : 'ajax',
		api : {
			read 		: '/soft/softlist'
		},
		reader : {
			type : 'json',
			root : 'data',
			successProperty : 'success',
			totalProperty : 'results'
		},
		listeners : {
			/**
			 * alert a warning window to show the Error.
			 * @param {} proxy
			 * @param {} response
			 * @param {} operation
			 */
			exception : function(proxy, response, operation) {

			}
		}
	},
	listeners:{
		
	}
});
