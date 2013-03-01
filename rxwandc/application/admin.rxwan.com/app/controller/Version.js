Ext.define('DC.controller.Version', {
    extend: 'Ext.app.Controller',
    views: [
    	'Version.VersionInstall'		//软件安装包管理 
    	,'Version.VersionUpdate'		//软件升级包管理
    	,'Version.VersionAdd1'
    	,'Version.VersionAdd2'
    	,'Version.UploadSoft1' //上传软件
    	,'Version.UploadSoft2'
    	,'Version.SoftList'
    	,'Version.SoftListWindow'
    	,'Version.SoftwareList'
    	,'Version.SoftwareAdd'
    ],
    stores: [
		'Version.VersionInstall'		//软件安装包管理 
    	,'Version.VersionUpdate'		//软件升级包管理
    	,'Version.Soft'
    	,'Version.SoftList'
    ],
    models: [
		'Version.Version'				//模型
    	,'Version.Soft'
    	,'Version.SoftList'
    ],
    init: function() {
        this.control({
        	
            'versionInstall button[action = addVersion]' : {
            	click : this.addVersion1
            }
            ,'versionInstall button[action = uploadSoft]' : {
            	click : this.uploadSoft1
            }
            ,'versionInstall button[action = softList]' : {
            	click : this.softList1
            }
            ,'versionAdd1 button[action = saveVersion]' : {
            	click : this.saveVersion1
            }
            ,'uploadSoft1 button[action = doSoftUpload]' : {
            	click : this.doSoftUpload1
            }
            
            //update
            ,'versionUpdate button[action = addVersion]' : {
            	click : this.addVersion2
            }
            ,'versionUpdate button[action = uploadSoft]' : {
            	click : this.uploadSoft2
            }
            ,'versionUpdate button[action = softList]' : {
            	click : this.softList2
            }
            ,'versionAdd2 button[action = saveVersion]' : {
            	click : this.saveVersion2
            }
            ,'uploadSoft2 button[action = doSoftUpload]' : {
            	click : this.doSoftUpload2
            }
            
            //software
            ,'softwareList button[action = addSoftware]' : {
            	click : this.addSoftware
            }
             ,'softwareAdd button[action = saveSoftware]' : {
            	click : this.saveSoftware
            }
            
            
            
        });
    },
    addVersion1:function(){
        Ext.widget('versionAdd1');
    }
    ,saveVersion1:function(button){
    	var window = button.up('window')
    	, form = window.down('form')
    	, value = form.getValues();
    	store = this.getStore('Version.VersionInstall');
    	store.insert(0,value);
    	store.load();
    	window.close();
    },
    uploadSoft1:function(button){
    	var selection = button.up('versionInstall').getSelectionModel().getSelection();
    	data = selection[0].data;
    	var hidden1 = Ext.widget('uploadSoft1').down('form').down('hidden'),
		hidden2 = hidden1.nextSibling('hidden');
		//设置这两个隐藏域的值
		hidden1.setValue(data.id);
		hidden2.setValue(data.typeon);
    },
    doSoftUpload1:function(button){
   		var controller = this
    	,   win = button.up('uploadSoft1')
    	, 	form = win.down('form')
    	, 	value = form.getValues();
    	//console.log(value);
		if(form.getForm().isValid()){  
            form.submit({  
            			scope:[controller,win],
                       url:'/soft/upload',
                        type : 'ajax',
                      //  waitMsg:'正在上传', 
                        
                        success:function(fp,o){
                        	var a = Ext.JSON.decode(o.response.responseText,true);
                        	
                        	
                        	//console.log(a);
                        	 controller.getStore('Version.VersionInstall').load();
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
    },
    softList1:function(button){
    	var selection = button.up('versionInstall').getSelectionModel().getSelection();
    	data = selection[0].data;
    	var view = Ext.widget('softListWindow');
    	var store = this.getStore('Version.SoftList');
    	
    	store.load({params:{version_id:data.id,typeon:data.typeon}});
    },
    
    //update
     addVersion2:function(){
        Ext.widget('versionAdd2');
    },
    
    softList2:function(button){
    	var selection = button.up('versionUpdate').getSelectionModel().getSelection();
    	data = selection[0].data;
    	var view = Ext.widget('softListWindow');
    	var store = this.getStore('Version.SoftList');
    	store.load({params:{version_id:data.id,typeon:data.typeon}});
    }
    
    
     ,saveVersion2:function(button){
    	var window = button.up('window')
    	, form = window.down('form')
    	, value = form.getValues();
    	console.log(value);
    	store = this.getStore('Version.VersionUpdate');
    	store.insert(0,value);
    	store.load();
    	window.close();
    },
    uploadSoft2:function(button){
    	var selection = button.up('versionUpdate').getSelectionModel().getSelection();
    	data = selection[0].data;
    	var hidden1 = Ext.widget('uploadSoft2').down('form').down('hidden'),
		hidden2 = hidden1.nextSibling('hidden');
		//设置这两个隐藏域的值
		hidden1.setValue(data.id);
		hidden2.setValue(data.typeon);
    },
    doSoftUpload2:function(button){
   		var controller = this
    	,   win = button.up('uploadSoft2')
    	, 	form = win.down('form')
    	, 	value = form.getValues();
    	//console.log(value);
		if(form.getForm().isValid()){  
            form.submit({  
            			scope:[controller,win],
                       url:'/soft/upload',
                        type : 'ajax',
                      //  waitMsg:'正在上传', 
                        
                        success:function(fp,o){
                        	var a = Ext.JSON.decode(o.response.responseText,true);
                        	
                        	
                        	//console.log(a);
                        	 controller.getStore('Version.VersionUpdate').load();
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
    },
    
    softList2:function(button){
    	var selection = button.up('versionUpdate').getSelectionModel().getSelection();
    	data = selection[0].data;
    	var view = Ext.widget('softListWindow');
    	var store = this.getStore('Version.SoftList');
    	store.load({params:{version_id:data.id,typeon:data.typeon}});
    }
    
    //software
    ,addSoftware:function(){
    	Ext.widget('softwareAdd');
    	
    }
    ,saveSoftware:function(button){
    	var window = button.up('window')
    	, form = window.down('form')
    	, value = form.getValues();
    	store = this.getStore('Version.Soft');
    	store.insert(0,value);
    	store.load();
    	window.close();
    }
});