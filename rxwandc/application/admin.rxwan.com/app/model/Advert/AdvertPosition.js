//管理员列表
Ext.define('DC.model.Advert.AdvertPosition', {
    extend: 'Ext.data.Model',
   fields: ['id', 'title','dateline','deleted','label','advert_zone_id'],
	idProperty : 'id'
});