//管理员列表
Ext.define('DC.model.Stat.StatPerDay', {
    extend: 'Ext.data.Model',
   fields: ['report_day_id', 'dateline', 'quantity_install', 'quantity_uninstall', 'quantity_active', 'quantity_firstopen', 'soft_id', 'lastlogin','soft_name'],
	idProperty : 'report_day_id'
});