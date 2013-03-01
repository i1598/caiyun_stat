Ext.define('DC.store.Version.Soft', {
	extend : 'Ext.data.Store',
	model : 'DC.model.Version.Soft',
	autoLoad : true,
	autoSync : true,
	proxy : {
		type : 'ajax',
		api : {
			read 		: '/soft/show'
			,create 	: '/soft/softnameadd'
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
