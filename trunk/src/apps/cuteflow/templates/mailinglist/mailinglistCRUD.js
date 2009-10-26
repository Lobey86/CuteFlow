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
			cf.mailinglistPopUpWindow.theTabPanel.setActiveTab(0);
			cf.mailinglistCRUD.doSubmit(url, title);	
		}
		
	},
	
	
	
	buildPanel:function () {
		if(Ext.getCmp('mailinglistFirstTab_nametextfield').getValue() == '' || Ext.getCmp('mailinglistFirstTab_documenttemplate_id').getValue() == '' ) {
			cf.mailinglistPopUpWindow.theTabPanel.setActiveTab(0);
			return false;
		}
		var grid = cf.mailinglistFirstTab.theAllowedSenderGrid;
		var hiddenfield = new Ext.form.Field({
			autoCreate : {tag:'input', type: 'hidden', name: 'removealloweduser', value:cf.mailinglistFirstTab.theRemoveUser, width: 0}			
		});
		cf.mailinglistFirstTab.theFormPanel.add(hiddenfield);
		var hiddenfield = new Ext.form.Field({
			autoCreate : {tag:'input', type: 'hidden', name: 'removeuser', value:cf.mailinglistSecondTab.theRemoveUser, width: 0}			
		});
		cf.mailinglistFirstTab.theFormPanel.add(hiddenfield);
		
		
		
		if(grid.store.getCount() > 0) {
			var counter = 0;
			for(var c=0;c<grid.store.getCount();c++) {
				var row = grid.getStore().getAt(c);
				var hiddenfield = new Ext.form.Field({
					autoCreate : {tag:'input', type: 'hidden', name: 'user['+counter+'][id]', value:row.data.user_id, width: 0}			
				});
				cf.mailinglistFirstTab.theFormPanel.add(hiddenfield);
				var hiddenfield = new Ext.form.Field({
					autoCreate : {tag:'input', type: 'hidden', name: 'user['+counter+'][databaseId]', value:row.data.databaseId, width: 0}			
				});
				
				cf.mailinglistFirstTab.theFormPanel.add(hiddenfield);
				cf.mailinglistFirstTab.theFormPanel.doLayout();
				counter++;
			}
		}
		var panel = cf.mailinglistSecondTab.theLeftPanel;
		var counter = 0;
		for(var a=0;a<panel.items.length;a++) {
			var fieldset  = panel.getComponent(a);
			var grid = fieldset.getComponent(0);
			var slot_id = fieldset.getId();
			slot_id = slot_id.replace('secondtabfieldsetid_','');
			var hiddenfield = new Ext.form.Field({
				autoCreate : {tag:'input', type: 'hidden', name: 'slot['+counter+'][slot_id]', value:slot_id, width: 0}			
			});
			cf.mailinglistFirstTab.theFormPanel.add(hiddenfield);
			for(var c=0;c<grid.store.getCount();c++) {
				var row = grid.getStore().getAt(c);
				var hiddenfield = new Ext.form.Field({
					autoCreate : {tag:'input', type: 'hidden', name: 'slot['+counter+'][grid]['+c+'][id]', value:row.data.id, width: 0}			
				});
				cf.mailinglistFirstTab.theFormPanel.add(hiddenfield);
				var hiddenfield = new Ext.form.Field({
					autoCreate : {tag:'input', type: 'hidden', name: 'slot['+counter+'][grid]['+c+'][databaseId]', value:row.data.databaseId, width: 0}			
				});
				//alert('User_id: ' + row.data.id + ' DatabaseId: ' + row.data.databaseId );
				cf.mailinglistFirstTab.theFormPanel.add(hiddenfield);
			}
			counter++;
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
			waitMsg: '<?php echo __('Saving Data',null,'mailinglist'); ?>',
			success: function(objServerResponse){
				cf.mailinglistPanelGrid.theMailinglistStore.reload();
				cf.mailinglistPopUpWindow.theMailinglistPopUpWindow.hide();
				cf.mailinglistPopUpWindow.theMailinglistPopUpWindow.destroy();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'mailinglist'); ?>',title);
			}
		});
		
		
	}
	
	
	
	
	
	
	
};}();