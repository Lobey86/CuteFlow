/** class for CRUD functions of a documenttemplate **/
cf.formCRUD = function(){return {
	
	/**
	* Delete a template
	*
	*@param int id, id of template
	*/
	initDelete: function (id) {
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('form/DeleteForm')?>/id/' + id, 
			success: function(objServerResponse){
				cf.formPanelGrid.theFormStore.reload();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'form'); ?>', '<?php echo __('Delete successful',null,'form'); ?>');
			}
		});
	},
	
	
	/**
	* save / update an template
	*
	*@param int id, id is set, if in editmode, is not set, then ''
	*/
	initSave: function (id) {
		
		if (id != '') {
			var url = '<?php echo build_dynamic_javascript_url('form/UpdateForm')?>/id/' + id;
			var title = '<?php echo __('Data updated',null,'form'); ?>';
		}
		else {
			var url = '<?php echo build_dynamic_javascript_url('form/SaveForm')?>';
			var title = '<?php echo __('Data saved',null,'form'); ?>';
		}
		var readyToSend = cf.formCRUD.buildPanel(); // build panel to save
		if(readyToSend == true)	{
			cf.formCRUD.doSubmit(url, title);	
		}
	},
	
	
	/**
	* Submit theFormPanel
	*
	*@param string url, SubmitURL, can be UpdateForm or SaveForm
	*@param string title, confirm box title
	*@return boolean, true when saveprocess can be started, false, if not
	*/
	doSubmit: function (url, title) {
		cf.createFormWindow.theFirstTab.doLayout();
		cf.createFormWindow.theFormPopUpWindow.doLayout();
		cf.createFormWindow.theFirstTab.getForm().submit({
			url: url,
			method: 'POST',
			waitMsg: '<?php echo __('Saving Data',null,'form'); ?>',
			success: function(objServerResponse){
				cf.formPanelGrid.theFormStore.reload();
				cf.createFormWindow.theFormPopUpWindow.hide();
				cf.createFormWindow.theFormPopUpWindow.destroy();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'form'); ?>',title);
			}
		});
		
		
	},
	

	/** function adds all Slots and its components to the form panel, to submit it **/
	buildPanel: function () {
		if(Ext.getCmp('createFileWindow_fieldname').getValue() == '') {
			cf.createFormWindow.theTabPanel.setActiveTab(0);
			return false;
		}
		var panel = cf.formPopUpWindowSecondTab.theLeftColumnPanel;
		if(panel.items.length > 1) {
			var counter = 0;
			for(var a=1;a<panel.items.length;a++) { // toolbar will not be added 
				
				var item  = panel.getComponent(a);
				var textfield = item.getComponent(0);
				var checkbox = item.getComponent(1);
				var grid = item.getComponent(2);
				var save = false;
				if(grid.store.getCount() > 0 && textfield.getValue() != '') { // only fields with name and elements in grid will be saved!
					cf.createFormWindow.theTabPanel.setActiveTab(0);
					save = true;
					checkboxvalue = checkbox.getValue() == false ? 0 : 1;
					textfieldvalue = textfield.getValue();
					
					
					var hiddenfield = new Ext.form.Field({
						autoCreate : {tag:'input', type: 'hidden', name: 'slot['+counter+'][title]', value:textfieldvalue, width: 0}			
					});
					cf.createFormWindow.theFirstTab.add(hiddenfield);

					var hiddenfield = new Ext.form.Field({
						autoCreate : {tag:'input', type: 'hidden', name: 'slot['+counter+'][receiver]', value:checkboxvalue, width: 0}			
					});
					cf.createFormWindow.theFirstTab.add(hiddenfield);

					for(var c=0;c<grid.store.getCount();c++) {
						var row = grid.getStore().getAt(c);
						var hiddenfield = new Ext.form.Field({
							autoCreate : {tag:'input', type: 'hidden', name: 'slot['+counter+'][grid]['+c+']', value:row.data.id, width: 0}			
						});
						cf.createFormWindow.theFirstTab.add(hiddenfield);
					
					}
					counter++;
				}
	
			}
			if(save == false) {
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('Error',null,'form'); ?>','<?php echo __('Add some Fields to your slots and set slotname',null,'form'); ?>');
				cf.createFormWindow.theTabPanel.setActiveTab(1);
				return false;
			}
			else {
				return true;
			}
		}
		else {
			Ext.Msg.minWidth = 200;
			Ext.MessageBox.alert('<?php echo __('Error',null,'form'); ?>','<?php echo __('Add some Slots!',null,'form'); ?>');
			cf.createFormWindow.theTabPanel.setActiveTab(1);
			return false;
		}
		return true;
	}
	
	
	
	
	
	
};}();