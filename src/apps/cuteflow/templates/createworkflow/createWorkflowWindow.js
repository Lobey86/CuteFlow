cf.createWorkflowWindow = function(){return {
	
	theCreateWorkflowWindow			:false,
	theTabPanel						:false,
	
	
	init: function() {
		this.initTabPanel();
		this.initWindow();
		cf.createWorkflowFirstTab.init();
		this.theTabPanel.add(cf.createWorkflowFirstTab.theFirstTabPanel);
		this.theCreateWorkflowWindow.add(this.theTabPanel);
		this.theCreateWorkflowWindow.show();
		
	},
	
	
	
	/**
	* init the popupwindow
	*
	* @param int id, id is set if in edit mode
	* @param string title, title of window
	*/
	initWindow: function () {
		this.theCreateWorkflowWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: cf.Layout.theRegionWest.getHeight() - 40,
			width: cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 40,
			autoScroll: false,
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: true,
			title:  '<?php echo __('Create new workflow',null,'documenttemplate'); ?>',
	        buttonAlign: 'center',
			buttons:[{
				text:'<?php echo __('Store',null,'documenttemplate'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.createWorkflowCRUD.initSave();
				}
			},{
				text:'<?php echo __('Close',null,'documenttemplate'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.createWorkflowWindow.theCreateWorkflowWindow.hide();
					cf.createWorkflowWindow.theCreateWorkflowWindow.destroy();
				}
			}]
		});
		this.theCreateWorkflowWindow.on('close', function() {
			cf.createWorkflowWindow.theCreateWorkflowWindow.hide();
			cf.createWorkflowWindow.theCreateWorkflowWindow.destroy();
		});
	},
	
	/** init tabpanel **/
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