cf.workflowedit = function(){return {
	
	theLoadingMask				:false,
	thePopUpWindow				:false,
	thePanel					:false,
	theHiddenfield				:false,
	
	
	init: function (workflowtemplate_id, version_id) {
		cf.workflowedit.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Loading Data...',null,'workflowmanagement'); ?>'});					
		cf.workflowedit.theLoadingMask.show();
		
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('workflowedit/LoadWorkflowData')?>/versionid/' + version_id + '/workflowtemplateid/' + workflowtemplate_id,
			success: function(objServerResponse){
				cf.workflowedit.initPanel();
				var ServerResult = Ext.util.JSON.decode(objServerResponse.responseText);
				var generalData = ServerResult.generalData;
				var slotData = ServerResult.slotData;
				var attachments = ServerResult.workflowAttachment;
				cf.workflowedit.initWindow(workflowtemplate_id, version_id);
				cf.workfloweditGeneral.init(generalData, workflowtemplate_id);
				cf.workfloweditSlot.init(slotData);
				cf.workfloweditAcceptWorkflow.init();
				cf.workfloweditAttachments.init(attachments);
				
				cf.workflowedit.thePanel.add(cf.workfloweditGeneral.theFieldset);
				cf.workflowedit.thePanel.add(cf.workfloweditAcceptWorkflow.theFieldset);
				cf.workflowedit.thePanel.add(cf.workfloweditAttachments.theFieldset);
				cf.workflowedit.thePanel.add(cf.workfloweditSlot.theFieldset);
				cf.workflowedit.thePopUpWindow.add(cf.workflowedit.thePanel);
				cf.workflowedit.thePopUpWindow.show();
				cf.workflowedit.theLoadingMask.hide();
			}
		});
		
	},
	
	
	initPanel: function () {
		this.thePanel = new Ext.FormPanel({
			frame: true,
			width: 'auto',
			height: 'auto'
		});	
		
	},
	
	initWindow: function (workflowtemplate_id, version_id) {
		this.thePopUpWindow = new Ext.Window({
			modal: true,
			closable: true,
			height: cf.Layout.theRegionWest.getHeight() + cf.Layout.theRegionNorth.getHeight() - 40,
			width: cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 40,
			autoScroll: true,
			title: '<?php echo __('Edit workflow',null,'workflowmanagement'); ?>',
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: false,
	        buttonAlign: 'center',
			buttons:[{
				text:'<?php echo __('Store',null,'documenttemplate'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.workfloweditCRUD.createSavePanel(workflowtemplate_id, version_id);
				}
			},{
				text:'<?php echo __('Close',null,'documenttemplate'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.workflowedit.thePopUpWindow.hide();
					cf.workflowedit.thePopUpWindow.destroy();
				}
			}],
			close : function(){
				cf.workflowedit.thePopUpWindow.hide();
				cf.workflowedit.thePopUpWindow.destroy();
			}
		});
	}
	
	
	
	
	
	
	
	
	
	
};}();