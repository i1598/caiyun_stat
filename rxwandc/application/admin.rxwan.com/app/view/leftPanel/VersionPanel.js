/**
 * This is a panel for admin controller.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.leftPanel.VersionPanel',{
	extend :'Ext.tree.Panel',
	alias	: 'widget.versionPanel',
	store : 'toolbar.VersionPanel',
	title : '版本管理',
	rootVisible: false,
	lines : false,
	useArrows :true,
	iconCls:'icon icon-group'
	
});
