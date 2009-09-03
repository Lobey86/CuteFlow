/**
* CRUD Functionality for Rolemangement
*
*/
cf.RoleCRUD = function(){return {



	/** delete role from database **/
	deleteRole: function (id) {
		cf.DeleteRoleWindow.init(id);
	},



	
	/**
	* save or update an exisiting role
	*
	* @param boolean new_flag, if 1 then a new record will be created, if 0 then a exisitng record is edited
	* @param int id, is only set, if a record is edited
	*
	*/
	saveRole: function (new_flag,id) {
		var textfield = Ext.getCmp('userrole_title_id');
		if(textfield.getValue() == '') { // no role name is set
			cf.AddRoleTabpanel.theTabpanel.setActiveTab(0);
			textfield.focus();
		}
		else { // role name is set
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
						if(objServerResponse.responseText == 1) { // save Role
							cf.theAddUserWindow.theFormPanel.getForm().submit({
								url: '<?php echo url_for('userrolemanagement/AddRole')?>',
								method: 'POST',
								success: function() {
									cf.UserRoleGrid.theUserRoleStore.reload();
									cf.AddRoleWindow.theAddRoleWin.hide();
									cf.AddRoleWindow.theAddRoleWin.destroy();
								}
							});
						}
						else { // role is already exisiting
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