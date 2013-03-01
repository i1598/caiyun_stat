//管理员列表
Ext.define('DC.model.adminCenter.AdminModel', {
    extend: 'Ext.data.Model',
   fields: ['id', 'username', 'password', 'realname', 'email', 'role', 'tel', 'lastlogin'],
	idProperty : 'id'
});