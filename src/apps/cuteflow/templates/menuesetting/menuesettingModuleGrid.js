/** loads grid for changing order of the Module Menue **/
cf.menueSettingModuleGrid = function(){return {
	
	theModuleGrid 				:false,
	theModuleStore				:false,
	theModuleCM					:false,
	theTopToolBar				:false,
	
	/** init function **/
	init:function () {
		this.initCM();
		this.initStore();
		this.initTopToolBar();
		this.initGrid();
	},
	
	
	/** build grid **/
	initGrid: function () {
		this.theModuleGrid = new Ext.grid.GridPanel({
			frame:false,
			autoScroll: true,
			collapsible:false,
			closable: false,
			ddGroup : 'theModuleGridDD',
			allowContainerDrop : true,
			enableDragDrop:true,
		    ddText: '<?php echo __('Drag Drop to change order',null,'menuesetting'); ?>', 
			width: 550,
			height: 450,
			style: 'margin-top:10px;margin-left:7px;',
			border: true,
			store: this.theModuleStore,
			cm: this.theModuleCM,
			tbar: this.theTopToolBar
		});
		
		/** add Drag Drop functionality **/
		this.theModuleGrid.on('render', function(grid) {
			var ddrow = new Ext.dd.DropTarget(grid.container, {
                ddGroup: 'theModuleGridDD',
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
					}
					else {
						var total_length = grid.store.data.length+1;
						for (i = 0; i < rows.length; i++) {  
							grid.store.remove(grid.store.getById(rows[i].id));
						}
						grid.store.add(rows);
												
					}
					sm.clearSelections();
				}
			   }); 
		});
		
	},
	
	/** init store **/
	initStore: function () {
		this.theModuleStore = new Ext.data.JsonStore({
				root: 'result',
				url: '<?php echo build_dynamic_javascript_url('menuesetting/LoadModule')?>',
				fields: [
					{name: '#'},
					{name: 'id'},
					{name: 'group'},
					{name: 'module'}
				]
		});
		this.theModuleStore.load();
		
	},
	
	/** init Column model **/
	initCM: function () {
		this.theModuleCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: false, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Menue entry',null,'menuesetting'); ?>", width: 410, sortable: false, dataIndex: 'group', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/pencil.png' />&nbsp;&nbsp;</td><td><?php echo __('Change item order',null,'menuesetting'); ?></td></tr></table>\" ext:qwidth=\"200\"><?php echo __('Action',null,'menuesetting'); ?></div>", width: 60, sortable: false, tooltip: 'Module bearbeiten', dataIndex: 'module', css : "text-align : left;font-size:12px;align:center;", renderer: cf.menueSettingModuleGrid.editButtonRenderer}
		]);
		
	}, 
	
	/** init Toolbar **/
	initTopToolBar: function () {
		this.theTopToolBar = new Ext.Toolbar({
			items: [{
                icon: '/images/icons/accept.png',
                tooltip:'<?php echo __('Save order',null,'menuesetting'); ?>',
                handler: function () {
                	cf.menueSettingModuleCRUD.saveModuleOrder();
                }
		    }]
		});
	},
	
	/** render and create editbutton on the right **/
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