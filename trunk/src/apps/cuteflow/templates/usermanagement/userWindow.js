cf.UserManagement = function(){return {
	
	isInitialized              : false,
	theUserWindow              : false,


	init: function () {
		if (this.isInitialized == false) {
			this.isInitialized = true;
			this.initWindow();
			cf.UserSearchbar.init();
			this.theUserWindow.add(cf.UserSearchbar.theUserSearchbar);
			cf.UserGrid.init();
			this.theUserWindow.add(cf.UserGrid.theGridPanel);
		}
	},
	
	initWindow: function () {
		this.theUserWindow = new Ext.Panel({
				title: '<?php echo __('User management',null,'usermanagement'); ?>',
				closable: true,
				plain: false,
				frame: false,
				autoScroll:true,
				bodyStyle:'margin-top:10px'
		});
	},
	
	setInitialized: function (value) {
		this.isInitialized = value;
	},
	
	getInstance: function() {
		return this.theUserWindow;
	}
	
};}();