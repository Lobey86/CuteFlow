cf.AddUserWindow = function(){return {
	
	theAddUserWindow					:false,
	theAddUserWindowIsInitialized		:false,
	
	init: function () {
		this.initWindow();
		this.theAddUserWindow.show();
	},
	
	initWindow: function() {
		this.theAddUserWindowIsInitialized = true;
		this.theAddUserWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: 600,
			width: 800,
			title: '<?php echo __('Add new user',null,'usermanagement'); ?>',
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: false
		});
	}
	
	
	
};}();