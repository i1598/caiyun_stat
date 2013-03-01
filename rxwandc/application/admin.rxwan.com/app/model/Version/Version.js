/**
 * The model for review dictionary
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.model.Version.Version', {
	extend : 'Ext.data.Model',
	fields : ["id",'soft_id',"typeon","is_delete","is_download","is_publish","is_release_on",
	"dateline","time_publish","time_release_on","version_title","version_name","features",
	"bugfixed",'summary','soft_name'
	],
	idProperty : "id"
});
