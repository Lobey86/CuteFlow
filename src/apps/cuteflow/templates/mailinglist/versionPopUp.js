cf.mailinglistVersionPopUp = function(){return {
	
	theVersionWindow				:false,
	theTabPanel						:false,
	
	init: function (parent_id) {
		this.initWindow();	
		cf.mailinglistVersionFirstTab.init(parent_id);
		this.initTabPanel();
		this.theTabPanel.add(cf.mailinglistVersionFirstTab.thePanel);
		this.theVersionWindow.add(this.theTabPanel);
		this.theVersionWindow.show();		
	},
	
	
	initWindow: function () {
		this.theVersionWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: cf.Layout.theRegionWest.getHeight() - 40,
			width: 820,
			autoScroll: false,
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: true,
			title: '<?php echo __('Set Mailinglist to previous version',null,'mailinglist'); ?>'
		});
		this.theVersionWindow.on('close', function() {
			cf.mailinglistVersionPopUp.theVersionWindow.hide();
			cf.mailinglistVersionPopUp.theVersionWindow.destroy();
		});
		
	},
	
	initTabPanel: function () {
		this.theTabPanel = new Ext.TabPanel({
			activeTab: 0,
			enableTabScroll:true,
			border: false,
			deferredRender:true,
			frame: true,
			layoutOnTabChange: true,
			style: 'margin-top:5px;',
			plain: false,
			closable:false
		});	
	}
	
	
		
};}();