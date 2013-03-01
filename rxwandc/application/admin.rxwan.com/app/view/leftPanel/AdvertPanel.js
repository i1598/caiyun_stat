/**
 * This is a panel for admin controller.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.leftPanel.AdvertPanel',{
	extend :'Ext.tree.Panel',
	alias	: 'widget.advertPanel',
	store : 'toolbar.AdvertPanel',
	title : '广告数据',
	rootVisible: false,
	lines : false,
	useArrows :true,
	iconCls:'icon icon-listForKnow'
	
});
