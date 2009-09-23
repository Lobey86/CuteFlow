/** init the main window where grid is added **/
cf.management_additionaltext = function(){return {

	theAdditionTextPanel				:false,
	theMainPanel						:false,
	isInitialized						:false,





	/** load all necessarry functions **/
	init:function () {
		cf.additionalTextGrid.init();
		this.initAdditionalTextPanel();
		this.theAdditionTextPanel.add(cf.additionalTextGrid.theTextGrid);
		this.initMainPanel();
		this.theMainPanel.add(this.theAdditionTextPanel);
	},
	
	
	/** main panel, where theAdditionTextPanel is binded to **/
	initMainPanel: function() {
		if (this.isInitialized == false) {
			this.isInitialized = true;
			this.theMainPanel = new Ext.Panel({
				modal: true,
				closable: true,
				modal: true,
				margins:'5 5 5 5',
				layout: 'fit',
				autoScroll: true,
				title: '<?php echo __('Additional Text',null,'additionaltext'); ?>',
				shadow: false,
				minimizable: false,
				autoScroll: false,
				draggable: false,
				resizable: false,
				plain: false
			});
		}
	},
	
	/** panel is added to mainpanel. this panel contains the grid **/
	initAdditionalTextPanel: function () {
		this.theAdditionTextPanel = new Ext.Panel({
			modal: true,
			closable: true,
			modal: true,
			layout: 'fit',
			margins:'5 5 5 5',
			autoScroll: false,
			title: '<?php echo __('Additional Text',null,'additionaltext'); ?>',
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: false,
	        buttonAlign: 'center'
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
		return this.theMainPanel;
	}
	
	
	
};}();