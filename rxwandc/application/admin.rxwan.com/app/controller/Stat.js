Ext.define('DC.controller.Stat', {
    extend: 'Ext.app.Controller',
    views: [
    	'Stat.StatPerDay'		//每日统计
    	,'Stat.StatByCoop'		//每日统计
    	,'Stat.CoopCate'		//每日统计
    	,'Stat.CoopAdd'
    	,'Stat.CateAdd'
    	,'Stat.CoopStat'
    	,'Stat.CoopStatWindow'
    ],
    stores: [
		'Stat.StatPerDay'		//每日统计
    	,'Stat.StatByCoop'		//每日统计
    	,'Stat.CoopCate'		//每日统计
    	,'Stat.CoopStat'
    ],
    models: [
		'Stat.StatPerDay'		//每日统计
    	,'Stat.StatByCoop'		//每日统计
    	,'Stat.CoopCate'		//每日统计
    ],
    init: function() {
        this.control({
        	
            'statByCoop button[action = coopAdd]' : {
            	click : this.coopAdd
            },
            'statByCoop button[action = coopStat]' : {
            	click : this.coopStat
            },
            'coopAdd button[action = saveCoop]' : {
            	click : this.saveCoop
            },
            'coopCate button[action = cateAdd]' : {
            	click : this.cateAdd
            },
            'cateAdd button[action = saveCate]' : {
            	click : this.saveCate
            }
            
        });
    },
    coopAdd:function(){
    	Ext.widget('coopAdd');
    },
    saveCoop : function(button){
    	var win = button.up('window')
    	,	form = win.down('form')
    	,	value1 = form.getValues()
    	,	store  = this.getStore('Stat.StatByCoop');
    	
    	//console.log(value1);
    	
    	form.getForm().isValid() && store.insert(0,value1) ;
    	
    	store.load();
    	win.close();
    },
    cateAdd:function(){
    	Ext.widget('cateAdd');
    },
    saveCate : function(button){
    	var win = button.up('window')
    	,	form = win.down('form')
    	,	value1 = form.getValues()
    	,	store  = this.getStore('Stat.CoopCate');
    	
    	//console.log(value1);
    	
    	form.getForm().isValid() && store.insert(0,value1) ;
    	
    	store.load();
    	win.close();
    },
    /*
     * 基于coop的统计
     */
    coopStat : function(button){
    	Ext.widget('coopStatWindow');
    }
});