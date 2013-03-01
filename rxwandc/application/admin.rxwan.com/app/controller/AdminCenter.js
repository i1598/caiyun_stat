Ext.define('DC.controller.AdminCenter', {
    extend: 'Ext.app.Controller',
    views: [
    	'adminCenter.AdminList'		//管理员列表
    ,   'adminCenter.AdminAdd'		//添加管理员
    ,   'adminCenter.RoleManage'	//管理员角色管理
    ,   'adminCenter.RightsAssign'	//管理员权限分配
    ,   'adminCenter.AddAdminRole'	//添加管理员角色
    ,	'adminCenter.Adminlog'
    ],
    stores: [
        'adminCenter.AdminListStore',
        'adminCenter.RightsStore',
        'adminCenter.AdminRoleStore',
        'adminCenter.Adminlog'
        ],
    models: [
       'adminCenter.AdminModel',
        'adminCenter.AdminRoleModel',
        'adminCenter.Adminlog'
        ],
    init: function() {
        this.control({
        	
            'adminList button[action = addAdmin]': {
                click : this.addAdmin
            },
            'adminAdd button[action = saveAdmin]': {
                click : this.saveAdmin
            },
            'adminList button[action = deleteAdmin]' : {
            	click : this.deleteAdmin
            },
            'roleManage button[action = addAdminRole]': {
                click : this.addAdminRole
            },
            'roleManage button[action = deleteAdminRole]': {
                click : this.deleteAdminRole
            },
            'roleManage button[action = rightsAssign]': {
                click : this.rightsAssign
            },
            'addAdminRole button[action = saveAdminRole]': {
                click : this.saveAdminRole
            },
            'rightsAssign button[action = saveAssign]' : {
            	click : this.saveAssign
            }
            
        });
    },
    addAdmin : function(grid, editor){
        Ext.widget('adminAdd');
    },
    saveAdmin : function(button){
        var win = button.up('window')
        ,   form = win.down('form')
        ,   values = form.getValues()
        ,	store = this.getStore('adminCenter.AdminListStore');
        form.getForm().isValid() && store.insert(0, values);
        store.load();
        win.close();
    },
    deleteAdmin : function(button){
    	var selection = button.up('adminList').getSelectionModel().getSelection();
    	selection.length && this.getStore('adminCenter.AdminListStore').remove(selection);
    },
    rightsAssign : function(grid, editor){
    	Ext.widget('rightsAssign');
    },
    addAdminRole : function(grid, editor){
    	Ext.widget('addAdminRole');
    },
    saveAdminRole : function(button){
        var win = button.up('addAdminRole')
        ,   form = win.down('form')
        ,   values = form.getValues()
        ,	store = this.getStore('adminCenter.AdminRoleStore');
        form.getForm().isValid() && store.insert(0, values);
    	store.load();
    	win.close();
    },
    deleteAdminRole : function(button){
    	var win = button.up('roleManage')
    	,	selection = win.getSelectionModel().getSelection();
    	selection.length && this.getStore('adminCenter.AdminRoleStore').remove(selection);
    },
    /*
    saveAssign : function(button){
    	var win = button.up('rightsAssign')
    	,	form = win.down('form');
    	form.submit({
    		url : '/admin/saveassign',
    		type : 'ajax',
    		success : function(){
    			console.log('success');
    		},
    		error : function(){
    			console.log('error');
    		}
    	})
    	win.close();
    },
    */
    
    //CheckboxSelectionBegin
	saveAssign : function(button,record) {
		var adminCenter = this,
			win = button.up('RightsAssign');
		var insert_value,send_data1=[],send_data2, win = button.up('rightsAssign'), form = win.down('form'),checkbox = form.down('checkboxgroup'), values = form.getValues(),
		send_data3;
		//send_data3 = win1.getSelectionModel().getSelection()[0].get('id');
		
		//form.getForm().isValid() && alert(values.id7);
		//alert(form.checkboxgroup.items.length);
		var k=0;
		for(var i=0;i<checkbox.items.length;i++){
				
				var current_value= eval('values.id'+i);
				//alert(typeof(current_value));
				//console.info(typeof(current_value));
				if(typeof(current_value)!='undefined'){
					
					send_data1[k]=current_value;
					k++;
				}

		}
		
		send_data1=send_data1.join(',');
		
		send_data3 = values.roleid;
		
		//alert(send_data1);	
		//'id':"+send_data3+",	
		insert_value="{'id':"+send_data3+",'resource_id':'"+send_data1+"','assign':1}";
		//alert(insert_value);
		send_data2 = Ext.JSON.decode(insert_value,true);
		//alert(send_data2.resource_id);
		//console.info(send_data3);
		//console.info(send_data2);
		//'edit',['id','resource_id'],
		//form.getForm().isValid() && this.getStore('adminCenter.AdminRoleStore').update(send_data2);
		//form.getForm().isValid() && this.getStore('adminCenter.AdminRoleStore').insert(0,send_data2);
	
		form.submit({
    		url : '/role/edit',
    		type : 'ajax',
    		scope : [adminCenter,win],
    		params : send_data2,
    		success : function(){
    			adminCenter.getStore('adminCenter.AdminRoleStore').load();
    			win.close();
    		},
    		error : function(){
    			console.log('error');
    		}
    	})
    	
	}
    
    ///CheckboxSelectionEnd
    
    
});