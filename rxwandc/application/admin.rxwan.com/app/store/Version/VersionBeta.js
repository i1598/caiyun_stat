Ext.define('DC.store.Version.VersionBeta', {
	extend : 'Ext.data.Store',
	model : 'DC.model.Version.Version',
	autoLoad : false,
	autoSync : true,
	proxy : {
		type : 'ajax',
		api : {
			read 		: '/soft/vlist/3'
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
