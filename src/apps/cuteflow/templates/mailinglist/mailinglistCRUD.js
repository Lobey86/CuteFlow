cf.mailinglistCRUD = function(){return {
	
	
	
	initDelete: function (id) {
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('mailinglist/DeleteMailinglist')?>/id/' + id, 
			success: function(objServerResponse){
				cf.mailinglistPanelGrid.theMailinglistStore.reload();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'mailinglist'); ?>', '<?php echo __('Delete successful',null,'form'); ?>');
			}
		});
		
		
	},
	
	
	initSave: function (id) {
		if (id != '') {
			var url = '<?php echo build_dynamic_javascript_url('mailinglist/UpdateMailinglist')?>/id/' + id;
			var title = '<?php echo __('Data updated',null,'mailinglist'); ?>';
		}
		else {
			var url = '<?php echo build_dynamic_javascript_url('mailinglist/SaveMailinglist')?>';
			var title = '<?php echo __('Data saved',null,'mailinglist'); ?>';
		}
		var readyToSend = cf.mailinglistCRUD.buildPanel(); // build panel to save
		if(readyToSend == true)	{
			cf.mailinglistCRUD.doSubmit(url, title);	
		}
		
	},
	
	
	
	buildPanel:function () {
		if(Ext.getCmp('mailinglistFirstTab_nametextfield').getValue() == '' || Ext.getCmp('mailinglistFirstTab_documenttemplate_id').getValue() == '' ) {
			cf.mailinglistPopUpWindow.theTabPanel.setActiveTab(0);
			return false;
		}
		var grid = cf.mailinglistFirstTab.theAllowedSenderGrid;
		if(grid.store.getCount() > 0) {
			var counter = 0;
			for(var c=0;c<grid.store.getCount();c++) {
				var row = grid.getStore().getAt(c);
				var hiddenfield = new Ext.form.Field({
					autoCreate : {tag:'input', type: 'hidden', name: 'user['+counter+'][id]', value:row.data.id, width: 0}			
				});
				cf.mailinglistFirstTab.theFormPanel.add(hiddenfield);
				var hiddenfield = new Ext.form.Field({
					autoCreate : {tag:'input', type: 'hidden', name: 'user['+counter+'][newRecord]', value:row.data.databaseId, width: 0}			
				});
				cf.mailinglistFirstTab.theFormPanel.add(hiddenfield);
				cf.mailinglistFirstTab.theFormPanel.doLayout();
				counter++;
			}
		}
		return true;
	},
	
	
		
	/**
	* Submit theFormPanel
	*
	*@param string url, SubmitURL, can be UpdateForm or SaveForm
	*@param string title, confirm box title
	*@return boolean, true when saveprocess can be started, false, if not
	*/
	doSubmit: function (url, title) {
		cf.mailinglistFirstTab.theFormPanel.doLayout();
		cf.mailinglistFirstTab.theFormPanel.getForm().submit({
			url: url,
			method: 'POST',
			//waitMsg: '<?php echo __('Saving Data',null,'form'); ?>',
			success: function(objServerResponse){
				/*cf.formPanelGrid.theFormStore.reload();
				cf.createFormWindow.theFormPopUpWindow.hide();
				cf.createFormWindow.theFormPopUpWindow.destroy();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'form'); ?>',title);*/
			}
		});
		
		
	}
	
	
	
	
	
	
	
};}();