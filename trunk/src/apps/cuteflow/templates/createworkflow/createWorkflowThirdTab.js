cf.createWorkflowThirdTab = function(){return {
	
	
	theThirdPanel				:false,
	theUniqueFieldsetId			:false,
	theUniqueFieldId			:false,
	
	
	init:function(id) {
		this.theUniqueFieldsetId = 1;
		this.theUniqueFieldId = 1;
		this.initPanel();
		this.loadData(id);
	},
	
	
	loadData: function (id) {
		Ext.Ajax.request({  
			url: '<?php echo build_dynamic_javascript_url('createworkflow/LoadAllField')?>/id/' + id,
			success: function(objServerResponse){
				theJsonTreeData = Ext.util.JSON.decode(objServerResponse.responseText);
				cf.createWorkflowThirdTab.addData(theJsonTreeData, true);
			}
		});		
	},
	
	
	initPanel: function () {
		this.theThirdPanel = new Ext.Panel({
			title: '<?php echo __('Fields',null,'workflowmanagement'); ?>',
			frame:true,
			autoScroll: true,
			height: cf.Layout.theRegionWest.getHeight() - 148,
			width: 600,
			border: false,
			style: 'border:none;'
		});
	},
	
	addData: function (result, collapsed) {
		var data = result.result;
		
		for(var a=0;a<data.length;a++) {
			var item = data[a];
			var fieldset = cf.createWorkflowThirdTab.createFieldset(item.slot_id, collapsed, item.slot_name);
			var columnpanel = cf.createWorkflowThirdTab.createColumnPanel('');
			var leftPanel = cf.createWorkflowThirdTab.createPanel();
			var rightPanel = cf.createWorkflowThirdTab.createPanel();
			columnpanel.add(leftPanel);
			columnpanel.add(rightPanel);
			fieldset.add(columnpanel);
			for(var b=0;b<item.fields.length;b++) {
				var fielditem = item.fields[b];
				switch (fielditem.type) {
					case "TEXTFIELD":
						var textfield = cf.createWorkflowThirdTab.createTextfield(fielditem);
						fielditem.assign == 'LEFT' ? leftPanel.add(textfield) : rightPanel.add(textfield);
					    break;
					case "CHECKBOX":
						var checkbox = cf.createWorkflowThirdTab.createCheckbox(fielditem);
						fielditem.assign == 'LEFT' ? leftPanel.add(checkbox) : rightPanel.add(checkbox);
						break;
					case "NUMBER":
						var number = cf.createWorkflowThirdTab.createNumberfield(fielditem);
						fielditem.assign == 'LEFT' ? leftPanel.add(number) : rightPanel.add(number);
						break;
					case "DATE":
						var date = cf.createWorkflowThirdTab.createDatefield(fielditem);
						fielditem.assign == 'LEFT' ? leftPanel.add(date) : rightPanel.add(date);
						break;
					case "TEXTAREA":
						var textarea = cf.createWorkflowThirdTab.createTextarea(fielditem);
						fielditem.assign == 'LEFT' ? leftPanel.add(textarea) : rightPanel.add(textarea);
						break;
					case "RADIOGROUP":
						var radiogroup = cf.createWorkflowThirdTab.createRadiogroup(fielditem);
						fielditem.assign == 'LEFT' ? leftPanel.add(radiogroup) : rightPanel.add(radiogroup);
						break;
					case "CHECKBOXGROUP":
						var checkboxgroup = cf.createWorkflowThirdTab.createCheckboxgroup(fielditem);
						fielditem.assign == 'LEFT' ? leftPanel.add(checkboxgroup) : rightPanel.add(checkboxgroup);
						break;
					case "COMBOBOX":
						var combobox = cf.createWorkflowThirdTab.createCombobox(fielditem);
						fielditem.assign == 'LEFT' ? leftPanel.add(combobox) : rightPanel.add(combobox);
						break;
					case "FILE":
						var file = cf.createWorkflowThirdTab.createFile(fielditem);
						fielditem.assign == 'LEFT' ? leftPanel.add(file) : rightPanel.add(file);
					break;
				}			

			}
			cf.createWorkflowThirdTab.theThirdPanel.add(fieldset);
		}
		cf.createWorkflowThirdTab.theThirdPanel.doLayout();
	},
	
	createPanel: function () {
		var panel = new Ext.Panel({
			frame:false,
			layout: 'form',
			border: false,
			autoScroll: true,
			width: (cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 180) / 2,
			height: 'auto'
		});	
		return panel;
	},
	
	createCombobox: function (data) {
		var combo =  new Ext.form.ComboBox({
			fieldLabel: data.field_name,
			valueField: 'value',
			displayField: 'text',
			editable: false,
			mode: 'local',
			store: new Ext.data.SimpleStore({
				fields: [
					{name: 'value'},
					{name: 'text'}
				]
			}),
			triggerAction: 'all',
			selectOnFocus:true,
			id: 'COMBOBOX_' + data.field_id + '__' + cf.createWorkflowThirdTab.theUniqueFieldId++,
			allowBlank: true,
			width:200,
			forceSelection:true
		});
		var activeId = -1;
		for(var a=0;a<data.items.length;a++){
			var item = data.items[a];
			var Rec = Ext.data.Record.create({name: 'value'},{name: 'text'});
			combo.store.add(new Rec({value: item.id, text: item.value}));
			if(item.isactive == 1) {
				activeId = item.id;
			}
		}
		
		if(activeId != -1) {
			combo.setValue(activeId);
		}
		
		if (Ext.isIE6 == true) {
			combo.style = 'margin-top:0px;margin-bottom:0px;';
		}
		else if (Ext.isIE7 == true) {
			combo.style = 'margin-top:0px;margin-bottom:1px;';
		}
		
		return combo;
		
	},
	
	
	createFile: function (data) {
		var id = cf.createWorkflowThirdTab.theUniqueFieldId++;
		var file = new Ext.form.FileUploadField({
		    fieldLabel: data.field_name,
			id: 'FILE_' + data.field_id + '__' + id,
			emptyText:  '<?php echo __('Select a file',null,'workflowmanagement'); ?>',
			width: 200
		});
		if (Ext.isIE6 == true) {			
			file.style = 'margin-top:0px;margin-bottom:0px;';
		}
		else if (Ext.isIE7 == true) {
			file.style = 'margin-top:0px;margin-bottom:0px;';
		}
		return file;
	},
	
	createCheckboxgroup: function (data) {
		var store = new Array();
		var id = cf.createWorkflowThirdTab.theUniqueFieldId++;
		for(var a=0;a<data.items.length;a++){
			var item = data.items[a];
			var activeitem = item.isactive == 1 ? true : false;
			var check = new Ext.form.Checkbox({
				 boxLabel: item.value, 
				 checked: activeitem,
				 name: 'CHECKBOXGROUP_' + data.field_id + '__' + id + '__name', 
				 inputValue: 1
			});
			store[a] = check;
		}
		var checkboxgroup = new Ext.form.CheckboxGroup({
			id: 'CHECKBOXGROUP_' + data.field_id + '__' + id,
			fieldLabel: data.field_name,
			items:[store]			
		});
		
		return checkboxgroup;

	},
	
	createRadiogroup: function (data) {
		var store = new Array();
		var id = cf.createWorkflowThirdTab.theUniqueFieldId++;
		for(var a=0;a<data.items.length;a++){
			var item = data.items[a];
			var activeitem = item.isactive == 1 ? true : false;
			var radio = new Ext.form.Radio({
				 boxLabel: item.value, 
				 checked: activeitem,
				 name: 'RADIOGROUP_' + data.field_id + '__' + id + '__name', 
				 inputValue: 1
			});
			store[a] = radio;
		}
		var radiogroup = new Ext.form.RadioGroup({
			id: 'RADIOGROUP_' + data.field_id + '__' + id,
			fieldLabel: data.field_name,
			items:[store]			
		});
		
		return radiogroup;
	},
	
	createTextarea: function (data) {
		if(data.items.contenttype == 'plain') {
			var textarea = new Ext.form.TextArea({
				fieldLabel: data.field_name,
				width: ((cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 180) / 2)-200,
				value: data.items.content,
				id: 'TEXTAREA_' + data.field_id + '__' + cf.createWorkflowThirdTab.theUniqueFieldId++,
				height: 150
			});
		}
		else {
			var textarea = new Ext.form.HtmlEditor({
				fieldLabel: data.field_name,
				value: data.items.content,
				width: ((cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 180) / 2)-200,
				id: 'TEXTAREA_' + data.field_id + '__' + cf.createWorkflowThirdTab.theUniqueFieldId++,
				height: 150
			});
		}
		return textarea;
		
	},
	
	createDatefield: function (data) {
		var textfield = new Ext.form.DateField({
			allowBlank:true,
			format: data.items.dateformat,
			id: 'DATE_' + data.field_id + '__' + cf.createWorkflowThirdTab.theUniqueFieldId++,
			fieldLabel: data.field_name,
			editable: false,
			value: data.items.defaultvalue,
			width:200
		});	
		return textfield;
	},
	
	
	createNumberfield: function (data) {
		var textfield = new Ext.form.TextField({
			fieldLabel: data.field_name,
			id: 'NUMBER_' + data.field_id + '__' + cf.createWorkflowThirdTab.theUniqueFieldId++,
			allowBlank: true,
			emptyText: data.items.comboboxtext,
			value: data.items.defaultvalue,
			width: 200
		});
		return textfield;
		
	},
	
	createCheckbox: function (data) {
		var checkbox = new Ext.form.Checkbox({
			fieldLabel: data.field_name,
			inputValue: '1',
			style: 'margin-top:5px;',
			id: 'CHECKBOX_' + data.field_id + '__' + cf.createWorkflowThirdTab.theUniqueFieldId++
		});
		return checkbox;
	},
	
	createTextfield: function (data) {
		var textfield = new Ext.form.TextField({
			fieldLabel: data.field_name,
			id: 'TEXTFIELD_' + data.field_id + '__' + cf.createWorkflowThirdTab.theUniqueFieldId++,
			allowBlank: true,
			value: data.items.defaultvalue,
			width: 200
		});
		return textfield;
	},
	
	
	createColumnPanel: function (id) {
		var panel = new Ext.Panel({
		    layout: 'column',
		    width: cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 150,
		    height:'auto',
		    autoScroll:true,
			border: 'none',
			layoutConfig: {
				columns: 2,
				fitHeight: true,
				split: true
			}
		});
		return panel;
	},
	
	createFieldset: function (id, collapsed, name) {
		var fieldset = new Ext.form.FieldSet({
			title: name,
			height: 'auto',
			style: 'margin-left:5px;margin-top:5px',
			width: cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 100,
			autoScroll: true,
			collapsible: true,
			collapsed: collapsed,
			id: 'slotid_' + id,
			labelWidth:220
		});
		return fieldset;
	}
	
	

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
};}();