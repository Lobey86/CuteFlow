cf.createFileWindow = function(){return {
	
	theFieldPopUpWindow				:false,
	theGeneralSettingsFieldset		:false,
	theFormPanel					:false,

	
	init: function (id) {
		cf.fieldTextfield.init();
		cf.fieldCheckbox.init();
		cf.fieldNumber.init();
		cf.fieldDate.init();
		cf.fieldTextarea.init();
		cf.fieldFile.init();
		
				
		this.initFormPanel();		
		this.initGeneralSettings();
		this.initWindow(id);
		
		
		this.theFormPanel.add(this.theGeneralSettingsFieldset); // default Fieldset which is shown everytime ;)
		this.theFormPanel.add(cf.fieldTextfield.theTextfieldFieldset);
		this.theFormPanel.add(cf.fieldNumber.theNumberFieldset);
		this.theFormPanel.add(cf.fieldDate.theDateFieldset);
		this.theFormPanel.add(cf.fieldTextarea.theTextareaFieldset);
		this.theFormPanel.add(cf.fieldFile.theFileFieldset);
		this.theFieldPopUpWindow.add(this.theFormPanel);
		this.theFieldPopUpWindow.show();
	},
	
	
	
	
	initWindow: function (id) {
		this.theFieldPopUpWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: 630,
			width: 'auto',
			autoScroll: true,
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: true,
			title: '<?php echo __('Create new field',null,'field'); ?>',
	        buttonAlign: 'center',
			buttons:[{
				text:'<?php echo __('Store',null,'myprofile'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.fieldCRUD.initSave(id);
				}
			},{
				text:'<?php echo __('Close',null,'usermanagement'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.createFileWindow.theFieldPopUpWindow.hide();
					cf.createFileWindow.theFieldPopUpWindow.destroy();
				}
			}]
		});
		this.theFieldPopUpWindow.on('close', function() {
			cf.createFileWindow.theFieldPopUpWindow.hide();
			cf.createFileWindow.theFieldPopUpWindow.destroy();
		});
	},
	
	initFormPanel: function () {
		this.theFormPanel = new Ext.FormPanel({
			frame:true 
		});
		
	},
	
	initGeneralSettings: function () {
		this.theGeneralSettingsFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('General Field Settings',null,'field'); ?>',
			width: 600,
			height: 150,
			style: 'margin-top:20px;margin-left:5px;margin-right:5px;',
			labelWidth: 170,
			items: [{
				xtype: 'textfield',
				id:'createFileWindow_fieldname',
				allowBlank: false,
				fieldLabel: '<?php echo __('Field name',null,'field'); ?>',
				width:230
			},{
				xtype: 'combo',
				mode: 'local',
				editable:false,
 				valueField:'id',
 				value: 'TEXTFIELD',
 				id: 'createFileWindow_fieldtype_id',
 				hiddenName : 'createFileWindow_fieldtype',
				displayField:'text',
				triggerAction: 'all',
				foreSelection: true,
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[
    					 ['TEXTFIELD', '<?php echo __('Textfield',null,'field'); ?>'],
	       				 ['CHECKBOX', '<?php echo __('Checkbox (yes/no)',null,'field'); ?>'],
	       				 ['NUMBER', '<?php echo __('Number',null,'field'); ?>'],
	       				 ['DATE', '<?php echo __('Date',null,'field'); ?>'],
	       				 ['TEXTAREA', '<?php echo __('Textarea',null,'field'); ?>'],
	       				 ['RADIOGROUP', '<?php echo __('Radiogroup',null,'field'); ?>'],
	       				 ['CHECKBOXGROUP', '<?php echo __('Checkboxgroup',null,'field'); ?>'],
	       				 ['COMBOBOX', '<?php echo __('Combobox',null,'field'); ?>'],
	       				 ['FILE', '<?php echo __('File',null,'field'); ?>']
       				 ]
   				}),
   				fieldLabel: '<?php echo __('Fieldtype',null,'field'); ?>',
   				width:230,
				listeners: {
					select: {
						fn:function(combo) {
							cf.createFileWindow.disableAll();
							if(combo.getValue() == 'TEXTFIELD') {
								cf.fieldTextfield.theTextfieldFieldset.setVisible(true);
								
							}
							else if(combo.getValue() == 'CHECKBOX'){
								
								cf.createFileWindow.saveURL = cf.fieldCheckbox.theSubmitUrl;
							}
							else if(combo.getValue() == 'NUMBER'){
								cf.fieldNumber.theNumberFieldset.setVisible(true);
								
							}
							else if(combo.getValue() == 'DATE'){
								cf.fieldDate.theDateFieldset.setVisible(true);
								
							}
							else if(combo.getValue() == 'TEXTAREA'){
								cf.fieldTextarea.theTextareaFieldset.setVisible(true);
								
							}
							else if(combo.getValue() == 'RADIOGROUP'){
								//cf.fieldCheckbox.theHiddenPanel.setVisible(true);
								//cf.theDefaultFieldset = cf.fieldCheckbox.theHiddenPanel;
							}
							else if(combo.getValue() == 'CHECKBOXGROUP'){
								//cf.fieldCheckbox.theHiddenPanel.setVisible(true);
								//cf.theDefaultFieldset = cf.fieldCheckbox.theHiddenPanel;
							}
							else if(combo.getValue() == 'COMBOBOX'){
								//cf.fieldCheckbox.theHiddenPanel.setVisible(true);
								//cf.theDefaultFieldset = cf.fieldCheckbox.theHiddenPanel;
							}
							else if(combo.getValue() == 'FILE'){
								cf.fieldFile.theFileFieldset.setVisible(true);
								
							}
						}
					}
				}
			},{
				xtype: 'textfield',
				id:'createFileWindow_color',
				fieldLabel: '<?php echo __('Select color',null,'field'); ?>',
				width:230	
			},{
				xtype: 'checkbox',
				fieldLabel: '<?php echo __('Write protected?',null,'field'); ?>',
				inputValue: "1",
				id: 'createFileWindow_writeprotected'
				
			}]
		});
	},
	
	
	disableAll: function () {
		cf.fieldTextfield.theTextfieldFieldset.setVisible(false);
		cf.fieldNumber.theNumberFieldset.setVisible(false);
		cf.fieldDate.theDateFieldset.setVisible(false);
		cf.fieldTextarea.theTextareaFieldset.setVisible(false);
		cf.fieldFile.theFileFieldset.setVisible(false);
	}
	
};}();