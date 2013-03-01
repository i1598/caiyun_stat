/**
 * build a window to add admin.
 *
 * @author Pluto
 * @version 1.1
 * dsadsadsadsadsa
 */

Ext.require(['Ext.form.field.ComboBox']);

Ext.define('DC.view.adminCenter.AdminAdd', {
    extend: 'Ext.window.Window',
    alias: 'widget.adminAdd',
    title: '添加管理员',
    width: 280,
    layout: 'fit',
    autoShow: true,
    modal: true,
    initComponent: function() {
        this.addEvents('create');
        Ext.apply(this, {
            items: [{
                xtype: 'form',
                items: [{
                    xtype: 'textfield',
                    name: 'username',
                    margin: '5 5 5 5',
                    allowBlank:false,
                    fieldLabel: '用户名'
                }, {
                    xtype: 'textfield',
                    name: 'password',
                    inputType : 'password',
                    margin: '5 5 5 5',
                    allowBlank:false,
                    fieldLabel: '密码'
                }, {
                    xtype: 'textfield',
                    name: 'realname',
                    margin: '5 5 5 5',
                    allowBlank:false,
                    fieldLabel: '姓名',
                    vtype : 'alpha'
                }, {
                    xtype: 'textfield',
                    name: 'email',
                    margin: '5 5 5 5',
                    allowBlank:false,
                    fieldLabel: 'E-mail',
                    vtype : 'email'
                }, {
                    xtype: 'textfield',
                    name: 'tel',
                    margin: '5 5 5 5',
                    allowBlank:false,
                    fieldLabel: '电话',
                    vtype : 'alphanum'
                }, {
                    xtype: 'combobox',
                    name: 'role',
                    autoSelect:true,
                    store : 'adminCenter.AdminRoleStore',
                    valueField :'id',
                    displayField : 'name',
                    margin: '5 5 5 5',
                    fieldLabel: '角色',
                    editable : false 
                }]
            }],
            buttons: [{
                text: '保存',
                action: 'saveAdmin'
            }, {
                text: '取消',
                scope: this,
                handler: this.close
            }],
            listeners:{
            	
            	click:{
            		
            		fn:function(){
            			console.log(this);
            		}
            	}
            },
            updateError:function(){
            	
            	
            }
            
            

        });
        this.callParent(arguments);
    }
});