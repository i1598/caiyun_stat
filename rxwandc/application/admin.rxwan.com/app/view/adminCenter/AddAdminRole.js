/**
 * build a window to add user.
 *
 * @author Pluto
 * @version 1.1
 */

Ext.define('DC.view.adminCenter.AddAdminRole', {
    extend: 'Ext.window.Window',
    alias: 'widget.addAdminRole',
    title: '添加管理员角色',
    width: 280,
    layout: 'fit',
    autoShow: true,
    modal: true,
    initComponent: function() {
        this.addEvents('create');
        Ext.apply(this, {
            items: [{
                xtype: 'form',
                items: [{
                    xtype: 'textfield',
                    name: 'name',
                    margin: '5 5 5 5',
                    fieldLabel: '角色名称'
                }]
            }],
            buttons: [{
                text: '保存',
                action: 'saveAdminRole'
            }, {
                text: '取消',
                scope: this,
                handler: this.close
            }]

        });
        this.callParent(arguments);
    }
});