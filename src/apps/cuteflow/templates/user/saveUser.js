cf.saveUser = function(){return {
	
	
	theHiddenPanel			:false,
	
	initSave: function (theFormpanel) {
		this.initHiddenPanel();
		this.buildUserAgent(theFormpanel);
		this.doSubmit(theFormpanel);

	},
	
	initHiddenPanel:function () {
		this.theHiddenPanel = new Ext.Panel({
			border: false,
			width:0,
			height:0
		});
		
	},
	
	
	buildUserAgent: function (theFormpanel) {
		var grid = cf.userSecondTab.theUserAgentGrid;
		for(var a=0;a<grid.store.getCount();a++) {
			var row = grid.getStore().getAt(a);
			var hiddenfield = new Ext.form.Field({
				autoCreate : {tag:'input', type: 'hidden', name: 'useragents[]', value:row.data.user_id, width: 0}			
			});
			cf.updateUser.theHiddenPanel.add(hiddenfield);
		}
		theFormpanel.add(cf.updateUser.theHiddenPanel);
		theFormpanel.doLayout();
	},
	
	doSubmit: function (theFormpanel) {
		var username = Ext.getCmp('userFirstTab_username').getValue();
		Ext.Ajax.request({
			url : '<?php echo build_dynamic_javascript_url('usermanagement/CheckForExistingUser')?>/username/' + username,
			success: function(objServerResponse){  
				if(objServerResponse.responseText == 1) {
					theFormpanel.getForm().submit({
						url: '<?php echo build_dynamic_javascript_url('usermanagement/SaveUser')?>',
						method: 'POST',
						success: function(objServerResponse){
							try {
								Ext.destroy.apply(Ext, cf.updateUser.theHiddenPanel.items.items);
								cf.updateUser.theHiddenPanel.items.clear();
								cf.updateUser.theHiddenPanel.body.update('');
							}
							catch(e) {}
							Ext.Msg.minWidth = 200;
							Ext.MessageBox.alert('<?php echo __('OK',null,'usermanagement'); ?>', '<?php echo __('User added',null,'usermanagement'); ?>');
							cf.UserGrid.theUserStore.reload();
							cf.createUserWindow.theAddUserWindow.hide();
							cf.createUserWindow.theAddUserWindow.destroy();
						}
					});
				}
				else {
					Ext.Msg.minWidth = 200;
					Ext.MessageBox.alert('<?php echo __('Error',null,'usermanagement'); ?>', '<?php echo __('Username already exists',null,'usermanagement'); ?>');
					cf.TabPanel.theTabPanel.setActiveTab(0);
				}
			}
		});
	}
	
	
	
	
	
	
	
	
	
};}();