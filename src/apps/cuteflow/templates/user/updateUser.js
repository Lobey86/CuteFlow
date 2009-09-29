cf.updateUser = function(){return {
	
	theHiddenPanel			:false,
	
	initSave: function (theFormpanel, user_id) {
		this.initHiddenPanel();
		this.buildUserAgent(theFormpanel);
		this.doSubmit(theFormpanel, user_id);

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
	
	doSubmit: function (theFormpanel, user_id) {
		theFormpanel.getForm().submit({
			url: '<?php echo build_dynamic_javascript_url('usermanagement/UpdateUser')?>/id/' + user_id,
			method: 'POST',
			success: function(objServerResponse){
				try {
					Ext.destroy.apply(Ext, cf.updateUser.theHiddenPanel.items.items);
					cf.updateUser.theHiddenPanel.items.clear();
					cf.updateUser.theHiddenPanel.body.update('');
				}
				catch(e) {}
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'usermanagement'); ?>', '<?php echo __('Profile changed',null,'usermanagement'); ?>');
				try {
					cf.editUserWindow.theEditUserWindow.hide();
					cf.editUserWindow.theEditUserWindow.destroy();
				}
				catch(e) {}
				try {
					cf.UserGrid.theUserStore.reload();
				}
				catch(e) {}
			}
		});
	}



	
	
	
	
	
	
	
	
};}();