/**
* Class implements the CRUD Functions for the users.
*
*/ 
cf.UserCRUD = function(){return {
	
	/** delete selected users from the grid and database **/
	deleteUser: function () {
		var grid = cf.UserGrid.theUserGrid;
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
	
	/**
	* Function saves user to databse or saves changes in userprofile to database
	*
	* @param boolean new_flag, if 1 then new user will be created, if 0 then edit function is called and userprofile update will be done
	* @param int id, id is set, when editing user.
	*
	*/
	saveUser: function (new_flag, id) {
		var firstname = Ext.getCmp('firstname');
		var lastname = Ext.getCmp('lastname');
		var email = Ext.getCmp('email');
		var username =  Ext.getCmp('username');
		var password1 = Ext.getCmp('password');
		var password2 = Ext.getCmp('passwordAgain');
		var role = Ext.getCmp('userrole');
		var checkPW = this.checkPassword(password1.getValue(), password2.getValue());
		
		if (firstname.getValue() == '' || lastname.getValue() == '' || email.getValue() == '' || username.getValue() == '' || password1.getValue() == '' || password2.getValue() == '' || role.getValue() == '' || checkPW == false) {
			if (checkPW == false) {
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('Password Failure',null,'usermanagementpopup'); ?>', '<?php echo __('Passwords are not equal',null,'usermanagementpopup'); ?>');
			}
			cf.AddUserWindow.theTabpanel.setActiveTab(0);
		}
		else { // start of save process
			if(cf.AddUserThirdTab.isInitialized == true) {
				this.buildUserAgentFields();
			}
			else  {
				var hiddenfield = new Ext.form.Field({
					autoCreate : {tag:'input', type: 'hidden', name: 'useragent_edit', value:-1, width: 0}			
				});
				cf.AddUserWindow.theFormPanel.add(hiddenfield);
				cf.AddUserWindow.theFormPanel.doLayout();
			}
			
			if(new_flag == 1) {
				// new
				var url = '<?php echo url_for('usermanagement/CheckForExistingUser')?>/username/' + username.getValue();
				Ext.Ajax.request({  // check if username is already stored in database
					url: url,
					success: function(objServerResponse){
						if(objServerResponse.responseText == 1) {
							cf.AddUserWindow.theFormPanel.getForm().submit({
								url: '<?php echo url_for('usermanagement/AddUser')?>',
								method: 'POST',
								success: function() {
									cf.AddUserThirdTab.isInitialized  = false;
									cf.UserGrid.theUserStore.reload();
									cf.AddUserWindow.theAddUserWindow.hide();
									cf.AddUserWindow.theAddUserWindow.destroy();
								}
							});
						}
						else {
							Ext.MessageBox.alert('<?php echo __('Error',null,'usermanagementpopup'); ?>', '<?php echo __('Username is alreasy existing',null,'usermanagementpopup'); ?>');
							cf.AddUserWindow.theTabpanel.setActiveTab(0);
							username.focus();
							username.setValue();
						}
					}
				});
			}
			else {
				// edit
				cf.AddUserWindow.theFormPanel.getForm().submit({
					url: '<?php echo url_for('usermanagement/EditUser')?>',
					method: 'POST',
					success: function(objServerResponse){
						cf.AddUserThirdTab.isInitialized  = false;
						cf.UserGrid.theUserStore.reload();
						cf.AddUserWindow.theAddUserWindow.hide();
						cf.AddUserWindow.theAddUserWindow.destroy();
					}
				});
			}
			
		}
	},
	/** checks for equals password **/
	checkPassword: function (pw1,pw2) {
		if (pw2 == pw1) {
			return true;
		}
		else {
			return false;
		}
		
	},
	
	buildUserAgentFields: function() {
		for(var a=0;a<cf.AddUserThirdTab.theRightGrid.store.getCount();a++) {
		
			var row = cf.AddUserThirdTab.theRightGrid.getStore().getAt(a);
			var hiddenfield = new Ext.form.Field({
				autoCreate : {tag:'input', type: 'hidden', name: 'grid[]', value:row.data.user_id, width: 0}			
			});
			cf.AddUserWindow.theFormPanel.add(hiddenfield);
			
		}
		cf.AddUserWindow.theFormPanel.doLayout();
	}







};}();