/** save function for Modules **/
cf.menueSettingModuleCRUD = function(){return {
	
	
	/** main save function **/
	saveModuleOrder: function () {
		this.buildModuleFields();
		this.saveModule();
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
				autoCreate : {tag:'input', type: 'hidden', name: 'grid[]', value:row.data.id, width: 0}			
			});
			
			myPanel.add(hiddenfield);
			
		}
		// add Panel to formpanel
		cf.administration_menuesetting.theModulePanel.add(myPanel);
		cf.administration_menuesetting.theModulePanel.doLayout();
	},
	
	/** save function of the hiddenfields **/
	saveModule: function () {
		cf.administration_menuesetting.theModulePanel.getForm().submit({
			url: '<?php echo url_for('menuesetting/SaveModule')?>',
			method: 'POST',
			success: function() {
				var ac_item_id = cf.Navigation.theAccordion.layout.activeItem.id;
				Ext.getCmp('menueSettingModuleCRUDSavePanel').remove();
				Ext.getCmp('menueSettingModuleCRUDSavePanel').destroy();
				cf.administration_menuesetting.theModulePanel.doLayout();
				cf.menueSettingModuleGrid.theModuleStore.reload();
				
				
				
				cf.Navigation.isInitialized = false;
				cf.Layout.theRegionWest.remove(cf.Navigation.theAccordion);
				cf.Navigation.theAccordion.destroy();
				cf.Navigation.init();
				
				cf.Layout.theRegionWest.add(cf.Navigation.theAccordion);
				cf.Layout.theRegionWest.doLayout();	
				cf.menueSettingModuleCRUD.expandNavigation.defer(500,this,[ac_item_id]);
		
			}
		});
	},
	
	/** expand west navigation **/
	expandNavigation: function (id) {
		Ext.getCmp(id).expand();
	}


	
	
};}();