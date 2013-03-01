/**
 * This is a panel for admin controller.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.leftPanel.AdminPanel',{
	extend :'Ext.tree.Panel',
	alias	: 'widget.adminPanel',
	store : 'toolbar.AdminPanel',
	title : '管理员中心',
	rootVisible: false,
	lines : false,
	useArrows :true,
	iconCls:'icon icon-usercenter'
	
});
