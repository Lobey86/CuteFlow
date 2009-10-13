/** create second tab to add fields to form **/
cf.formPopUpWindowSecondTab = function(){return {
	
	theSecondTab					:false,
	theTopToolBar					:false,
	theColumnPanel					:false,
	theLeftColumnPanel				:false,
	theRightColumnPanel				:false,
	theUniqueFieldsetId				:false,
	theFormCM						:false,
	theUniqueGridStoreId			:false,
	
	
	init: function () {
		cf.formPopUpWindowFieldGrid.init();
		this.initFormCM();
		this.theUniqueFieldsetId = 0;
		this.theUniqueGridStoreId = 0;
		this.initTopToolBar();
		this.initLeftColumnPanel();
		this.initRightColumnPanel();
		this.theLeftColumnPanel.add(this.theTopToolBar);
		this.theRightColumnPanel.add(cf.formPopUpWindowFieldGrid.theFieldGrid);
		this.initPanel();
		this.initColumnPanel();
		this.theColumnPanel.add(this.theLeftColumnPanel);
		this.theColumnPanel.add(this.theRightColumnPanel);
		this.theSecondTab.add(this.theColumnPanel);
	},
	
	initLeftColumnPanel: function () {
		this.theLeftColumnPanel = new Ext.FormPanel ({
			frame:true,
			border: false,
			autoScroll: true,
			columnWidth: .5,
			width: 370
		});
	},
	initRightColumnPanel: function () {
		this.theRightColumnPanel = new Ext.Panel ({
			frame:true,
			border: false,
			columnWidth: .5,
			autoScroll: true,
			width: 370
		});
	},
	
	
	initPanel: function () {
		this.theSecondTab = new Ext.Panel ({
			title: '<?php echo __('Set Fields to Template',null,'field'); ?>',
			frame:true,
			autoScroll: true,
			border: 'none',
			height: cf.Layout.theRegionWest.getHeight() - 134
		});
	},
	
	initColumnPanel: function () {
		this.theColumnPanel = new Ext.Panel({
			layout: 'column',
			frame:true,
			border: false,
			style: 'border:none;',
			autoScroll: true,
			layoutConfig: {
				columns: 2
			},
			height: 'auto'
		});
	},
	
	
	initTopToolBar: function () {
		this.theTopToolBar = new Ext.Toolbar({
			items: [{
				xtype: 'button',
				text: '<?php echo __('Add new slot',null,'form'); ?>',
				icon: '/images/icons/shape_square_add.png',
	            tooltip:'<?php echo __('Add new slot',null,'form'); ?>',
				style: 'margin-botton:10px;',
	            handler: function () {
					cf.formPopUpWindowSecondTab.addGrid();
	            }
			}]
		});	
	},
	
	addGrid: function () {
		var id = cf.formPopUpWindowSecondTab.theUniqueFieldsetId++;
		var fieldset = cf.formPopUpWindowSecondTab.createFieldset(id);
		var grid = cf.formPopUpWindowSecondTab.createGrid(id);
		fieldset.add(grid);
		cf.formPopUpWindowSecondTab.theLeftColumnPanel.add(fieldset);
		cf.formPopUpWindowSecondTab.createDeleteButton.defer(100,this, [id]);
		cf.createFormWindow.theFormPopUpWindow.doLayout();
	},
	
	
	createFieldset: function (id) {
		return new Ext.form.FieldSet({
			title: '<table><tr><td><div id="deletegrid_' + id + '"></div></td><td>&nbsp;&nbsp;&nbsp;<?php echo __('Slot settings',null,'form'); ?></td></tr></table>',
			height: 260,
			width:'auto',
			id: 'formfieldset_' + id,
			labelWidth:170,
			items:[{
				xtype:'textfield',
				fieldLabel: '<?php echo __('Slot name',null,'form'); ?>',
				width: 130
			},{
				xtype: 'checkbox',
				style: 'margin-top:2px;',
				fieldLabel: '<?php echo __('To all Slot-Receivers at once',null,'form'); ?>'
			}]
		});
	},
	
	createDeleteButton: function (id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'deletegrid_' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/shape_square_delete.png" /></span>',
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							var fieldset = Ext.getCmp('formfieldset_' + id);
							fieldset.hide();
							fieldset.destroy();
						},
					scope: c
				});
				}
			}
		});
	
	},

	createGrid: function (id) {
		var grid =  new Ext.grid.GridPanel({
			stripeRows: true,
			border: true,
			enableDragDrop:true,
			autoScroll: true,
			id: 'formgridid_' + id,
			ddGroup : 'formgrid',
			allowContainerDrop : true,
			width: 'auto',
			height: 170,
			collapsible: false,
			style:'margin-top:5px;',
			store: new Ext.data.SimpleStore({
			fields: [
				{name: 'unique_id'},
				{name: 'title'}
				]
			}),
			cm: this.theFormCM
		});
		
		grid.on('render', function(grid) {
			var ddrow = new Ext.dd.DropTarget(grid.container, {
                ddGroup: 'formgrid',
				copy: false,
				notifyDrop: function(ddSource, e, data){ // when droppping a container in the right grid
					if (ddSource.grid != grid){
						for(var a=0;a<data.selections.length;a++) { // if data is from right grid, add it to store. 
							var item = data.selections[a].data;
							var Rec = Ext.data.Record.create({name: 'unique_id'},{name: 'title'});
							grid.store.add(new Rec({unique_id: cf.formPopUpWindowSecondTab.theUniqueGridStoreId++, title: item.title})); // important to add unique ID's
						}
					}
					else { // if data is coming from left, then reorder is done.
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
					return true;
				}
		    }); 
		});
		return grid;
	},
	
	initFormCM: function () {
		this.theFormCM = new Ext.grid.ColumnModel([
			{header: "<div ext:qtip=\"<?php echo __('Notice: empty records are not saved!',null,'form'); ?>\" ext:qwidth=\"200\"><?php echo __('Field',null,'form'); ?></div>", width: 230, sortable: false, dataIndex: 'title', css : "text-align : left;font-size:12px;align:left;"},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Remove Field',null,'form'); ?></td></tr></table>\" ext:qwidth=\"160\"><?php echo __('Action',null,'form'); ?></div>", width: 60, sortable: false, dataIndex: 'unique_id', css : "text-align:left;font-size:12px;align:center;", renderer: this.renderRowDelete}
		]);
	},

	
	/** button renderer for edit and delete **/
	renderRowDelete: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		cf.formPopUpWindowSecondTab.createDeleteRecordButton.defer(200,this, [record.data['unique_id'],store]);
		return '<center><table><tr><td width="16"><div id="formleftgrid_'+ record.data['unique_id'] +'"></div></td></tr></table></center>';
	},
	
	/**
	* delete button, to remove a row in a grid
	*
	*@param int id, id of the record
	*/
	createDeleteRecordButton: function (id, theStore) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'formleftgrid_' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/delete.png" /></span>',
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							var item = theStore.findExact('unique_id', id );
							theStore.remove(item);
						},
					scope: c
				});
				}
			}
		});

	}
	



};}();


