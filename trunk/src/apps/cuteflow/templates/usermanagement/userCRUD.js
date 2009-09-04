/**
* Class implements the CRUD Functions for the users.
*
*/ 
cf.UserCRUD = function(){return {
	
	/** delete selected users from the grid and database **/
	deleteUser: function () {
		var grid = Ext.getCmp('grid');
		var rows = grid.getSelectionModel().getSelections();
		if (rows.length > 0) {
			for(var i=0;i<rows.length;i++) {
				var r = rows[i];
				var deleteurl = '<?php echo url_for('usermanagement/DeleteUser')?>/id/' + r.get('id');
				if(r.get('id') != '<?php echo $sf_user->getAttribute('id')?>') {
					cf.UserGrid.theUserStore.remove(rows[i]);
					Ext.Ajax.request({  
						url : deleteurl
					});
				}
			}
			Ext.Msg.minWidth = 200;
			if(r.get('id') != '<?php echo $sf_user->getAttribute('id')?>') {
				Ext.MessageBox.alert('<?php echo __('OK',null,'usermanagement'); ?>', '<?php echo __('Delete Success',null,'usermanagement'); ?>');
			}
			else {
				Ext.MessageBox.alert('<?php echo __('Error',null,'usermanagement'); ?>', '<?php echo __('Deleting own account not working',null,'usermanagement'); ?>');
			}
		}
	},
	
	/** edit user **/
	editUser: function (userId) {
		alert(userId);
	},
	
	saveUser: function (new_flag, id) {
		var firstname = Ext.getCmp('firstname');
		var lastname = Ext.getCmp('lastname');
		var email = Ext.getCmp('email');
		var username =  Ext.getCmp('username');
		var password1 = Ext.getCmp('password');
		var password2 = Ext.getCmp('passwordAgain');
		var role = Ext.getCmp('userrole');
		
		if (firstname.getValue() == '' || lastname.getValue() == '' || email.getValue() == '' || username.getValue() == '' || password1.getValue() == '' || password2.getValue() == '' || role.getValue() == '') {
			cf.AddUserWindow.theTabpanel.setActiveTab(0);
		}
		else {
			if(new_flag == 1) {
				// new
				var url = '<?php echo url_for('usermanagement/CheckForExistingUser')?>/username/' + username.getValue();
				Ext.Ajax.request({  
					url: url,
					success: function(objServerResponse){
						if(objServerResponse.responseText == 1) {
							cf.AddUserWindow.theFormPanel.getForm().submit({
								url: '<?php echo url_for('usermanagement/AddUser')?>',
								method: 'POST',
								success: function() {
									cf.UserGrid.theUserStore.reload();
									cf.AddUserWindow.theAddUserWindow.hide();
									cf.AddUserWindow.theAddUserWindow.destroy();
								}
							});
						}
						else {
							Ext.MessageBox.alert('Error', 'Benutzername existiert bereits');
							cf.AddUserWindow.theTabpanel.setActiveTab(0);
							username.focus();
							username.setValue();
						}
					}
				});
			}
			else {
				cf.AddUserWindow.theFormPanel.getForm().submit({
					url: '<?php echo url_for('usermanagement/EditUser')?>',
					method: 'POST',
					success: function(objServerResponse){
						cf.UserGrid.theUserStore.reload();
						cf.AddUserWindow.theAddUserWindow.hide();
						cf.AddUserWindow.theAddUserWindow.destroy();
					}
				});
			}
			
		}
		
		
		
	}







};}();