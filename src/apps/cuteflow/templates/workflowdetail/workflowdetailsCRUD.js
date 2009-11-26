cf.workflowdetailsCRUD = function(){return {
	
	theLoadingMask					:false,
	
	
	
	skipStation: function (id, templateversion_id) {
		cf.workflowdetailsCRUD.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Updating Data...',null,'workflowmanagement'); ?>'});					
		cf.workflowdetailsCRUD.theLoadingMask.show();
		//alert('<?php echo build_dynamic_javascript_url('workflowdetail/SkipStation')?>/versionid/' + templateversion_id + '/workflowprocessuserid/' + id);
		
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('workflowdetail/SkipStation')?>/versionid/' + templateversion_id + '/workflowprocessuserid/' + id,
			success: function(objServerResponse){
				var ServerResult = Ext.util.JSON.decode(objServerResponse.responseText);
				var detailData = ServerResult.detailData;
				cf.workflowdetailsDetails.theGrid.destroy();
				
				cf.workflowdetailsDetails.initCM();
				cf.workflowdetailsDetails.initStore();
				cf.workflowdetailsDetails.initGrid(detailData);
				cf.workflowdetailsDetails.theFieldset.add(cf.workflowdetailsDetails.theGrid);
				cf.workflowdetailsDetails.theFieldset.doLayout();
				cf.workflowdetailsCRUD.theLoadingMask.hide();
				
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'workflowmanagement'); ?>', '<?php echo __('Station skipped',null,'workflowmanagement'); ?>');
			}
		});
		
		
	}
	
	
	
	
	
};}();