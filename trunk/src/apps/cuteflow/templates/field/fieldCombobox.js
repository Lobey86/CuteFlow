cf.fieldCombobox = function(){return {
	
	
	
	theComboboxFieldset			:false,
	theComboboxGrid				:false,
	theComboboxCM			    :false,
	theComboboxStore			:false,
	theComboboxToolbar			:false,
	theUniqueId					:false,
	theComboboxgroupSavePanel	:false,


	init: function (id) {
		this.theUniqueId  = 0;
		this.initToolbar();
		this.initCM();
		this.initStore();
		this.initGrid();
		this.initFieldset();
		this.theComboboxFieldset.add(this.theComboboxGrid);
	},

	
	initFieldset:  function () {
		this.theComboboxFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Combobox settings',null,'field'); ?>',
			width: 600,
			height: 335,
			hidden: true,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 170
		});
	},
	
	initCM: function () {
		this.theComboboxCM  =  new Ext.grid.ColumnModel([
			{header: "<div ext:qtip=\"<b>Notice</b>: empty records are not saved!\" ext:qwidth=\"200\"><?php echo __('Title',null,'field'); ?></div>", width: 350, sortable: false, dataIndex: 'value', css : "text-align : left;font-size:12px;align:left;", editor: true},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Set Radio checked by default',null,'field'); ?></td></tr></table>\" ext:qwidth=\"200\"><?php echo __('set Checked',null,'field'); ?></div>", width: 85, sortable: false, dataIndex: 'unique_id', css : "text-align : left;font-size:12px;align:left;", renderer: this.createRadiobox, editor: false},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Remove value',null,'field'); ?></td></tr></table>\" ext:qwidth=\"160\"><?php echo __('Action',null,'field'); ?></div>", width: 70, sortable: false, dataIndex: 'unique_id', css : "text-align:left;font-size:12px;align:center;", renderer: this.createComboboxDeleteButton, editor: false},
		]);
	},
	
	initStore: function () {
		this.theComboboxStore = new Ext.data.JsonStore({
			root: 'result',
			url: '<?php echo build_dynamic_javascript_url('field/LoadAllFields')?>',
			autoload: false,
			fields: [
				{name: 'unique_id'},
				{name: 'value'},
				{name: 'checked'}
			]
		});
	},
	
	initGrid: function () {
		this.theComboboxGrid = new Ext.grid.EditorGridPanel({
			stripeRows: true,
			border: true,
			enableDragDrop:true,
			ddGroup : 'comboboxDragDrop',
			sm: new Ext.grid.RowSelectionModel(),
			allowContainerDrop : true,
			width: 'auto',
			height: 285,
			clicksToEdit: 1,
			collapsible: false,
			style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			store: this.theComboboxStore,
			tbar: this.theComboboxToolbar,
			cm: this.theComboboxCM
		});
		
		this.theComboboxGrid.on('render', function(grid) {
			var ddrow = new Ext.dd.DropTarget(grid.container, {
                ddGroup: 'comboboxDragDrop',
                copy:false,
                notifyDrop: function(ddSource, e, data){
					var sm = grid.getSelectionModel();  
					var rows = sm.getSelections();  
					var cindex = ddSource.getDragData(e).rowIndex;  
					 if (sm.hasSelection()) {  
						if(typeof(cindex) != "undefined") {
							for (i = 0; i < rows.length; i++) {  
								grid.store.remove(grid.store.getById(rows[i].id));  
								grid.store.insert(cindex,rows[i]);  
							}  
						}
						else { // when trying to add data to the end of the grid
							var total_length = grid.store.data.length+1;
							for (i = 0; i < rows.length; i++) {  
								grid.store.remove(grid.store.getById(rows[i].id));
							}
							grid.store.add(rows);
						}
					} 
					sm.clearSelections();
				}
		    }); 
		});		
	},
	
	initToolbar: function () {
		this.theComboboxToolbar = new Ext.Toolbar({
			items: [{
			    text: '<?php echo __('Add new field',null,'field'); ?>',
				icon: '/images/icons/add.png',
	            tooltip:'<?php echo __('Add new field',null,'field'); ?>',
	            handler: function () {
					cf.fieldCombobox.addRecord();
	            }
			}]
		});	
	},
	
	
	addRecord: function () {
		var grid = cf.fieldCombobox.theComboboxGrid;
	    var Record = grid.getStore().recordType;
		var r = new Record({
			value: '',
			unique_id: cf.fieldCombobox.theUniqueId++,
			checked : 0
		});
		grid.stopEditing();
		grid.store.insert(0, r);
		grid.startEditing(0, 0);
	},
	
	createRadiobox: function (data, cell, record, rowIndex, columnIndex, store, grid) {
			var id = record.data['unique_id'];
			var checked = record.data['checked']
			var btn = cf.fieldCombobox.radioButton.defer(1,this, [id,checked]);
			return '<center><table><tr><td><div id="activate_combobox'+ id +'"></div></td></tr></table></center>';
	},
	
	radioButton: function (id, checked) {
		var btn = new Ext.form.Radio({
			renderTo: 'activate_combobox' + id,
			name: 'activate_combobox_radio',
			inputValue: 1,
			checked: checked,
			handler: function (radio) {
				cf.fieldCombobox.theComboboxGrid.store.findExact('unique_id', id ).data.checked = radio.checked;
            }
		});
	},
	
	createComboboxDeleteButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {
			var id = record.data['unique_id'];
			var btn = cf.fieldCombobox.createDeleteButton.defer(1,this, [id]);
			return '<center><table><tr><td><div id="remove_combobox_value'+ id +'"></div></td></tr></table></center>';
	},
	
	createDeleteButton: function (id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'remove_combobox_value' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/delete.png" /></span>',
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							var item = cf.fieldCombobox.theComboboxGrid.store.findExact('unique_id', id );
							cf.fieldCombobox.theComboboxGrid.store.remove(item);
						},
					scope: c
				});
				}
			}
		});
	},
	
	/** function checks numbers **/
	checkBeforeSubmit: function() {
		if(cf.fieldCombobox.theComboboxGrid.store.getCount() == 0){
			Ext.Msg.minWidth = 200;
			Ext.MessageBox.alert('<?php echo __('Error',null,'field'); ?>', '<?php echo __('Please add some fields',null,'field'); ?>');
			return false;
		}
		else {
			return this.buildPanel();
		}
	},
	
	
	buildPanel: function () {
		if(Ext.getCmp('createFileWindow_fieldname').getValue() != '') {
			this.initPanel();
			var save = false;
			var grid = cf.fieldCombobox.theComboboxGrid;
			for(var a=0;a<grid.store.getCount();a++) {
				var row = grid.getStore().getAt(a);
				if(row.data.value != '') {
					save = true;
					if(row.data.checked == 0 || row.data.checked == false) {
						var checkValue = 0;
					}
					else {
						var checkValue = 1;
					}
					var hiddenfield = new Ext.form.Field({
						autoCreate : {tag:'input', type: 'hidden', name: 'grid['+row.data.value+']', value:checkValue, width: 0}			
					});
					cf.fieldCombobox.theComboboxgroupSavePanel.add(hiddenfield);
				}
			}
			if(save == true) {
				cf.createFileWindow.theFormPanel.add(cf.fieldCombobox.theComboboxgroupSavePanel);
				cf.createFileWindow.theFieldPopUpWindow.doLayout();
				return true;
			}
			else {
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('Error',null,'field'); ?>', '<?php echo __('Please add some content to your fields',null,'field'); ?>');
				return false;
			}	
		}
		else {
			return false;
		}
	},
	
	initPanel: function () {
		this.theComboboxgroupSavePanel = new Ext.Panel({
			
		});
	}









};}();