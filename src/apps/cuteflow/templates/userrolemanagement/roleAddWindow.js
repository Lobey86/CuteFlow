cf.AddRoleWindow = function(){return {
	
	theAddRoleWin						:false,
	theAddRoleWindowIsInitialzied		:false,
	
	init: function (new_flag) {
		this.initWindow(new_flag);
		cf.AddRoleTabpanel.init();
		this.theAddRoleWin.add(cf.AddRoleTabpanel.theFormPanel);
		this.theAddRoleWin.show();
	},
	
	
	initWindow: function(new_flag) {
		this.theAddRoleWindowIsInitialzied = true;
		this.theAddRoleWin = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: 700,
			width: 650,
			autoScroll: true,
			title: '<?php echo __('Add new Userrole',null,'userrolemanagement'); ?>',
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: false,
			id: 'mainWindow_id',
	        buttonAlign: 'center',
			close : function(){
				cf.AddRoleWindow.theAddRoleWin.hide();
				cf.AddRoleWindow.theAddRoleWin.destroy();
			},
			buttons:[{
				id: 'addButton',
				text:'<?php echo __('Store',null,'userrolemanagement'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.RoleCRUD.saveRole(new_flag);
				}
			},{
				id: 'cancelButton',
				text:'<?php echo __('Close',null,'userrolemanagement'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.AddRoleWindow.theAddRoleWin.hide();
					cf.AddRoleWindow.theAddRoleWin.destroy();
				}
			}]
		});
	}
};}();