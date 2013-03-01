Ext.define('DC.controller.Advert', {
    extend: 'Ext.app.Controller',
    views: [
		'Advert.Advertise'  //广告信息
    	,'Advert.AdvertPosition' //广告位选择
    	,'Advert.AdvertZone' //广告位置
    	,'Advert.AdvertPositionAdd' //广告位选择
    	,'Advert.AdvertZoneAdd' //广告位置
		,'Advert.AdvertAdd' //广告添加
		,'Advert.AdvertTodayDataList'
		,'Advert.AdvertStat'
    ],
    stores: [
		'Advert.Advertise'  //广告信息
    	,'Advert.AdvertiseList'  //广告信息
    	,'Advert.AdvertPosition' //广告位选择
    	,'Advert.AdvertZone' //广告位置
    	,'Advert.LabelInfo'
    	,'Advert.AdvertType'
    	,'Advert.AdvertTodayDataList'
    	,'Advert.AdvertChatList'
    ],
    models: [
		'Advert.Advertise'  //广告信息
    	,'Advert.AdvertPosition' //广告位选择
    	,'Advert.AdvertZone' //广告位置
    	,'Advert.LabelInfo'
    	,'Advert.AdvertTodayDataList'
    	,'Advert.AdvertChatList'
    ],
    init: function() {
        this.control({
        	
            'advertise button[action = addAdvertise]' : {
            	click : this.addAdvertise
            }
            ,'advertise button[action = deleteAdvertise]' : {
            	click : this.deleteAdvertise
            }
            ,'advertPosition button[action = addPosition]' : {
            	click : this.addPosition
            }
             ,'advertPosition button[action = deletePosition]' : {
            	click : this.deletePosition
            }
            ,'advertZone button[action = addZone]' : {
            	click : this.addZone
            }
            ,'advertZone button[action = deleteZone]' : {
            	click : this.deleteZone
            }
             ,'advertZoneAdd button[action = saveZone]' : {
            	click : this.saveZone
            }
            ,'advertPositionAdd button[action = savePosition]' : {
            	click : this.savePosition
            }
            ,'advertAdd button[action = saveAdvertise]' : {
            	click : this.saveAdvertise
            }
            
        });
    },
    addPosition:function(){
    	Ext.widget('advertPositionAdd');
    },
    addZone:function(){
    	Ext.widget('advertZoneAdd');
    },
    deleteZone:function(button,record){
    	var selection = button.up('advertZone').getSelectionModel().getSelection();
    	selection.length && this.getStore('Advert.AdvertZone').remove(selection);
    },
    savePosition : function(button){
    	var win = button.up('window')
    	,	form = win.down('form')
    	,	value1 = form.getValues()
    	,	store  = this.getStore('Advert.AdvertPosition');
    	
    	//console.log(value1);
    	
    	form.getForm().isValid() && store.insert(0,value1) ;
    	
    	store.load();
    	win.close();
    },
     deletePosition : function(button,record){
    	var selection = button.up('advertPosition').getSelectionModel().getSelection();
    	selection.length && this.getStore('Advert.AdvertPosition').remove(selection);
    }
    ,saveZone : function(button){
    	var win = button.up('window')
    	,	form = win.down('form')
    	,	value1 = form.getValues()
    	,	store  = this.getStore('Advert.AdvertZone');
    	
    	//console.log(value1);
    	
    	form.getForm().isValid() && store.insert(0,value1) ;
    	
    	store.load();
    	win.close();
    }
    ,addAdvertise:function(){
    	Ext.widget('advertAdd');
    }
    ,deleteAdvertise : function(button,record){
    	var selection = button.up('advertise').getSelectionModel().getSelection();
    	selection.length && this.getStore('Advert.Advertise').remove(selection);
    }
    ,saveAdvertise:function(button){
    	var controller = this
    	,   win = button.up('window')
    	, 	form = win.down('form')
    	, 	value = form.getValues();
    	//console.log(value);
		if(form.getForm().isValid()){  
            form.submit({  
            			scope:[controller,win],
                       url:'/advertise/add',
                        type : 'ajax',
                      //  waitMsg:'正在上传', 
                        
                        success:function(fp,o){
                        	//var a = Ext.JSON.decode(o.response.responseText,true);
                        	
                        	
                        	//console.log(o);
                        	 controller.getStore('Advert.Advertise').load();
                            win.close();
                            Ext.Msg.show(  
                                    {  
                                        title:'提示信息',  
                                        msg:'文件上传成功',
                                        buttons:Ext.Msg.OK  
                                    }  
                            ) 
                        }
                        
                        ,failure:function(fp,o){
                        	console.log(o);
                        }
                         
             })  
    	}      	
    }
    
});