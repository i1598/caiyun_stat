//管理员角色
Ext.define('DC.model.adminCenter.AdminRoleModel', {
    extend: 'Ext.data.Model',
    fields: ['id', 'name','resource_id'],
	idProperty : 'id'
});