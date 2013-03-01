Ext.define('DC.store.Version.VersionUpdate', {
	extend : 'Ext.data.Store',
	model : 'DC.model.Version.Version',
	autoLoad : false,
	autoSync : true,
	proxy : {
		type : 'ajax',
		api : {
			read 		: '/soft/lists/2'
			,create		: '/soft/add/2'
		},
		reader : {
			type : 'json',
			root : 'data',
			successProperty : 'success',
			totalProperty : 'results'
		},
		writer : {
			type : 'json',
			writeAllFields : false,
			root : 'data'
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
