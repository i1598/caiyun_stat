/**
 * Accordion bar for function list
 * 
 * @author Being
 * @version 1.1
 */
Ext.define('DC.view.leftPanel.AccordionBar' ,{
    extend: 'Ext.Panel',
    margins:'5 0 5 0',
    width : 210,
    
    alias : 'widget.accordionbar',
    title : '彩云充值管理平台',
    store :'toolbar.AccordionBar',
    split : true,
    collapsible: true,
    layout : 'accordion',
    iconCls:'icon icon-topList',
    //autoLoad: true,
    autoShow: true,
   
    items:[
    	
        
    	
    ]
    
    
   ,
   listeners:{
   		
   			render:function(){
   				var self =  this,
   				msg2 = [],
   				data1,
   				store = Ext.data.StoreManager.lookup('toolbar.AccordionBar');
   				
   				store.on('load',function(store,records,success){
   					var msg=[];
   					for(var i=0;i<records.length;i++){
   						var rec=records[i],
   						data11;
   						data11 = "{xtype:'"+rec.get('widget')+"'}";
   						data11 = Ext.JSON.decode(data11,true);
   						msg.push(data11);
   						
   					}
   					
   					
   					self.add(msg);
   				});
   			
   			}
   		
   }
   
});