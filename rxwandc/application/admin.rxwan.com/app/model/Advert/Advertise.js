//管理员列表
Ext.define('DC.model.Advert.Advertise', {
    extend: 'Ext.data.Model',
   fields: ['id', 'is_delete','dateline','is_publish','status','advert_position_id','info','url','path','start_time','end_time','weight','type'],
	idProperty : 'id'
});