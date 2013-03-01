/**
 * This is a panel for admin controller.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.leftPanel.StatPanel',{
	extend :'Ext.tree.Panel',
	alias	: 'widget.statPanel',
	store : 'toolbar.StatPanel',
	title : '统计数据',
	rootVisible: false,
	lines : false,
	useArrows :true,
	iconCls:'icon icon-statistics'
	
});
