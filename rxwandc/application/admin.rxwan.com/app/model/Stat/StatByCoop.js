//管理员列表
Ext.define('DC.model.Stat.StatByCoop', {
    extend: 'Ext.data.Model',
   fields: ['id', 'title', 'username', 'is_pay', 'is_notice', 'is_stop', 'url', 'is_audit','category_id','total_install','total_uninstall','total_active'],
	idProperty : 'id'
});