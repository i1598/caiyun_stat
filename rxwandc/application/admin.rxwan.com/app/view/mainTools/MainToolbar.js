/**
 *  MainToolbar Vuews
 *
 * @author pluto
 * @version 1.1
 */

Ext.define('DC.view.mainTools.MainToolbar', {
	extend : 'Ext.toolbar.Toolbar',
	alias : 'widget.mainToolbar',
	requires:['Ext.button.Split'],
	initComponent : function(){
		this.addEvents('create');
		Ext.apply(this, {
			items : [
			
			{
				// xtype: 'button', // default for Toolbars
				text : 'Button',
				xtype:'tbfill'
			},
			/* {
				xtype : 'splitbutton',
				text : 'Split Button'
			},
			// begin using the right-justified button container
			'->', // same as { xtype: 'tbfill' }
			{
				xtype : 'textfield',
				name : 'field1',
				emptyText : 'enter search term'
			},
			// add a vertical separator bar between toolbar items
			'-', // same as {xtype: 'tbseparator'} to create Ext.toolbar.Separator
			'text 1', // same as {xtype: 'tbtext', text: 'text1'} to create Ext.toolbar.TextItem
			{
				xtype : 'tbspacer'
			}, // same as ' ' to create Ext.toolbar.Spacer
			'text 2', {
				xtype : 'tbspacer',
				width : 150
			}, // add a 50px space
			*/
			{
				xtype : 'splitbutton',
				id : 'logedAdminOfMainToolbar',
				iconCls : 'icon icon-admin',
				region:'east',
				width:100,
				
				
				menu : [{
					text : '注销',
					itemId : 'logoutMenuOfMainToolbar',
					action : 'logout'
				}]
			}]
		});
		this.callParent(arguments);
	}
}); 