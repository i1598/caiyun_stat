//管理员列表
Ext.define('DC.model.Advert.AdvertTodayDataList', {
    extend: 'Ext.data.Model',
    fields: ['report_day_id','dateline','referer_dateline','uv','pv','click_unique','click','advert_id'],
	idProperty : 'report_day_id'
});