/**
* Main Class for the group "Usermanagement". This class initializes the searchbar and the grid
* where all data will be displayed.
*
*/
cf.UserManagement = function(){return {
	
	isInitialized              : false,
	theUserWindow              : false,

	/** inits searchbar and grid **/
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
	
	/** inits the Panel, where searchbar and grid will be added. Panel is displayed in Tabpanel **/
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
	
	/** 
	 * Part of the API
	 * set value if class is already initialized. 
	 * @param boolean value
	 *
	 **/
	setInitialized: function (value) {
		this.isInitialized = value;
	},
	
	
	/**
	* Part of the API
	* This function returns the window, to add it into tabpanel
	*
	*/
	getInstance: function() {
		return this.theUserWindow;
	}
	
};}();