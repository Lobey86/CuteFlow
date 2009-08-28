cf.UserRoleManagement = function(){return {
	
	isInitialized 	                 : false,
	theManagementWindow              : false,
	
	
	init: function () {
		if (this.isInitialized == false) {
			this.isInitialized = true;
			this.initWindow();
			cf.UserRoleGrid.init();
			this.theManagementWindow.add(cf.UserRoleGrid.theUserRoleGrid);
		}
	},
	
	initWindow: function () {
		this.theManagementWindow = new Ext.Panel({
				title: '<?php echo __('Role management',null,'userrolemanagement'); ?>',
				closable: true,
				plain: true,
				frame: false,
				autoScroll:true,
				bodyStyle:'margin-top:10px'
		});
	},
	
	setInitialized: function (value) {
		this.isInitialized = value;
	},
	
	getInstance: function() {
		return this.theManagementWindow;
	}
	
};}();