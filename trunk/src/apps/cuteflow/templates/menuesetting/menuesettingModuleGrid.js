cf.menueSettingModuleGrid = function(){return {
	
	theModuleGrid 				:false,
	theModuleStore				:false,
	theModuleCM					:false,
	theTopToolBar				:false,
	
	
	init:function () {
		this.initCM();
		this.initStore();
		this.initTopToolBar();
		this.initGrid();
	},
	
	initGrid: function () {
		this.theModuleGrid = new Ext.grid.GridPanel({
			frame:false,
			autoScroll: true,
			collapsible:false,
			closable: false,
			ddGroup : 'theModuleGridDD',
			allowContainerDrop : true,
			enableDragDrop:true,
		    ddText: 'drag and drop to change order', 
			width: 550,
			height: 450,
			style: 'margin-top:10px;margin-left:7px;',
			border: true,
			store: this.theModuleStore,
			cm: this.theModuleCM,
			tbar: this.theTopToolBar
		});
		
		this.theModuleGrid.on('render', function(grid) {
			var secondGridDropTargetEl = grid.getView().scroller.dom;
			var secondGridDropTarget = new Ext.dd.DropTarget(secondGridDropTargetEl, {
					ddGroup    : 'theModuleGridDD',
					copy:false,
					notifyDrop  : function(ddSource, e, data){ // when droppping a container in the right grid
						var sm = grid.getSelectionModel();
						var rows = sm.getSelections();
						var cindex = ddSource.getDragData(e).rowIndex;
						for (i = 0; i < rows.length; i++) {
							rowData = grid.store.getById(rows[i].id);
							if(!this.copy) {
								grid.store.remove(grid.store.getById(rows[i].id));
								if(cindex) {
									grid.store.insert(cindex,rowData);
								}
								else { // moves items to last position
									var totalItems = grid.store.data.length;
									grid.store.insert(totalItems,rowData);
								}
								
							}
						}
						return true;
					}
			});
		});
		
	},
	
	initStore: function () {
		this.theModuleStore = new Ext.data.JsonStore({
				root: 'result',
				url: '<?php echo url_for('menuesetting/LoadModule')?>',
				fields: [
					{name: '#'},
					{name: 'id'},
					{name: 'group'},
					{name: 'module'}
				]
		});
		this.theModuleStore.load();
		
	},
	
	initCM: function () {
		this.theModuleCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: false, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "Men&uuml; Eintrag", width: 410, sortable: false, dataIndex: 'group', css : "text-align : left;font-size:12px;align:center;"},
			{header: "Aktion", width: 60, sortable: false, tooltip: 'Module bearbeiten', dataIndex: 'module', css : "text-align : left;font-size:12px;align:center;", renderer: cf.menueSettingModuleGrid.editButtonRenderer}
		]);
		
	}, 
	
	initTopToolBar: function () {
		this.theTopToolBar = new Ext.Toolbar({
			items: [{
                icon: '/images/icons/accept.png',
                tooltip:'Reihenfolge speichern',
                handler: function () {
                	cf.menueSettingModuleCRUD.saveModuleOrder();
                }
		    }]
		});
	},
	
	editButtonRenderer: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var action = record.data['module'];
		cf.menueSettingModuleGrid.createButton.defer(500,this, [record.data['module']]);
		return '<center><div id="menuesetting_groupbutton'+ record.data['module'] +'"></div></center>';
	},
	
	createButton: function (id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'menuesetting_groupbutton' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/pencil.png" /></span>',
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							cf.menueSettingGroupWindow.init(id);
						},
					scope: c
				});
				}
			}
		});
		
	}
	
	
};}();