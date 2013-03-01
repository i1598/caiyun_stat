//管理员权限
Ext.define('DC.model.adminCenter.RightsModel', {
    extend: 'Ext.data.Model',
    fields: ['id', 'text','category_id','widget','ishas'],
	idProperty : 'id'
});