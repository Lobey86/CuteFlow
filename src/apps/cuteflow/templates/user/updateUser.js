/** class when updating a user **/
cf.updateUser = function(){return {
	
	theHiddenPanel			:false,
	theLoadingMask			:false,
	
	/** init save function **/
	initSave: function (theFormpanel, user_id) {
		this.initHiddenPanel();
		this.buildUserAgent(theFormpanel);
		this.buildWorkflow(theFormpanel);
		this.doSubmit(theFormpanel, user_id);

	},
	
	/** init panel to save worklfow **/
	initHiddenPanel:function () {
		this.theHiddenPanel = new Ext.Panel({
			border: false,
			width:0,
			height:0
		});
		
	},
	
	/** add worklfow to panel **/
	buildWorkflow: function (theFormpanel) {
		var grid = cf.userFourthTab.theFourthGrid;
		for(var a=0;a<grid.store.getCount();a++) {
			var row = grid.getStore().getAt(a);
			var hiddenfield = new Ext.form.Field({
				autoCreate : {tag:'input', type: 'hidden', name: 'worklfow['+row.data.column+']', value:row.data.isactive, width: 0}			
			});
			cf.updateUser.theHiddenPanel.add(hiddenfield);
		}
		theFormpanel.add(cf.updateUser.theHiddenPanel);
		theFormpanel.doLayout();	
	},
	
	/** add useragents tp panel **/
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
	
	/** submit formpanel **/
	doSubmit: function (theFormpanel, user_id) {
		theFormpanel.getForm().submit({
			url: '<?php echo build_dynamic_javascript_url('usermanagement/UpdateUser')?>/id/' + user_id,
			method: 'POST',
			waitMsg: '<?php echo __('Saving Data',null,'usermanagement'); ?>',
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