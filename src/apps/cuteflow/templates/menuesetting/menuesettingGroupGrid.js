/** class builds grid for Menueitems in one group **/
cf.menueSettingGroupGrid = function(){return {
	
	theGroupGrid 				:false,
	theGroupStore				:false,
	theGroupCM					:false,
	theGroupTopToolBar			:false,
	
	
	
	/**
	*
	* init function
	*
	* @param int id, id of the loaded Group
	*/
	init: function (id) {
		this.initCM();
		this.initStore(id);
		this.initTopToolBar();
		this.initGrid();
	},
	
	
	/** init cm **/
	initCM: function () {
		this.theGroupCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: false, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "Modul", width: 225, sortable: false, dataIndex: 'module', css : "text-align : left;font-size:12px;align:center;"},
			{header: "Gruppe", width: 225, sortable: false, dataIndex: 'group', css : "text-align : left;font-size:12px;align:center;"}
		]);
		
	},
	
	/**
	*
	* init Store for grid
	*
	* @param int id, id of the loaded Group
	*/
	
	initStore: function (id) {
		this.theGroupStore = new Ext.data.JsonStore({
				root: 'result',
				url: '<?php echo url_for('menuesetting/LoadGroup')?>/id/' + id,
				fields: [
					{name: '#'},
					{name: 'id'},
					{name: 'group'},
					{name: 'module'},
					{name: 'module_id'},
					{name: 'group_id'}
				]
		});
		this.theGroupStore.load();
		
	},
	
	/** toolbar **/
	initTopToolBar: function () {
		this.theGroupTopToolBar = new Ext.Toolbar({
			items: [{
                icon: '/images/icons/accept.png',
                tooltip:'Reihenfolge speichern',
                handler: function () {
                	cf.menueSettingModuleCRUD.saveModuleOrder();
                }
		    }]
		});
		
	},
	
	/** init grid for displaying items **/
	initGrid: function () {
		this.theGroupGrid = new Ext.grid.GridPanel({
			frame:false,
			autoScroll: true,
			collapsible:false,
			closable: false,
			ddGroup : 'theGroupGridDD',
			allowContainerDrop : true,
			enableDragDrop:true,
		    ddText: 'drag and drop to change order', 
			width: 530,
			title: 'Bearbeiten',
			height: 450,
			style: 'margin-top:10px;margin-left:10px;',
			border: true,
			store: this.theGroupStore,
			cm: this.theGroupCM,
			tbar: this.theGroupTopToolBar
		});
		
		// drag drop functionality added
		this.theGroupGrid.on('render', function(grid) {
		var secondGridDropTargetEl = grid.getView().scroller.dom;
		var secondGridDropTarget = new Ext.dd.DropTarget(secondGridDropTargetEl, {
				ddGroup    : 'theGroupGridDD',
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
		
	}
	
	
	
};}();