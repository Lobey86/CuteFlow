cf.RoleCRUD = function(){return {




deleteRole: function (id) {
	cf.DeleteRoleWindow.init(id);
},



	

saveRole: function (new_flag,id) {
	var textfield = Ext.getCmp('userrole_title_id');
	if(textfield.getValue() == '') {
		cf.AddRoleTabpanel.theTabpanel.setActiveTab(0);
		textfield.focus();
	}
	else {
		if (new_flag != 1) { // edit role
			
			cf.AddRoleTabpanel.theFormPanel.getForm().submit({
				url: '<?php echo url_for('userrolemanagement/EditRole')?>',
				method: 'POST',
				success: function() {
					//cf.UserRoleGrid.theUserRoleStore.reload();
					cf.AddRoleWindow.theAddRoleWin.hide();
					cf.AddRoleWindow.theAddRoleWin.destroy();
				}
			});
		}
		else { // new role
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
}




};}();