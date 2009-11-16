cf.firstTabComponents = function(){return {
	
	
	
	createCheckbox: function (id, label, checked, style) {
		var checkbox = new Ext.form.Checkbox({
			fieldLabel: label,
			inputValue: '1',
			style: style,
			checked: checked,
			id: id		
		});	
		return checkbox;
	},
	
	createWorkflowname: function (id, defaultvalue) {
		var textfield = new Ext.form.TextField({
			id: id,
			fieldLabel: '<?php echo __('Workflow name',null,'workflowmanagement'); ?>',
			allowBlank: false,
			value: defaultvalue,
			style: 'margin-top:5px;',
			width: 170
		});
		return textfield
	},
	
	
	createDatePicker: function (id, defaultvalue) {
		var datepicker = new Ext.form.DateField({
			allowBlank:true,
			id: id,
			format:'d-m-Y',
			fieldLabel: '<?php echo __('Set Startdate of Workflow',null,'workflowmanagement'); ?>',
			width:170	
		});
		return datepicker;
	},
	
	createMailinglist: function(id, defaultvalue) {
		var store = new Ext.data.SimpleStore({
			mode: 'local',
			autoload: false,
			root: 'result',
			fields: [
				{name: 'value'},
				{name: 'text'}
			]
		});
		
		var combo = new Ext.form.ComboBox({
			fieldLabel: '<?php echo __('Mailinglist',null,'workflowmanagement'); ?>',
			valueField: 'value',
			displayField: 'text',
			editable: false,
			mode: 'local',
			store: store,
			triggerAction: 'all',
			selectOnFocus:true,
			allowBlank: true,
			forceSelection:true,
			id:id,
			width: 170
		});
		combo.on('render', function(combo) {
			Ext.Ajax.request({  
				url: '<?php echo build_dynamic_javascript_url('createworkflow/LoadAllMailinglist')?>',
				success: function(objServerResponse){ 
					theJsonTreeData = Ext.util.JSON.decode(objServerResponse.responseText);
					var defaultdata = -1;
					var data = theJsonTreeData.result;
					for(var a=0;a<data.length;a++) {
						var item = data[a];
						var Rec = Ext.data.Record.create({name: 'value'},{name: 'text'});
						combo.store.add(new Rec({value: item.id, text: item.name}));
						if(item.isactive == 1) {
							defaultdata = item.id;
						}
					}
					if(defaultdata != -1) {
						combo.setValue(defaultdata);
					}
				}
			});

			
		});	
		return combo;	
	},
	
	createFieldset: function(id, label, collapsed, height, autoscroll) {
		var fieldset = new Ext.form.FieldSet({
			title: label,
			height: height,
			style: 'margin-left:5px;margin-top:5px',
			width:700,
			autoScroll: autoscroll,
			collapsible: true,
			collapsed: collapsed,
			id: id,
			labelWidth:200
		});
		return fieldset;
	},
	
	createMailinglistCombo:function (id, defaultvalue) {
		var store = new Ext.data.SimpleStore({
			mode: 'local',
			autoload: false,
			root: 'result',
			fields: [
				{name: 'value'},
				{name: 'contenttype'},
				{name: 'content'},
				{name: 'text'}
			]
		});
		
		var combo = new Ext.form.ComboBox({
			fieldLabel: '<?php echo __('Select Additional text',null,'workflowmanagement'); ?>',
			valueField: 'value',
			displayField: 'text',
			editable: false,
			mode: 'local',
			store: store,
			triggerAction: 'all',
			selectOnFocus:true,
			allowBlank: true,
			forceSelection:true,
			id:id,
			listeners: {
				select: {
					fn:function(combo, value) {
						var item = combo.store.findExact('value', combo.getValue());
						if(item.data.contenttype == 'plain') {
							Ext.getCmp('workflow_htmlarea').setVisible(false);
							Ext.getCmp('workflow_textarea').setVisible(true);
							Ext.getCmp('workflow_textarea').setValue(item.data.content);
							Ext.getCmp('workflowpanel').doLayout();
						}
						else {
							Ext.getCmp('workflow_htmlarea').setVisible(true);
							Ext.getCmp('workflow_textarea').setVisible(false);
							Ext.getCmp('workflow_htmlarea').setValue(item.data.content);
							Ext.getCmp('workflowpanel').doLayout();
						}
					}
				}
			},
			width: 170
		});
		combo.on('render', function(combo) {
			Ext.Ajax.request({  
				url: '<?php echo build_dynamic_javascript_url('additionaltext/LoadAllText')?>',
				success: function(objServerResponse){ 
					theJsonTreeData = Ext.util.JSON.decode(objServerResponse.responseText);
					var defaultdata = -1;
					var contenttype;
					var content;
					var data = theJsonTreeData.result;
					for(var a=0;a<data.length;a++) {
						var item = data[a];
						var Rec = Ext.data.Record.create({name: 'value'},{name: 'contenttype'},{name: 'content'},{name: 'text'});
						combo.store.add(new Rec({value: item.id, contenttype: item.rawcontenttype,content: item.content, text: item.title}));
						if(item.isactive == 1) {
							contenttype = item.rawcontenttype;
							content = item.content;
							defaultdata = item.id;
						}
					}
					if(defaultvalue == '') {
						if(defaultdata != -1) {
							combo.setValue(defaultdata);
							if(contenttype == 'plain') {
								Ext.getCmp('workflow_htmlarea').setVisible(false);
								Ext.getCmp('workflow_textarea').setVisible(true);
								Ext.getCmp('workflow_textarea').setValue(content);
								Ext.getCmp('workflowpanel').doLayout();
							}
							else {
								Ext.getCmp('workflow_htmlarea').setVisible(true);
								Ext.getCmp('workflow_textarea').setVisible(false);
								Ext.getCmp('workflow_htmlarea').setValue(content);
								Ext.getCmp('workflowpanel').doLayout();
							}
						}
					}
					else {
						combo.setValue(defaultvalue);
					}
				}
			});

			
		});
		return combo;
	},
	
	initPanel: function () {
		var panel = new Ext.Panel({
			id:'workflowpanel'
		});	
		return panel;
	},
	
	createAdditionaltext: function (id, label, collapsed, height, autoscroll,comboid, defaultvalue) {
		var panel = this.initPanel();
		var htmlarea = this.createHTMLarea();
		var textarea = this.createTextarea();
		panel.add(htmlarea);
		panel.add(textarea);
		var fieldset = this.createFieldset(id, label, collapsed, height, autoscroll);
		var placeholder = this.createPlaceholder();
		var columnpanel = this.createColumnPanel();
		var combo = this.createMailinglistCombo(comboid, defaultvalue);

		
		fieldset.add(combo);
		columnpanel.add(panel);
		columnpanel.add(placeholder);
		fieldset.add(columnpanel);
		return fieldset;
	},	
	
	createColumnPanel:function () {
		var panel = new Ext.Panel({
		    layout: 'column',
			border: 'none',
			layoutConfig: {
				columns: 2,
				fitHeight: true,
				split: true
			}
		});
		return panel;
	},
	
	
	createPlaceholder: function () {
		var cm = new Ext.grid.ColumnModel([
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/control_rewind_blue.png' /></td><td><?php echo __('Insert Placeholder',null,'additionaltext'); ?></td></tr></table>\" ext:qwidth=\"200\"><?php echo __('Action',null,'usermanagementpopup'); ?></div>", width: 50, sortable: true, dataIndex: 'id', css : "text-align : left;font-size:12px;align:center;", renderer: cf.firstTabComponents.renderButton},
			{header: "<?php echo __('Spaceholder',null,'additionaltext'); ?>", width: 180, sortable: false, dataIndex: 'text', css : "text-align : left;font-size:12px;align:center;"}
		]);
		
		var grid = new Ext.grid.GridPanel({ 
			frame:false,
			autoScroll: true,
			collapsible:false,
			closable: false,
			width: 250,
			height: 248,
			style: 'margin-left:10px;',
			border: true,
			store: new Ext.data.SimpleStore({
				fields:['id', 'text'],
				data:[[1,'{%CIRCULATION_TITLE%}'],[2,'{%SENDER_USERNAME%}'],[3,'{%SENDER_FULLNAME%}'],[4,'{%TIME%}'],[5,'{%DATE_SENDING%}']]
			}),
			cm: cm
		});
		return grid;	
	},
	
	createTextarea: function () {
		var textarea = new Ext.form.TextArea({				
			id: 'workflow_textarea',
			labelSeparator: '',
			allowBlank: true,
			hidden: true,
			height: 250,
			width: 385,
			value: '',
			anchor: '100%'
		});
		return textarea;
	},
	
	createHTMLarea: function () {
		var htmlarea = new Ext.form.HtmlEditor({
				labelSeparator: '',			
				height: 250,
				id: 'workflow_htmlarea',
				hidden:true,
				width: 385,
				style: 'margin-top:5px;margin-left:5px;',
				allowBlank: true,
				value: '',
				anchor: '98%'
		});	
		return htmlarea;
	},
	

	
	/** render Move Function **/
	renderButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var btnMove = cf.firstTabComponents.createMoveButton.defer(1,this, [data,record.data['text']]);
		return '<center><table><tr><td><div id="workflowplaceholder' + data + '"></div></td></tr></table></center>';
	},
	
	/** 
	* create movebutton 
	* @param int id, id of the clicked element
	* @param string text, value of the placeholder
	*/
	createMoveButton: function (id,text) {
		var btn_move = new Ext.form.Label({
			renderTo: 'workflowplaceholder' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/control_rewind_blue.png" /></span>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							cf.firstTabComponents.movePlaceholder(text);
						},
					scope: c
					});
				}
			}
		});
	},
	
	/** 
	* 
	* move functionality
	*
	* @param String value, value of the placeholder
	*/
	movePlaceholder: function (value) {
		if(Ext.getCmp('workflow_textarea').hidden == false) {
			var visibleComponent = Ext.getCmp('workflow_textarea');
		}
		else {
			var visibleComponent = Ext.getCmp('workflow_htmlarea');
		}
		var content = visibleComponent.getValue();
		content = content + ' ' + value;
		visibleComponent.setValue(content);
		visibleComponent.focus();		
	}
	
	
	
	
	
	
	
	
	
	
	
	
};}();