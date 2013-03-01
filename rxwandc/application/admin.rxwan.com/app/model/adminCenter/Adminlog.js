//管理员列表
Ext.define('DC.model.adminCenter.Adminlog', {
    extend: 'Ext.data.Model',
    fields: ['id', 'username', 'action','create_date'],
	idProperty : 'id'
});