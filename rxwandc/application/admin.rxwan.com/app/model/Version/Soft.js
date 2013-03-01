/**
 * The model for review dictionary
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.model.Version.Soft', {
	extend : 'Ext.data.Model',
	fields : ["soft_id",'dateline','soft_name'
	],
	idProperty : "soft_id"
});
