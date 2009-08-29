cf.SaveRole = function(){return {




save: function (new_flag) {
	
	if (new_flag != 1) {
		alert("edit");
		// store edit.
	}
	
	var textfield = Ext.getCmp('userrole_title_id');
	if(textfield.getValue() == '') {
		cf.AddRoleTabpanel.theTabpanel.setActiveTab(0);
		textfield.focus();
	}
	else {
		Ext.Ajax.request({  
			url : '<?php echo url_for('userrolemanagement/CheckForExistingRole')?>/description/' + textfield.getValue(),
			success: function(objServerResponse){
				if(objServerResponse.responseText == 1) {
					cf.AddRoleTabpanel.theFormPanel.getForm().submit({
						url: '<?php echo url_for('userrolemanagement/AddRole')?>',
						method: 'POST',
						success: function() {
							cf.UserRoleGrid.theUserRoleStore.reload();
							cf.AddRoleWindow.theAddRoleWin.hide();
							cf.AddRoleWindow.theAddRoleWin.destroy();
							try {
								cf.UserRoleGrid.theUserRoleStore.load();
							}
							catch(e) {
							}
						}
					});
				}
				else {
					Ext.MessageBox.alert('<?php echo __('Error',null,'userrolemanagement'); ?>', '<?php echo __('Role is already existing',null,'userrolemanagement'); ?>');
					cf.AddRoleTabpanel.theTabpanel.setActiveTab(0);
					textfield.focus();
					textfield.setValue();
				}
			}
		});
	}
	
}




};}();