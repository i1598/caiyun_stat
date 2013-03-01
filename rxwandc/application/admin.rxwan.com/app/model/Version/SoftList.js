/**
 * The model for review dictionary
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.model.Version.SoftList', {
	extend : 'Ext.data.Model',
	fields : ["file2version_id",'version_id','version_typeon','file_typeon','dateline','md5sum','filename','size'
	],
	idProperty : "file2version_id"
});
