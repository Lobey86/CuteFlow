cf.formCRUD = function(){return {
	
	
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
	
	
	
	initSave: function (id) {
		
		if (id != '') {
			var url = '<?php echo build_dynamic_javascript_url('form/UpdateForm')?>/id/' + id;
			var title = '<?php echo __('Data updated',null,'form'); ?>';
		}
		else {
			var url = '<?php echo build_dynamic_javascript_url('form/SaveForm')?>';
			var title = '<?php echo __('Data saved',null,'form'); ?>';
		}
		var readyToSend = cf.formCRUD.buildPanel();
		if(readyToSend == true)	{
			cf.formCRUD.doSubmit(url, title);	
		}
	},
	
	
	
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
				Ext.MessageBox.alert('<?php echo __('OK',null,'field'); ?>',title);
			}
		});
		
		
	},
	

	
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
				if(grid.store.getCount() > 0 && textfield.getValue() != '') {
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
				Ext.MessageBox.alert('<?php echo __('Error',null,'form'); ?>','<?php echo __('Add some Fields to your solts and set Slotname',null,'form'); ?>');
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