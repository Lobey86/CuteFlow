cf.workflowdetailsCRUD = function(){return {
	
	theLoadingMask					:false,
	
	
	
	skipStation: function (id, templateversion_id, workflowslot_id, workflowuser_id) {
		cf.workflowdetailsCRUD.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Updating Data...',null,'workflowmanagement'); ?>'});					
		cf.workflowdetailsCRUD.theLoadingMask.show();
		//alert('<?php echo build_dynamic_javascript_url('workflowdetail/SkipStation')?>/versionid/' + templateversion_id + '/workflowprocessuserid/' + id);
		
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('workflowdetail/SkipStation')?>/versionid/' + templateversion_id + '/workflowprocessuserid/' + id + '/workflowslotid/' + workflowslot_id + '/workflowslotuserid/' + workflowuser_id,
			success: function(objServerResponse){
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'workflowmanagement'); ?>', '<?php echo __('Station skipped',null,'workflowmanagement'); ?>');
				var ServerResult = Ext.util.JSON.decode(objServerResponse.responseText);
				var detailData = ServerResult.detailData;
				cf.workflowdetailsCRUD.reloadData(detailData);
			}
		});
	},
	
	
	setUseragent: function (user_id, workflowuserprocessid, templateversion_id) {
		cf.workflowdetailsCRUD.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Updating Data...',null,'workflowmanagement'); ?>'});					
		cf.workflowdetailsCRUD.theLoadingMask.show();
	
		
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('workflowdetail/SetUseragent')?>/userid/' + user_id + '/workflowprocessuserid/' + workflowuserprocessid + '/versionid/' + templateversion_id,
			success: function(objServerResponse){
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'workflowmanagement'); ?>', '<?php echo __('Useragent set',null,'workflowmanagement'); ?>');
				var ServerResult = Ext.util.JSON.decode(objServerResponse.responseText);
				var detailData = ServerResult.detailData;
				cf.workflowdetailsCRUD.reloadData(detailData);
			}
		});
		
	},
	
	
	
	reloadData: function (detailData) {
		cf.workflowdetailsDetails.theGrid.destroy();
		cf.workflowmanagementPanelGrid.theWorkflowStore.reload();
		cf.workflowdetailsDetails.initCM();
		cf.workflowdetailsDetails.initStore();
		cf.workflowdetailsDetails.initGrid(detailData);
		cf.workflowdetailsDetails.theFieldset.add(cf.workflowdetailsDetails.theGrid);
		cf.workflowdetailsDetails.theFieldset.doLayout();
		cf.workflowdetailsCRUD.theLoadingMask.hide();
		try {
			cf.workflowdetailsSelectUseragent.thePopUpWindow.hide();
			cf.workflowdetailsSelectUseragent.thePopUpWindow.destroy();
		}
		catch(e) {}
	}
	
	
	
	
	
	
};}();