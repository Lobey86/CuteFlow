/** create second tab to add fields to form **/
cf.formPopUpWindowSecondTab = function(){return {
	
	theTopToolBar					:false,
	theColumnPanel					:false,
	theLeftColumnPanel				:false,
	theRightColumnPanel				:false,
	theUniqueFieldsetId				:false,
	theFormCM						:false,
	theUniqueGridStoreId			:false,
	
	/** calls all necesary functions**/
	init: function () {
		cf.formPopUpWindowFieldGrid.init();
		this.initFormCM();
		this.theUniqueFieldsetId = 0;
		this.theUniqueGridStoreId = 0;
		this.initTopToolBar();
		this.initRightColumnPanel();
		this.initLeftColumnPanel();
		this.theLeftColumnPanel.add(this.theTopToolBar);
		this.theRightColumnPanel.add(cf.formPopUpWindowFieldGrid.theFieldGrid);
		this.initColumnPanel();
		this.theColumnPanel.add(this.theLeftColumnPanel);
		this.theColumnPanel.add(this.theRightColumnPanel);
	},
	
	/** on the left Panel, new Fieldsets were added **/
	initLeftColumnPanel: function () {
		this.theLeftColumnPanel = new Ext.Panel ({
			frame:true,
			border: false,
			autoScroll: true,
			columnWidth: .5,
			height: cf.Layout.theRegionWest.getHeight() - 177,
			width: 370
		});
	},
	/** right panel contains the Grid with the Fields **/
	initRightColumnPanel: function () {
		this.theRightColumnPanel = new Ext.Panel ({
			frame:true,
			border: false,
			columnWidth: .5,
			autoScroll: false,
			width: 370
		});
	},
	
	/** column layout **/
	initColumnPanel: function () {
		this.theColumnPanel = new Ext.Panel({
			layout: 'column',
			frame:true,
			title: '<?php echo __('Set Fields to Template',null,'form'); ?>',
			height: cf.Layout.theRegionWest.getHeight() - 148,
			border: false,
			style: 'border:none;',
			autoScroll: false,
			layoutConfig: {
				columns: 2
			}
		});
	},
	
	/** init toolbar for left grid, to add new Fieldsets **/
	initTopToolBar: function () {
		this.theTopToolBar = new Ext.Toolbar({
			width:360,
			items: [{
				xtype: 'button',
				text: '<?php echo __('Add new slot',null,'form'); ?>',
				icon: '/images/icons/shape_square_add.png',
	            tooltip:'<?php echo __('Add new slot',null,'form'); ?>',
				style: 'margin-botton:10px;',
	            handler: function () {
					cf.formPopUpWindowSecondTab.addFieldset('',0,-1,'',false);
	            }
			}]
		});	
	},
	
	/**
	* add a new fieldset with a grid, checkbox and textfield
	* 
	*@param string textfield, is empty when fieldset is created, contains the a textfield name when a document template is edited
	*@param boolen checkbox, is 0 when fieldset is created, can be 1/0 when document template is edited
	*@param json griddata, griddate is -1 when a new fieldset is created. griddara contains a json string, when a docuemnt template is edited. then grid records are stored
	*
	*/
	addFieldset: function (textfield, checkbox, griddata, fieldsettitle,collapsed) {
		var id = cf.formPopUpWindowSecondTab.theUniqueFieldsetId++;
		var fieldset = cf.formPopUpWindowSecondTab.createFieldset(id,textfield, checkbox,fieldsettitle,collapsed );
		var grid = cf.formPopUpWindowSecondTab.createGrid(id);
		fieldset.add(grid);
		cf.formPopUpWindowSecondTab.theLeftColumnPanel.insert(cf.formPopUpWindowSecondTab.theLeftColumnPanel.items.length+1,fieldset);
		cf.formPopUpWindowSecondTab.createDeleteButton.defer(100,this, [id]);
		cf.formPopUpWindowSecondTab.createAddButton.defer(100,this, [id]);
		cf.formPopUpWindowSecondTab.theLeftColumnPanel.doLayout();
		cf.formPopUpWindowSecondTab.theLeftColumnPanel.doLayout();
		cf.formPopUpWindowSecondTab.addItems.defer(500,this, [griddata,grid]);
	},
	
	/**
	* add items to grid, when in editmode
	* 
	*@param json griddata, griddate is -1 when a new fieldset is created. griddara contains a json string, when a docuemnt template is edited. then grid records are stored
	*@param GridPanel grid, the grid, to which the items will be rendered
	*/
	addItems: function (griddata, grid) {
		
		if(griddata != -1) {
			for (var a=0;a<griddata.length;a++) {
				var Rec = Ext.data.Record.create({name: 'unique_id'},{name: 'id'},{name: 'title'});
				grid.store.add(new Rec({unique_id: cf.formPopUpWindowSecondTab.theUniqueGridStoreId++, id: griddata[a].fieldid, title: griddata[a].fieldname})); // important to add unique ID's
			}
		}
	},
	
	/**
	* create a fieldset with checkbox and textfield
	*
	*@param int id, unique ID for the record
	*@param string textfield, is empty when fieldset is created, contains the a textfield name when a document template is edited
	*@param boolen checkbox, is 0 when fieldset is created, can be 1/0 when document template is edited
	*
	*/
	createFieldset: function (id,textfield,checkbox,title, collapsed) {
		var fieldset =  new Ext.form.FieldSet({
			title: '<table><tr><td><div id="deletegrid_' + id + '"></div></td><td></td><td><div id="addgridat_'+id+'"></div></td><td>&nbsp;&nbsp;&nbsp;<b id="slottitle_'+id+'">'+title+'<b></td></tr></table>',
			height: 260,
			width:360,
			collapsible: true,
			collapsed: collapsed,
			id: 'formfieldset_' + id,
			labelWidth:170,
			items:[{
				xtype:'textfield',
				fieldLabel: '<?php echo __('Slot name',null,'form'); ?>',
				value: textfield,
				enableKeyEvents : true,
				id: 'slotfieldsettextfieldid_' + id,
				width: 130
			},{
				xtype: 'checkbox',
				style: 'margin-top:2px;',
				checked: checkbox,
				fieldLabel: '<?php echo __('To all Slot-Receivers at once',null,'form'); ?>'
			}]
		});
		Ext.getCmp('slotfieldsettextfieldid_' + id).on('keyup', function(el, type) {
				Ext.fly('slottitle_'+id).update(Ext.getCmp('slotfieldsettextfieldid_' + id).getValue());
		});
		return fieldset;
	},
	
	createAddButton: function (id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'addgridat_' + id,
			id: 'createaddbuttonid_' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/shape_square_add.png" /></span>',
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							for(var a=1;a<cf.formPopUpWindowSecondTab.theLeftColumnPanel.items.length;a++){
								var item = cf.formPopUpWindowSecondTab.theLeftColumnPanel.getComponent(a);
								var id = (c.getId());
								id = id.replace('createaddbuttonid_', 'formfieldset_');
								var item_id = item.getId();
								if(item_id == id) {
									var textfield = '';
									var checkbox = 0;
									var griddata = -1;
									var id = cf.formPopUpWindowSecondTab.theUniqueFieldsetId++;
									var fieldset = cf.formPopUpWindowSecondTab.createFieldset(id,textfield, checkbox,'');
									var grid = cf.formPopUpWindowSecondTab.createGrid(id);
									fieldset.add(grid);
									cf.formPopUpWindowSecondTab.theLeftColumnPanel.insert(a+1,fieldset);
									cf.formPopUpWindowSecondTab.createDeleteButton.defer(100,this, [id]);
									cf.formPopUpWindowSecondTab.createAddButton.defer(100,this, [id]);
									cf.formPopUpWindowSecondTab.theLeftColumnPanel.doLayout();
									cf.formPopUpWindowSecondTab.theLeftColumnPanel.doLayout();
									cf.formPopUpWindowSecondTab.addItems.defer(500,this, [griddata,grid]);
								}
							}						
						},
					scope: c
				});
				}
			}
		});
	},
	
	/** 
	*
	* render delete button to remove a fieldset
	*
	*@param int id, unique id of the fieldset
    **/
	createDeleteButton: function (id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'deletegrid_' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/shape_square_delete.png" /></span>',
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							Ext.Msg.show({
							   title:'<?php echo __('Delete slot?',null,'form'); ?>',
							   msg: '<?php echo __('Delete slot?',null,'form'); ?>',
							   buttons: Ext.Msg.YESNO,
							   fn: function(btn, text) {
									if(btn == 'yes') {
										var fieldset = Ext.getCmp('formfieldset_' + id);
										cf.formPopUpWindowSecondTab.theLeftColumnPanel.remove(fieldset);
										fieldset.destroy();
										cf.formPopUpWindowSecondTab.theLeftColumnPanel.doLayout();
										cf.createFormWindow.theFormPopUpWindow.doLayout();
									}
							   }
							});
							

						},
					scope: c
				});
				}
			}
		});
	
	},

	/**
	* create a new grid for the fieldset
	*
	*@param int id, unique id for the grid
	*/
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
				fields: [{name: 'unique_id'},{name: 'id'},{name: 'title'}]
			}),
			cm: this.theFormCM
		});
		
		grid.on('render', function(grid) { // render drag drop
			var ddrow = new Ext.dd.DropTarget(grid.container, {
                ddGroup: 'formgrid',
				copy: false,
				notifyDrop: function(ddSource, e, data){ // when droppping a container in the right grid
					if (ddSource.grid != grid){
						for(var a=0;a<data.selections.length;a++) { // if data is from right grid, add it to store. 
							var item = data.selections[a].data;
							var Rec = Ext.data.Record.create({name: 'unique_id'},{name: 'id'},{name: 'title'});
							grid.store.add(new Rec({unique_id: cf.formPopUpWindowSecondTab.theUniqueGridStoreId++, id: item.id, title: item.title})); // important to add unique ID's
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
	
	/** CM for all grids in fieldset **/
	initFormCM: function () {
		this.theFormCM = new Ext.grid.ColumnModel([
			{header: "<div ext:qtip=\"<?php echo __('Notice: empty records are not saved!',null,'form'); ?>\" ext:qwidth=\"200\"><?php echo __('Field',null,'form'); ?></div>", width: 230, sortable: false, dataIndex: 'title', css : "text-align : left;font-size:12px;align:left;"},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Remove Field',null,'form'); ?></td></tr></table>\" ext:qwidth=\"160\"><?php echo __('Action',null,'form'); ?></div>", width: 60, sortable: false, dataIndex: 'unique_id', css : "text-align:left;font-size:12px;align:center;", renderer: this.renderRowDelete}
		]);
	},

	
	/** button renderer for  delete  a row in each fieldset grid**/
	renderRowDelete: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		cf.formPopUpWindowSecondTab.createDeleteRecordButton.defer(100,this, [record.data['unique_id'],store]);
		return '<center><table><tr><td width="16"><div id="formleftgrid_'+ record.data['unique_id'] +'"></div></td></tr></table></center>';
	},
	
	/**
	* delete button, to remove a row in a grid
	*
	*@param int id, id of the record
	*@param SimpleStore theStore, remove record from stroe
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


