/** save function for Modules **/
cf.menueSettingModuleCRUD = function(){return {
	
	
	/** main save function **/
	saveModuleOrder: function () {
		var panel = this.buildModuleFields();
		this.saveModule(panel);
	},
	
	
	/** buildPabnel, where all hiddenfields will be added **/
	buildModuleFields: function() {
		var myPanel = new Ext.Panel ({
			id: 'menueSettingModuleCRUDSavePanel',
			width:'5',
			height: '5',
			autoScroll: false
		});
		
		// add hiddenfields
		for(var a=0;a<cf.menueSettingModuleGrid.theModuleGrid.store.getCount();a++) {
		
			var row = cf.menueSettingModuleGrid.theModuleGrid.getStore().getAt(a);
			var hiddenfield = new Ext.form.Field({
				autoCreate : {tag:'input', type: 'hidden', name: 'grid[]', value:row.data.id, width: 0,heigth:0 }			
			});
			
			myPanel.add(hiddenfield);
			
		}
		// add Panel to formpanel
		cf.administration_menuesetting.themenueSettingModuleWindow.add(myPanel);
		cf.administration_menuesetting.themenueSettingModuleWindow.doLayout();
		return myPanel;
	},
	
	/** save function of the hiddenfields **/
	saveModule: function (panel) {
		
		cf.administration_menuesetting.themenueSettingModuleWindow.getForm().submit({
			url: '<?php echo build_dynamic_javascript_url('menuesetting/SaveModule')?>',
			method: 'POST',
			success: function() {
				try {
					var ac_item_id = cf.Navigation.theAccordion.layout.activeItem.id;
				}
				catch(e) {
				}
				Ext.getCmp('menueSettingModuleCRUDSavePanel').remove();
				Ext.getCmp('menueSettingModuleCRUDSavePanel').destroy();
				cf.administration_menuesetting.themenueSettingModuleWindow.doLayout();
				cf.menueSettingModuleGrid.theModuleStore.reload();
				
				
				
				cf.Navigation.isInitialized = false;
				cf.Layout.theRegionWest.remove(cf.Navigation.theAccordion);
				cf.Navigation.theAccordion.destroy();
				cf.Navigation.init();
				
				cf.Layout.theRegionWest.add(cf.Navigation.theAccordion);
				cf.Layout.theRegionWest.doLayout();	
				cf.menueSettingModuleCRUD.expandNavigation.defer(1000,this,[ac_item_id]);
				cf.administration_menuesetting.themenueSettingModuleWindow.remove(panel);
				cf.administration_menuesetting.themenueSettingModuleWindow.setSize();
				cf.administration_menuesetting.themenueSettingModuleWindow.doLayout();
		
			}
		});
	},
	
	/** expand west navigation **/
	expandNavigation: function (id) {
		try {
			Ext.getCmp(id).expand();
		}
		catch(e) {
		}
	}


	
	
};}();