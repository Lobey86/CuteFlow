cf.AddRoleWindow = function(){return {
	
	theAddRoleWin						:false,
	theAddRoleWindowIsInitialzied		:false,
	
	init: function () {
		this.initWindow();
		cf.AddRoleTabpanel.init();
		this.theAddRoleWin.add(cf.AddRoleTabpanel.theTabpanel);
		this.theAddRoleWin.show();
	},
	
	
	initWindow: function() {
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
	        buttonAlign: 'center',
			buttons:[{
	                    id: 'addButton',
						text:'<?php echo __('Speichern',null,'userrolemanagement'); ?>', 
						icon: '/images/icons/accept.png',
						handler: function () {
							
						}
					},{
	                    id: 'cancelButton',
						text:'<?php echo __('Verwerfen',null,'userrolemanagement'); ?>', 
						icon: '/images/icons/cancel.png',
						handler: function () {
							cf.AddRoleWindow.theAddRoleWin.hide();
						}
					}]
		});
			
	}
	
	
};}();