cf.createFileWindow = function(){return {
	
	theFieldPopUpWindow				:false,
	theGeneralSettingsFieldset		:false,
	theFormPanel					:false,
	theCurrentObject				:false,

	
	initNewField: function (id) {
		cf.fieldTextfield.init();
		cf.fieldCheckbox.init();
		cf.fieldNumber.init();
		cf.fieldDate.init();
		cf.fieldTextarea.init();
		cf.fieldFile.init();
		cf.fieldRadiogroup.init(id);
		cf.fieldCheckboxgroup.init(id);
		cf.fieldCombobox.init(id);
		
				
		this.initFormPanel();		
		this.initGeneralSettings();
		this.initWindow(id, '<?php echo __('Create new field',null,'field'); ?>');
		
		
		this.theFormPanel.add(this.theGeneralSettingsFieldset); // default Fieldset which is shown everytime ;)
		this.theFormPanel.add(cf.fieldTextfield.theTextfieldFieldset);
		this.theFormPanel.add(cf.fieldNumber.theNumberFieldset);
		this.theFormPanel.add(cf.fieldDate.theDateFieldset);
		this.theFormPanel.add(cf.fieldTextarea.theTextareaFieldset);
		this.theFormPanel.add(cf.fieldFile.theFileFieldset);
		this.theFormPanel.add(cf.fieldRadiogroup.theRadiogroupFieldset);
		this.theFormPanel.add(cf.fieldCheckboxgroup.theCheckboxgroupFieldset);
		this.theFormPanel.add(cf.fieldCombobox.theComboboxFieldset);
		this.theFieldPopUpWindow.add(this.theFormPanel);
		
		this.theCurrentObject = cf.fieldTextfield;
		this.theFieldPopUpWindow.show();
	},
	
	initUpdateField: function (id) {
		Ext.Ajax.request({
			url : '<?php echo build_dynamic_javascript_url('field/LoadSingleField')?>/id/' + id,
			success: function(objServerResponse){ 
				var data = Ext.util.JSON.decode(objServerResponse.responseText);
				
				
			}
		);
	},
	
	
	
	initWindow: function (id, title) {
		this.theFieldPopUpWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: 630,
			width: 700,
			autoScroll: true,
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: true,
			title: title,
	        buttonAlign: 'center',
			buttons:[{
				text:'<?php echo __('Store',null,'myprofile'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.fieldCRUD.initSave(id,cf.createFileWindow.theCurrentObject);
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
			frame:true,
			height: 556
			
		});
		
	},
	
	initGeneralSettings: function () {
		this.theGeneralSettingsFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('General Field Settings',null,'field'); ?>',
			width: 600,
			height: 150,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 170,
			items: [{
				xtype: 'textfield',
				id:'createFileWindow_fieldname',
				allowBlank: false,
				fieldLabel: '<?php echo __('Field name',null,'field'); ?>',
				width:280
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
   				width:280,
				listeners: {
					select: {
						fn:function(combo) {
							cf.createFileWindow.disableAll();
							switch (combo.getValue()) {
								case "TEXTFIELD":
									cf.fieldTextfield.theTextfieldFieldset.setVisible(true);
									cf.createFileWindow.theCurrentObject = cf.fieldTextfield;
								    break;
								case "CHECKBOX":
									cf.createFileWindow.theCurrentObject = cf.fieldCheckbox;
									break;
								case "NUMBER":
									cf.fieldNumber.theNumberFieldset.setVisible(true);
									cf.createFileWindow.theCurrentObject = cf.fieldNumber;
									break;
								case "DATE":
									cf.fieldDate.theDateFieldset.setVisible(true);
									cf.createFileWindow.theCurrentObject = cf.fieldDate;
									break;
								case "TEXTAREA":
									cf.fieldTextarea.theTextareaFieldset.setVisible(true);
									cf.createFileWindow.theCurrentObject = cf.fieldTextarea;
									break;
								case "RADIOGROUP":
									cf.fieldRadiogroup.theRadiogroupFieldset.setVisible(true);
									cf.createFileWindow.theCurrentObject = cf.fieldRadiogroup;
									break;
								case "CHECKBOXGROUP":
									cf.fieldCheckboxgroup.theCheckboxgroupFieldset.setVisible(true);
									cf.createFileWindow.theCurrentObject = cf.fieldCheckboxgroup;
									break;
								case "COMBOBOX":
									cf.fieldCombobox.theComboboxFieldset.setVisible(true);
									cf.createFileWindow.theCurrentObject = cf.fieldCombobox;
									break;
								case "FILE":
									cf.fieldFile.theFileFieldset.setVisible(true);
									cf.createFileWindow.theCurrentObject = cf.fieldFile;
									break;
								default: 
									'';
							}
						}
					}
				}
			},
			new Ext.form.ColorField({
				fieldLabel: '<?php echo __('Select color',null,'field'); ?>',
				id: 'createFileWindow_color',
				width: 280,

				allowBlank: true
			})
			,{
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
		cf.fieldRadiogroup.theRadiogroupFieldset.setVisible(false);
		cf.fieldCheckboxgroup.theCheckboxgroupFieldset.setVisible(false);
		cf.fieldCombobox.theComboboxFieldset.setVisible(false);
	}
	
};}();