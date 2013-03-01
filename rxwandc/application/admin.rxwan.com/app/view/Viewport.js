/**
 * The viewport ,which defind the layout and the initComponent.
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.Viewport', {
	extend : 'Ext.container.Viewport',
	
	//requires:['Ext.layout.container.Form','	Ext.panel.Panel','DC.view.mainTools.MainToolbar','DC.view.leftPanel.AccordionBar','DC.view.centerPanel.centerTabPanel'],
	layout : 'border',
	items : [
	{
            xtype: 'box',
            id: 'header',
            region: 'north',
            html: '<h1>Kuaichong Data Center</h1>',
            height: 30
    },
    {
    	xtype : 'panel',
    	layout : 'border',
    	region: 'center',
    	items : [
		   
		    	{
		    		xtype:'mainToolbar',
		    		region : 'north'
	    	},
    		{
				xtype : 'accordionbar',
				region : 'west'
			},
			{
				xtype:'centerTabPanel',
				region:'center'
			}
			
    	]
    }]
});
