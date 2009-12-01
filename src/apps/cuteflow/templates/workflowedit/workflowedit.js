cf.workflowedit = function(){return {
	
	theLoadingMask				:false,
	thePopUpWindow				:false,
	thePanelToShow				:false,
	
	init: function (workflowtemplate_id, version_id) {
		//cf.workflowdetails.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Loading Data...',null,'workflowmanagement'); ?>'});					
		//cf.workflowdetails.theLoadingMask.show();
		this.initWindow();
		this.thePopUpWindow.show();
		
		
		
	},
	
	
	initWindow: function () {
		this.thePopUpWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: cf.Layout.theRegionWest.getHeight() - 40,
			width: cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 40,
			autoScroll: true,
			title: '<?php echo __('Edit workflow',null,'workflowmanagement'); ?>',
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: false,
			close : function(){
				cf.workflowedit.thePopUpWindow.hide();
				cf.workflowedit.thePopUpWindow.destroy();
			}
		});
	}
	
	
	
	
	
	
	
	
	
	
};}();