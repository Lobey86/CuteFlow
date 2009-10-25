/** inits fieldset for checkbox **/cf.fieldCheckboxgroup = function(){return {	theCheckboxgroupFieldset			:false,	theCheckboxgroupGrid				:false,	theCheckboxgroupCM					:false,	theCheckboxgroupStore				:false,	theCheckboxgroupToolbar				:false,	theUniqueId							:false,	theCheckboxgroupSavePanel			:false,			/** calls all necessarry functions **/	init: function () {		this.theUniqueId  = 0;		this.initToolbar();		this.initCM();		this.initStore();		this.initGrid();		this.initFieldset();		this.theCheckboxgroupFieldset.add(this.theCheckboxgroupGrid);	},		/** init fieldset **/	initFieldset:  function () {		this.theCheckboxgroupFieldset = new Ext.form.FieldSet({			title: '<?php echo __('Checkboxgroup settings',null,'field'); ?>',			width: 600,			height: 250,			hidden: true,			style: 'margin-top:20px;margin-left:5px;',			labelWidth: 170		});	},		/** init cm **/	initCM: function () {		this.theCheckboxgroupCM  =  new Ext.grid.ColumnModel([			{header: "<div ext:qtip=\"<?php echo __('Notice: empty records are not saved!',null,'field'); ?>\" ext:qwidth=\"200\"><?php echo __('Title',null,'field'); ?></div>", width: 350, sortable: false, dataIndex: 'value', css : "text-align : left;font-size:12px;align:left;", editor: true},			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/checkbox.png' />&nbsp;&nbsp;</td><td><?php echo __('Set Checkbox checked by default',null,'field'); ?></td></tr></table>\" ext:qwidth=\"200\"><?php echo __('set Checked',null,'field'); ?></div>", width: 85, sortable: false, dataIndex: 'unique_id', css : "text-align : left;font-size:12px;align:left;", renderer: this.createCheckbox, editor: false},			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Remove value',null,'field'); ?></td></tr></table>\" ext:qwidth=\"160\"><?php echo __('Action',null,'field'); ?></div>", width: 70, sortable: false, dataIndex: 'unique_id', css : "text-align:left;font-size:12px;align:center;", renderer: this.createCheckDeleteButton, editor: false}		]);	},		/** init simple store **/	initStore: function () {		this.theCheckboxgroupStore = new Ext.data.SimpleStore({			fields: [				{name: 'unique_id'},				{name: 'value'},				{name: 'checked'}			]		});	},		/** init grid **/	initGrid: function () {		this.theCheckboxgroupGrid = new Ext.grid.EditorGridPanel({			stripeRows: true,			border: true,			enableDragDrop:true,			ddGroup : 'checkboxgroupDragDrop',			sm: new Ext.grid.RowSelectionModel(),			allowContainerDrop : true,			width: 'auto',			height: 210,			clicksToEdit: 1,			collapsible: false,			style:'margin-top:5px;margin-left:5px;margin-right:5px;',			store: this.theCheckboxgroupStore,			tbar: this.theCheckboxgroupToolbar,			cm: this.theCheckboxgroupCM		});				this.theCheckboxgroupGrid.on('render', function(grid) {			var ddrow = new Ext.dd.DropTarget(grid.container, {                ddGroup: 'checkboxgroupDragDrop',                copy:false,                notifyDrop: function(ddSource, e, data){					var sm = grid.getSelectionModel();  					var rows = sm.getSelections();  					var cindex = ddSource.getDragData(e).rowIndex;  					 if (sm.hasSelection()) {  						if(typeof(cindex) != "undefined") {							for (i = 0; i < rows.length; i++) {  								grid.store.remove(grid.store.getById(rows[i].id));  								grid.store.insert(cindex,rows[i]);  							}  						}						else { // when trying to add data to the end of the grid							var total_length = grid.store.data.length+1;							for (i = 0; i < rows.length; i++) {  								grid.store.remove(grid.store.getById(rows[i].id));							}							grid.store.add(rows);						}					} 					sm.clearSelections();				}		    }); 		});			},	/** init toolbar **/	initToolbar: function () {		this.theCheckboxgroupToolbar = new Ext.Toolbar({			items: [{			    text: '<?php echo __('Add new field',null,'field'); ?>',				icon: '/images/icons/add.png',	            tooltip:'<?php echo __('Add new field',null,'field'); ?>',	            handler: function () {					cf.fieldCheckboxgroup.addRecord();	            }			}]		});		},		/** add record to grid **/	addRecord: function () {		var grid = cf.fieldCheckboxgroup.theCheckboxgroupGrid;	    var Record = grid.getStore().recordType;		var r = new Record({			value: '',			unique_id: cf.fieldCheckboxgroup.theUniqueId++,			checked : 0		});		grid.stopEditing();		grid.store.insert(0, r);		grid.startEditing(0, 0);	},		/** creates checkbox for grid **/	createCheckbox: function (data, cell, record, rowIndex, columnIndex, store, grid) {			var id = record.data['unique_id'];			var checked = record.data['checked']			var btn = cf.fieldCheckboxgroup.checkButton.defer(1,this, [id,checked]);			return '<center><table><tr><td><div id="activate_checkboxgroup'+ id +'"></div></td></tr></table></center>';	},	/**	* create checkbox	*@param int id, id of record	*@param boolean checked, true false if checekd	*	*/	checkButton: function (id, checked) {		var btn = new Ext.form.Checkbox({			renderTo: 'activate_checkboxgroup' + id,			inputValue: 1,			checked: checked,			handler: function (check) {				cf.fieldCheckboxgroup.theCheckboxgroupGrid.store.findExact('unique_id', id ).data.checked = check.checked;            }		});	},		/** create delete button **/	createCheckDeleteButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {			var id = record.data['unique_id'];			var btn = cf.fieldCheckboxgroup.createDeleteButton.defer(1,this, [id]);			return '<center><table><tr><td><div id="remove_checkboxgroup_value'+ id +'"></div></td></tr></table></center>';	},		/**	* Creates delete button	*@param int id, id of record	*	*/	createDeleteButton: function (id) {		var btn_edit = new Ext.form.Label({			renderTo: 'remove_checkboxgroup_value' + id,			html: '<span style="cursor:pointer;"><img src="/images/icons/delete.png" /></span>',			listeners: {				render: function(c){					  c.getEl().on({						click: function(el){							var item = cf.fieldCheckboxgroup.theCheckboxgroupGrid.store.findExact('unique_id', id );							cf.fieldCheckboxgroup.theCheckboxgroupGrid.store.remove(item);						},					scope: c				});				}			}		});	},		/**	* check before submit	*	*/	checkBeforeSubmit: function() {		if(cf.fieldCheckboxgroup.theCheckboxgroupGrid.store.getCount() == 0){			Ext.Msg.minWidth = 200;			Ext.MessageBox.alert('<?php echo __('Error',null,'field'); ?>', '<?php echo __('Please add some fields',null,'field'); ?>');			return false;		}		else {			return this.buildPanel();		}	},	/** builds the panel to submit data from grid **/	buildPanel: function () {		if(Ext.getCmp('createFileWindow_fieldname').getValue() != '') {			this.initPanel();			var save = false;			var grid = cf.fieldCheckboxgroup.theCheckboxgroupGrid;			for(var a=0;a<grid.store.getCount();a++) {				var row = grid.getStore().getAt(a);				if(row.data.value != '') {					save = true;					if(row.data.checked == 0 || row.data.checked == false) {						var checkValue = 0;					}					else {						var checkValue = 1;					}					var hiddenfield = new Ext.form.Field({						autoCreate : {tag:'input', type: 'hidden', name: 'grid['+row.data.value+']', value:checkValue, width: 0}								});					cf.fieldCheckboxgroup.theCheckboxgroupSavePanel.add(hiddenfield);				}			}			if(save == true) {				cf.createFileWindow.theFormPanel.add(cf.fieldCheckboxgroup.theCheckboxgroupSavePanel);				cf.createFileWindow.theFieldPopUpWindow.doLayout();				return true;			}			else {				Ext.Msg.minWidth = 200;				Ext.MessageBox.alert('<?php echo __('Error',null,'field'); ?>', '<?php echo __('Please add some content to your fields',null,'field'); ?>');				return false;			}			}		else {			return false;		}	},	/** inits panel to submit **/	initPanel: function () {		this.theCheckboxgroupSavePanel = new Ext.Panel({					});	},		/** add data to grid **/	addData: function (data) {		for(var a = 0;a<data.items.length;a++) {			var row = data.items[a];			var grid = cf.fieldCheckboxgroup.theCheckboxgroupGrid;		    var Record = grid.getStore().recordType;		    if(row.isactive == 0) {		    	row.isactive = false;		    }		    else {		    	row.isactive = true;		    }			var r = new Record({				value: row.value,				unique_id: cf.fieldCheckboxgroup.theUniqueId++,				checked : row.isactive			});			grid.store.add(r);		}	}				};}();