cf.systemSettingCRUD = function(){return {
	
	
	
	
	initSave: function () {
			
		cf.administration_systemsetting.theFormPanel.getForm().submit({
			url: '<?php echo build_dynamic_javascript_url('systemsetting/SaveSystem')?>',
			method: 'POST',
			success: function(objServerResponse){
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'systemsetting'); ?>', '<?php echo __('Settings saved',null,'systemsetting'); ?>');
			}
		});
		
		
	}
};}();