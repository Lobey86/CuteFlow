cf.menueSettingGroupCRUD = function(){return {
	
	
	saveGroupOrder: function (id) {
		this.buildGroupFields(id);
		this.saveGroup();
	},
	
	
		
	buildGroupFields: function(id) {
		var myPanel = new Ext.Panel ({
			id: 'menueSettingGroupCRUDSavePanel',
			width:'5',
			height: '5',
			autoScroll: false
		});
		
		var hiddenfield = new Ext.form.Field({
				autoCreate : {tag:'input', type: 'hidden', name: 'module', value:id, width: 0}			
		});
		
		myPanel.add(hiddenfield);
		
		for(var a=0;a<cf.menueSettingGroupGrid.theGroupGrid.store.getCount();a++) {
		
			var row = cf.menueSettingGroupGrid.theGroupGrid.getStore().getAt(a);
			var hiddenfield = new Ext.form.Field({
				autoCreate : {tag:'input', type: 'hidden', name: 'grid[]', value:row.data.group_id, width: 0}			
			});
			
			myPanel.add(hiddenfield);
			
		}
		cf.menueSettingGroupWindow.theFormPanel.add(myPanel);
		cf.menueSettingGroupWindow.theFormPanel.doLayout();
	},
	
	saveGroup: function () {		
		cf.menueSettingGroupWindow.theFormPanel.getForm().submit({
			url: '<?php echo url_for('menuesetting/SaveGroup')?>',
			method: 'POST',
			success: function() {
				cf.menueSettingGroupWindow.theMenueSettingGroupWindow.hide();
				cf.menueSettingGroupWindow.theMenueSettingGroupWindow.destroy();
				
				
				cf.Navigation.isInitialized = false;
				cf.Layout.theRegionWest.remove(cf.Navigation.theAccordion);
				cf.Navigation.theAccordion.destroy();
				cf.Navigation.init();
				cf.Layout.theRegionWest.add(cf.Navigation.theAccordion);
				cf.Layout.theRegionWest.doLayout();
			}
		});
	}
	
	
	
	
};}();