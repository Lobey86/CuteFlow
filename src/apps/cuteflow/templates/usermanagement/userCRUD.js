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
	}







};}();