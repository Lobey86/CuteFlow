cf.workflowdetails = function(){return {
	
	theLoadingMask				:false,
	thePopUpWindow				:false,
	thePanelToShow				:false,
	
	init: function (workflowtemplate_id, version_id, openinpopup, showAction, workflowAdmin) {
		cf.workflowdetails.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Loading Data...',null,'workflowmanagement'); ?>'});					
		cf.workflowdetails.theLoadingMask.show();
		
		
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('workflowdetail/LoadWorkflowDetails')?>/versionid/' + version_id + '/workflowtemplateid/' + workflowtemplate_id,
			success: function(objServerResponse){
				cf.workflowdetails.theLoadingMask.hide();
				var ServerResult = Ext.util.JSON.decode(objServerResponse.responseText);
				var generalData = ServerResult.generalData;
				var detailData = ServerResult.detailData;
				var valueData = ServerResult.workflowData;
				
				
				cf.workflowdetails.thePanelToShow = openinpopup == true ? cf.workflowdetails.initWindow() : '';
				cf.workflowdetailsGeneral.init(generalData, workflowtemplate_id);
				cf.workflowdetailsDetails.init(detailData, workflowAdmin);
				cf.workflowdetailsValue.init(valueData);
				cf.workflowdetails.thePanelToShow.add(cf.workflowdetailsGeneral.theFieldset);
				cf.workflowdetails.thePanelToShow.add(cf.workflowdetailsDetails.theFieldset);
				cf.workflowdetails.thePanelToShow.add(cf.workflowdetailsValue.theFieldset);
				cf.workflowdetails.thePanelToShow.doLayout();
				openinpopup == true ? cf.workflowdetails.thePanelToShow.show() : '';
			}
		});
		
		
		
		
	},
	
	
	initWindow: function () {
		var thePopUpWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: cf.Layout.theRegionWest.getHeight() - 40,
			width: cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 40,
			autoScroll: true,
			title: '<?php echo __('Workflow details',null,'workflowmanagement'); ?>',
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: false,
			close : function(){
				thePopUpWindow.hide();
				thePopUpWindow.destroy();
			}
		});
		return thePopUpWindow;
	}
	
	
	
	
	
	
	
	
	
	
};}();