cf.menueSettingModuleCRUD = function(){return {
	
	
	saveModuleOrder: function () {
		this.buildModuleFields();
		this.saveModule();
	},
	
	
	
	buildModuleFields: function() {
		var myPanel = new Ext.Panel ({
			id: 'menueSettingModuleCRUDSavePanel',
			width:'5',
			height: '5',
			autoScroll: false
		});
		
		for(var a=0;a<cf.menueSettingModuleGrid.theModuleGrid.store.getCount();a++) {
		
			var row = cf.menueSettingModuleGrid.theModuleGrid.getStore().getAt(a);
			var hiddenfield = new Ext.form.Field({
				autoCreate : {tag:'input', type: 'hidden', name: 'grid[]', value:row.data.id, width: 0}			
			});
			
			myPanel.add(hiddenfield);
			
		}
		cf.administration_menuesetting.theModulePanel.add(myPanel);
		cf.administration_menuesetting.theModulePanel.doLayout();
	},
	
	saveModule: function () {
		cf.administration_menuesetting.theModulePanel.getForm().submit({
			url: '<?php echo url_for('menuesetting/SaveModule')?>',
			method: 'POST',
			success: function() {
				Ext.getCmp('menueSettingModuleCRUDSavePanel').remove();
				Ext.getCmp('menueSettingModuleCRUDSavePanel').destroy();
				cf.administration_menuesetting.theModulePanel.doLayout();
				cf.menueSettingModuleGrid.theModuleStore.reload();
			}
		});
	}
	
	
};}();