cf.restartWorkflowCRUD = function(){return {
	
	
	doSubmit: function (versionid) {
		Ext.getCmp('restartWorkflowFirstTab_fieldset2').expand();
		cf.restartWorkflowFirstTab.theFirstTabPanel.getForm().submit({
			url: '<?php echo build_dynamic_javascript_url('restartworkflow/RestartWorkflow')?>/versionid/' + versionid,
			method: 'POST',
			//waitMsg: '<?php echo __('Saving Data',null,'usermanagement'); ?>',
			success: function(objServerResponse){
				/*cf.additionalTextGrid.theTextStore.reload();
				Ext.Msg.minWidth = 200;
				cf.additionalTextPopUpWindow.thePopUpWindow.hide();
				cf.additionalTextPopUpWindow.thePopUpWindow.destroy();
				Ext.MessageBox.alert('<?php echo __('OK',null,'additionaltext'); ?>', '<?php echo __('Text saved',null,'additionaltext'); ?>');*/

			}
		});
	}
	
	
	
	
	
};}();