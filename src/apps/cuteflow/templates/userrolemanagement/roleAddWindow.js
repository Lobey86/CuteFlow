cf.AddRoleWindow = function(){return {
	
	theAddRoleWin						:false,
	theAddRoleWindowIsInitialzied		:false,
	
	init: function (new_flag, id) {
		this.initWindow(new_flag,id);
		cf.AddRoleTabpanel.init(id);
		this.theAddRoleWin.add(cf.AddRoleTabpanel.theFormPanel);
		this.theAddRoleWin.show();
	},
	
	
	initWindow: function(new_flag,id) {
		if(new_flag == 1) {
			var title = '<?php echo __('Add new Userrole',null,'userrolemanagementpopup'); ?>';
		}
		else {
			var title = '<?php echo __('Edit Userrole',null,'userrolemanagementpopup'); ?>';
		}
		
		this.theAddRoleWindowIsInitialzied = true;
		this.theAddRoleWin = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: 700,
			width: 650,
			autoScroll: true,
			title: title,
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
				text:'<?php echo __('Store',null,'userrolemanagementpopup'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.RoleCRUD.saveRole(new_flag,id);
				}
			},{
				id: 'cancelButton',
				text:'<?php echo __('Close',null,'userrolemanagementpopup'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.AddRoleWindow.theAddRoleWin.hide();
					cf.AddRoleWindow.theAddRoleWin.destroy();
				}
			}]
		});
	}
};}();