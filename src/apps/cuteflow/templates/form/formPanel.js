/** init main window **/
cf.management_form = function(){return {
	
	isInitialized 	                 : false,
	theFormPanel             		 : false,
	
	
	/** init grid and window **/
	init: function () {
		if (this.isInitialized == false) {
			this.isInitialized = true;
			cf.formPanelGrid.init();
			this.initPanel();
			this.theFormPanel.add(cf.formPanelGrid.theFormGrid);
		}
	},
	
	/** inits the panel for grid **/
	initPanel: function () {
		this.theFormPanel = new Ext.Panel({
			title: '<?php echo __('Document templates Management',null,'form'); ?>',
			closable: true,
			plain: true,
			frame: false,
			layout: 'fit',
			autoScroll:false,
			bodyStyle:'margin-top:1px;'
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
		return this.theFormPanel;
	}
	
};}();