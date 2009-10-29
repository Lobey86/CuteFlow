/** class for CRUD functions of a documenttemplate **/
cf.documenttemplateCRUD = function(){return {
	
	/**
	* Delete a template
	*
	*@param int id, id of template
	*/
	initDelete: function (id) {
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('documenttemplate/DeleteDocumenttemplate')?>/id/' + id, 
			success: function(objServerResponse){
				cf.formPanelGrid.theFormStore.reload();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'documenttemplate'); ?>', '<?php echo __('Delete successful',null,'documenttemplate'); ?>');
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
			var url = '<?php echo build_dynamic_javascript_url('documenttemplate/UpdateDocumenttemplate')?>/id/' + id;
			var title = '<?php echo __('Data updated',null,'documenttemplate'); ?>';
		}
		else {
			var url = '<?php echo build_dynamic_javascript_url('documenttemplate/SaveDocumenttemplate')?>';
			var title = '<?php echo __('Data saved',null,'documenttemplate'); ?>';
		}
		var readyToSend = cf.documenttemplateCRUD.buildPanel(); // build panel to save
		if(readyToSend == true)	{
			cf.documenttemplateCRUD.doSubmit(url, title);	
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
		cf.documenttemplatePopUpFirstTab.theFirstTabPanel.doLayout();
		cf.documenttemplatePopUpFirstTab.theFirstTabPanel.getForm().submit({
			url: url,
			method: 'POST',
			//waitMsg: '<?php echo __('Saving Data',null,'documenttemplate'); ?>',
			success: function(objServerResponse){
				//cf.formPanelGrid.theFormStore.reload();
				//cf.createFormWindow.theFormPopUpWindow.hide();
				//cf.createFormWindow.theFormPopUpWindow.destroy();
				//Ext.Msg.minWidth = 200;
				//Ext.MessageBox.alert('<?php echo __('OK',null,'form'); ?>',title);
			}
		});
		
		
	},
	

	/** function adds all Slots and its components to the form panel, to submit it **/
	buildPanel: function () {
		if(Ext.getCmp('documenttemplatePopUpFirstTab_fieldname').getValue() == '') {
			cf.documenttemplatePopUpWindow.theTabPanel.setActiveTab(0);
			return false;
		}
		var panel = cf.documenttemplatePopUpSecondTab.theLeftColumnPanel;
		if(panel.items.length > 1) {
			var counter = 0;

			for(var a=1;a<panel.items.length;a++) { // toolbar will not be added 
				
				var item  = panel.getComponent(a);
				var textfield = item.getComponent(0);
				var checkbox = item.getComponent(1);
				var grid = item.getComponent(2);
				var save = false;

				if(grid.store.getCount() > 0 && textfield.getValue() != '') { // only fields with name and elements in grid will be saved!
					cf.documenttemplatePopUpWindow.theTabPanel.setActiveTab(0);
					save = true;
					checkboxvalue = checkbox.getValue() == false ? 0 : 1;
					textfieldvalue = textfield.getValue();
					
					var hiddenfield = new Ext.form.Field({
						autoCreate : {tag:'input', type: 'hidden', name: 'slot['+counter+'][title]', value:textfieldvalue, width: 0}			
					});
					cf.documenttemplatePopUpFirstTab.theFirstTabPanel.add(hiddenfield);

					var hiddenfield = new Ext.form.Field({
						autoCreate : {tag:'input', type: 'hidden', name: 'slot['+counter+'][receiver]', value:checkboxvalue, width: 0}			
					});
					cf.documenttemplatePopUpFirstTab.theFirstTabPanel.add(hiddenfield);

					for(var c=0;c<grid.store.getCount();c++) {
						var row = grid.getStore().getAt(c);
						var hiddenfield = new Ext.form.Field({
							autoCreate : {tag:'input', type: 'hidden', name: 'slot['+counter+'][grid]['+c+'][id]', value:row.data.id, width: 0}			
						});
						cf.documenttemplatePopUpFirstTab.theFirstTabPanel.add(hiddenfield);
					}
					counter++;
				}
	
			}
			if(save == false) {
				Ext.Msg.minWidth = 300;
				Ext.MessageBox.alert('<?php echo __('Error',null,'form'); ?>','<?php echo __('Add some Fields to your slots and set slotname',null,'form'); ?>');
				cf.documenttemplatePopUpWindow.theTabPanel.setActiveTab(1);
				return false;
			}
			else {
				return true;
			}
		}
		else {
			Ext.Msg.minWidth = 200;
			Ext.MessageBox.alert('<?php echo __('Error',null,'form'); ?>','<?php echo __('Add some Slots!',null,'form'); ?>');
			cf.documenttemplatePopUpWindow.theTabPanel.setActiveTab(1);
			return false;
		}
		return true;
	}
	
	
	
	
	
	
};}();