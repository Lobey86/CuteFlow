cf.administration_menuesetting = function(){return {
	
		
	themenueSettingModuleWindow			:false,
	isInitialized						:false,
	theModulePanel						:false,

	
	
	/** init function **/
	init:function () {
		if (this.isInitialized == false) {
			this.isInitialized = true;
			cf.menueSettingModuleGrid.init();			
			this.initModulePanel();
			this.initModuleWindow();
			this.theModulePanel.add(cf.menueSettingModuleGrid.theModuleGrid);
			this.themenueSettingModuleWindow.add(this.theModulePanel);
		}
		
	},
	
	
	initModuleWindow: function () {
		this.themenueSettingModuleWindow =  new Ext.Panel({
			modal: true,
			closable: true,
			modal: true,
			autoScroll: true,
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: false,
	        title: 'Menue Settings'
	    });
		
	},
	
	
	initModulePanel: function () {
		this.theModulePanel = new Ext.FormPanel({
			closable: false,
			plain: false,
			frame: false,
			height: 500,
			width: 572,
			layout: 'form',
			title: 'Menue Settings',
			style:'margin-top:5px;margin-left:6px;',
			collapsible:false
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
		return this.themenueSettingModuleWindow;
	}
	
};}();