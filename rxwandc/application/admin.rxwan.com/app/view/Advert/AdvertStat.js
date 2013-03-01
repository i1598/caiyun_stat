/**
 * Description of AllGameToday
 *
 * @author Pluto
 */

Ext.require(['Ext.chart.series.Column','Ext.chart.Chart','Ext.chart.axis.Numeric','Ext.chart.axis.Category','Ext.layout.container.Table']);

Ext.define('DC.view.Advert.AdvertStat', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.advertStat',
    frame: true,
    autoShow:true,
    multiSelect: true,
    initComponent: function() {
        Ext.apply(this, {
        	store : 'Advert.AdvertChatList',
        	layout: 'border',
        	items:[{
		    	xtype : 'chart',
		    	region: 'north',
		    	height:200,
		    	store : 'Advert.AdvertChatList',
		        style: 'background:#fff',
		        animate: true,
		        shadow: true,
		    	axes : [{
		            type: 'Numeric',
		            position: 'left',
		            fields: ['flow'],
		            title: '流量峰值',
		            grid: true,
		            minimum: 0
		        }, {
		            type: 'Category',
		            position: 'bottom',
		            fields: ['duration'],
		            title: '时段'
		        }],
		        series: [{
		            type: 'column',
		            axis: 'left',
		            highlight: true,
		            tips: {
		              trackMouse: true,
		              width: 140,
		              height: 28,
		              renderer: function(storeItem, item) {
		                this.setTitle(storeItem.get('duration') + ': ' + storeItem.get('flow') + '人');
		              	
		              }
		            },
		            label: {
		              display: 'insideEnd',
		              'text-anchor': 'middle',
		                field: 'flow',
		                renderer: Ext.util.Format.numberRenderer('0'),
		                orientation: 'vertical',
		                color: '#333'
		            },
		            xField: 'duration',
		            yField: 'flow'
		        }]
		    },{
	    	    frame: false,
		    	region: 'center',
		    	xtype : 'advertTodayDataList'
			}]
        });
        this.callParent(arguments);
       // alert(flow);
       
    }
    
});