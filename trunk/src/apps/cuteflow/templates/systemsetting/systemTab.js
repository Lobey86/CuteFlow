cf.systemTab = function(){return {

	theSystemTab				:false,
	theLanguageFieldset			:false,
	theLanguageCombo			:false,
	theComboStore				:false,
	
	
	init: function () {
		this.initStore();
		this.initFieldset();
		this.initSystemTab();
		this.theSystemTab.add(this.theLanguageFieldset);
	},
	
	initSystemTab: function () {
		this.theSystemTab = new Ext.Panel({
			modal: true,
			closable: false,
			modal: true,
			width: 650,
			height: 600,
			autoScroll: true,
			title: '<?php echo __('System settings',null,'systemsetting'); ?>',
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: false
		});
	},
	
	initFieldset: function () {
		this.theLanguageFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Default system language',null,'systemsetting'); ?>',
			width: 600,
			height: 80,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 330,
			items:[{
				xtype: 'combo',
				editable:false,
 				valueField:'value',
				mode: 'local',
 				id: 'systemsetting_language_id',
 				hiddenName : 'systemsetting_language',
				value: '<?php echo Language::buildDefaultLanguage(Language::loadDefaultLanguage());?>',
				displayField:'text',
				fieldLabel: '<?php echo __('Select language',null,'systemsetting'); ?>',
				triggerAction: 'all',
				foreSelection: true,
				store: cf.systemTab.theComboStore
			}]
		});
	},
	
	initStore: function () {
		this.theComboStore = new Ext.data.JsonStore({
			mode: 'local',
			autoload: true,
			url: '<?php echo build_dynamic_javascript_url('login/LoadLanguage')?>',
			root: 'result',
			fields: [
				{name: 'value'},
				{name: 'text'}
			]
		});
		cf.systemTab.theComboStore.load();
	}
	



};}();