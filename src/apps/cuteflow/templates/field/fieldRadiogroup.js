/** init fieldset for radiogroup **/cf.fieldRadiogroup = function(){return {	theRadiogroupFieldset		:false,	theRadioGrid				:false,	theRadioCM					:false,	theRadioStore				:false,	theRadioToolbar				:false,	theUniqueId					:false,	theRadiogroupSavePanel		:false,	/** call all necessary functions **/	init: function () {		this.theUniqueId  = 0;		this.initToolbar();		this.initCM();		this.initStore();		this.initGrid();		this.initFieldset();		this.theRadiogroupFieldset.add(this.theRadioGrid);	},	/** init fieldset **/	initFieldset:  function () {		this.theRadiogroupFieldset = new Ext.form.FieldSet({			title: '<?php echo __('Radiogroup settings',null,'field'); ?>',			width: 600,			height: 335,			hidden: true,			style: 'margin-top:20px;margin-left:5px;',			labelWidth: 170		});	},		/** init cm **/	initCM: function () {		this.theRadioCM  =  new Ext.grid.ColumnModel([			{header: "<div ext:qtip=\"<b>Notice</b>: empty records are not saved!\" ext:qwidth=\"200\"><?php echo __('Title',null,'field'); ?></div>", width: 350, sortable: false, dataIndex: 'value', css : "text-align : left;font-size:12px;align:left;", editor: true},			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Set Radio checked by default',null,'field'); ?></td></tr></table>\" ext:qwidth=\"200\"><?php echo __('set Checked',null,'field'); ?></div>", width: 85, sortable: false, dataIndex: 'unique_id', css : "text-align : left;font-size:12px;align:left;", renderer: this.createRadiobox, editor: false},			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Remove value',null,'field'); ?></td></tr></table>\" ext:qwidth=\"160\"><?php echo __('Action',null,'field'); ?></div>", width: 70, sortable: false, dataIndex: 'unique_id', css : "text-align:left;font-size:12px;align:center;", renderer: this.createRadioDeleteButton, editor: false},		]);	},			/** init store **/	initStore: function () {		this.theRadioStore = new Ext.data.SimpleStore({			fields: [				{name: 'unique_id'},				{name: 'value'},				{name: 'checked'}			]		});	},		/** init grid **/	initGrid: function () {		this.theRadioGrid = new Ext.grid.EditorGridPanel({			stripeRows: true,			border: true,			enableDragDrop:true,			ddGroup : 'radiogroupDragDrop',			sm: new Ext.grid.RowSelectionModel(),			allowContainerDrop : true,			width: 'auto',			height: 285,			clicksToEdit: 1,			collapsible: false,			style:'margin-top:5px;margin-left:5px;margin-right:5px;',			store: this.theRadioStore,			tbar: this.theRadioToolbar,			cm: this.theRadioCM		});		this.theRadioGrid.on('render', function(grid) {			var ddrow = new Ext.dd.DropTarget(grid.container, {                ddGroup: 'radiogroupDragDrop',                copy:false,                notifyDrop: function(ddSource, e, data){					var sm = grid.getSelectionModel();  					var rows = sm.getSelections();  					var cindex = ddSource.getDragData(e).rowIndex;  					 if (sm.hasSelection()) {  						if(typeof(cindex) != "undefined") {							for (i = 0; i < rows.length; i++) {  								grid.store.remove(grid.store.getById(rows[i].id));  								grid.store.insert(cindex,rows[i]);  							}  						}						else { // when trying to add data to the end of the grid							var total_length = grid.store.data.length+1;							for (i = 0; i < rows.length; i++) {  								grid.store.remove(grid.store.getById(rows[i].id));							}							grid.store.add(rows);						}					} 					sm.clearSelections();				}		    }); 		});			},	/** init toolbar **/	initToolbar: function () {		this.theRadioToolbar = new Ext.Toolbar({			items: [{			    text: '<?php echo __('Add new field',null,'field'); ?>',				icon: '/images/icons/add.png',	            tooltip:'<?php echo __('Add new field',null,'field'); ?>',	            handler: function () {					cf.fieldRadiogroup.addRecord();	            }			}]		});		},		/** add record to grid **/	addRecord: function () {		var grid = cf.fieldRadiogroup.theRadioGrid;	    var Record = grid.getStore().recordType;		var r = new Record({			value: '',			unique_id: cf.fieldRadiogroup.theUniqueId++,			checked : 0		});		grid.stopEditing();		grid.store.insert(0, r);		grid.startEditing(0, 0);	},	/** create radiobox **/	createRadiobox: function (data, cell, record, rowIndex, columnIndex, store, grid) {			var id = record.data['unique_id'];			var checked = record.data['checked']			var btn = cf.fieldRadiogroup.radioButton.defer(1,this, [id,checked]);			return '<center><table><tr><td><div id="activate_radiogroup'+ id +'"></div></td></tr></table></center>';	},		/**	*creates radiobox	*	*@param int id, id of record	*@param boolean checked, set radio checked	*/	radioButton: function (id, checked) {		var btn = new Ext.form.Radio({			renderTo: 'activate_radiogroup' + id,			name: 'activate_radiogroup_radio',			checked: checked,			handler: function (radio) {				cf.fieldRadiogroup.theRadioGrid.store.findExact('unique_id', id ).data.checked = radio.checked;            }		});	},		/** create delete button **/	createRadioDeleteButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {			var id = record.data['unique_id'];			var btn = cf.fieldRadiogroup.createDeleteButton.defer(1,this, [id]);			return '<center><table><tr><td><div id="remove_radiogroup_value'+ id +'"></div></td></tr></table></center>';	},	/**	* create delete button	*@param int id, id of record	*	*/	createDeleteButton: function (id) {		var btn_edit = new Ext.form.Label({			renderTo: 'remove_radiogroup_value' + id,			html: '<span style="cursor:pointer;"><img src="/images/icons/delete.png" /></span>',			listeners: {				render: function(c){					  c.getEl().on({						click: function(el){							var item = cf.fieldRadiogroup.theRadioGrid.store.findExact('unique_id', id );							cf.fieldRadiogroup.theRadioGrid.store.remove(item);						},					scope: c				});				}			}		});	},		/** function checks numbers **/	checkBeforeSubmit: function() {		if(cf.fieldRadiogroup.theRadioGrid.store.getCount() == 0){			Ext.Msg.minWidth = 200;			Ext.MessageBox.alert('<?php echo __('Error',null,'field'); ?>', '<?php echo __('Please add some fields',null,'field'); ?>');			return false;		}		else {			return this.buildPanel();		}	},		/** build panel for submit the record of grid **/	buildPanel: function () {		if(Ext.getCmp('createFileWindow_fieldname').getValue() != '') {			this.initPanel();			var save = false;			var grid = cf.fieldRadiogroup.theRadioGrid;			for(var a=0;a<grid.store.getCount();a++) {				var row = grid.getStore().getAt(a);				if(row.data.value != '') {					save = true;					if(row.data.checked == 0 || row.data.checked == false) {						var checkValue = 0;					}					else {						var checkValue = 1;					}					var hiddenfield = new Ext.form.Field({						autoCreate : {tag:'input', type: 'hidden', name: 'grid['+row.data.value+']', value:checkValue, width: 0}								});					cf.fieldRadiogroup.theRadiogroupSavePanel.add(hiddenfield);				}			}			if(save == true) {				cf.createFileWindow.theFormPanel.add(cf.fieldRadiogroup.theRadiogroupSavePanel);				cf.createFileWindow.theFieldPopUpWindow.doLayout();				return true;			}			else {				Ext.Msg.minWidth = 200;				Ext.MessageBox.alert('<?php echo __('Error',null,'field'); ?>', '<?php echo __('Please add some content to your fields',null,'field'); ?>');				return false;			}			}		else {			return false;		}	},	/** init panel to submit **/	initPanel: function () {		this.theRadiogroupSavePanel = new Ext.Panel({					});	},		/** add data to grid when in editmode **/	addData: function (data) {		for(var a = 0;a<data.items.length;a++) {			var row = data.items[a];			var grid = cf.fieldRadiogroup.theRadioGrid;		    var Record = grid.getStore().recordType;		    if(row.isactive == 0) {		    	row.isactive = false;		    }		    else {		    	row.isactive = true;		    }			var r = new Record({				value: row.value,				unique_id: cf.fieldRadiogroup.theUniqueId++,				checked : row.isactive			});			grid.store.add(r);		}	}	};}();