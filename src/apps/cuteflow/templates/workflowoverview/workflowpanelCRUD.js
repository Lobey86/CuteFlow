cf.workflowmanagementPanelCRUD = function(){return {
	
	theLoadingMask					:false,
	
	stopWorkflow: function (workflow_id, version_id) {
		cf.workflowmanagementPanelCRUD.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Stopping workflow...',null,'workflowmanagement'); ?>'});					
		cf.workflowmanagementPanelCRUD.theLoadingMask.show();
		
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('workflowoverview/StopWorkflow')?>/versionid/' + version_id + '/workflowtemplateid/' + workflow_id,
			success: function(objServerResponse){
				cf.workflowmanagementPanelGrid.theWorkflowStore.reload();
				cf.workflowmanagementPanelCRUD.theLoadingMask.hide();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'workflowmanagement'); ?>', '<?php echo __('Workflow stopped',null,'workflowmanagement'); ?>');
			}
		});
	},
	
	
	startWorkflow: function (version_id) {
		cf.workflowmanagementPanelCRUD.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Starting workflow...',null,'workflowmanagement'); ?>'});					
		cf.workflowmanagementPanelCRUD.theLoadingMask.show();
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('workflowoverview/StartWorkflow')?>/versionid/' + version_id,
			success: function(objServerResponse){
				cf.workflowmanagementPanelGrid.theWorkflowStore.reload();
				cf.workflowmanagementPanelCRUD.theLoadingMask.hide();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'workflowmanagement'); ?>', '<?php echo __('Workflow has been started',null,'workflowmanagement'); ?>');
			}
		});	
		
	}
	
	
	
	
	
	
};}();