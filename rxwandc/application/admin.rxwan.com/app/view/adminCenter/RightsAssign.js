/**
 * build a window to add admin.
 *
 * @author Pluto
 * @version 1.1
 */

Ext.require(['Ext.form.FieldSet','Ext.form.CheckboxGroup']);
Ext.define('DC.view.adminCenter.RightsAssign', {
    extend: 'Ext.window.Window',
    alias : 'widget.rightsAssign',
    title : '权限分配',
    layout : 'vbox',
    width : 660,
    bodyStyle : 'background:#fff',
    bodyPadding : 10,
    autoShow: true,
    modal: true,
    initComponent: function() {
		var store = Ext.data.StoreManager.lookup('adminCenter.RightsStore'),
			roleManage = Ext.getCmp('roleManagePanel'),
			baseRights = [],
			loop, item, roleid;
		//console.info(roleManage);
		roleid = roleManage.getSelectionModel().getSelection()[0].get('id');
		//console.info(roleid);
		for(var i=0, len=store.getCount();i<len;i++){
			loop = store.getAt(i);
			item = {
				boxLabel : loop.get('text'),
				inputValue : loop.get('id'),
				name : 'id'+i,
				checked : true
			};
			baseRights.push(item);
		}
		//baseRights.pop();
		//baseRights=baseRights.join(',');
        this.addEvents('create');
        Ext.apply(this, {
        	items :[{
        		xtype : 'form',
        		items :[{
					xtype: 'fieldset',
					layout: 'anchor',
					title : '基础权限',
        			margin: '5 5 5 5',
					width: 620,
					defaults: {
						anchor: '100%',
						labelStyle: 'padding-left:4px;',
						columns: 4
					},
					items: [{
						xtype : 'checkboxgroup',
						items : baseRights
					},{
						xtype : 'hiddenfield',
						name : 'roleid',
						value : roleid
					}]
				}],
	            buttons: [{
	                text: '保存',
	                action: 'saveAssign'
	            }, {
	                text: '取消',
	                scope: this,
	                handler: this.close
	            }]
			}]
        });
        this.callParent(arguments);
    }
});