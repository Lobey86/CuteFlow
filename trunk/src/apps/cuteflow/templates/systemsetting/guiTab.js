cf.guiTab = function(){return {
	
	theGuiTab					:false,
	theGuiFieldset				:false,
	theGuiGrid					:false,
	theGuiCM					:false,
	theGuiStore					:false,
	thePanel					:false,
	
	
	
		/** load all nedded functions **/
	init: function () {
		this.initCM();
		this.initStore();
		this.initGrid();
		this.initPanel();
		this.initGuiTab();
		this.initGuiFieldset();
		this.thePanel.add(this.theGuiGrid);
		this.theGuiFieldset.add(this.thePanel);
		this.theGuiTab.add(this.theGuiFieldset);
	},
	
	initPanel: function () {
		this.thePanel = new Ext.Panel({
			style: 'margin-left:5px;',
			border: false
		});
		
	},
	
	initGuiTab: function () {
		this.theGuiTab = new Ext.Panel({
			modal: true,
			closable: false,
			modal: true,
			width: 650,
			height: 600,
			autoScroll: false,
			title: '<?php echo __('GUI Settings',null,'systemsetting'); ?>',
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: false
		});
	},
	
	initGuiFieldset: function () {
		this.theGuiFieldset = new Ext.form.FieldSet({
			title: '<table><tr><td><img src="/images/icons/information.png"  ext:qtip=\"Settings will automatic be loaded,<br>when a new user is added to database\" ext:qwidth=\"300\"/></td><td>&nbsp;&nbsp;<?php echo __('GUI Workflow Settings',null,'systemsetting'); ?></td></tr></table>',
			width: 430,
			height: 520,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 330
		});
		
	},
	
	initGrid: function () {
		this.theGuiGrid = new Ext.grid.GridPanel({
			frame:false,
			autoScroll: true,
			collapsible:false,
			closable: false,
			ddGroup : 'theguiTabGridDD',
			//title: '<?php echo __('Change order',null,'systemsetting'); ?>',
			height: 470,
			width: 400,
			allowContainerDrop : true,
			enableDragDrop:true,
			border: true,
			store: this.theGuiStore,
			cm: this.theGuiCM
		});
		
		this.theGuiGrid.on('render', function(grid) {
			var ddrow = new Ext.dd.DropTarget(grid.container, {
                ddGroup: 'theguiTabGridDD',
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
	
	initStore: function () {
		this.theGuiStore = new Ext.data.JsonStore({
				totalProperty: 'total',
				root: 'result',
				url: '<?php echo build_dynamic_javascript_url('systemsetting/LoadCirculationColumns')?>',
				fields: [
					{name: 'id'},
					{name: 'columntext'},
					{name: 'column'},
					{name: 'isactive',type: 'bool'}
				]
		});
		cf.guiTab.theGuiStore.load();
	},
	
	initCM : function () {
		this.theGuiCM  =  new Ext.grid.ColumnModel([
			{header: "<center><div ext:qtip=\"<table><tr><td><img src='/images/icons/checkbox.png' />&nbsp;&nbsp;</td><td><?php echo __('Activate Item',null,'systemsetting'); ?></td></tr></table>\" ext:qwidth=\"200\"><?php echo __('Activate Item',null,'usermanagement'); ?></div></center>",  width: 90, sortable: false, dataIndex: 'id', css : "text-align :left; font-size:12px;", renderer: cf.guiTab.renderAction},
			{header: "<?php echo __('Column',null,'usermanagement'); ?>",  width: 200, sortable: false, dataIndex: 'columntext', css : "text-align :left; font-size:12px;"}
		]);	
		
	},
	
	renderAction: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var id = record.data['id'];
		cf.guiTab.createCheckbox.defer(500,this, [id,  record.data['isactive']]);
		return '<center><table><tr><td><div id="guiTabCheckbox_'+ id +'"></div></td></tr></table></center>';
	},
	
	createCheckbox: function (id, check_value) {
		var check = new Ext.form.Checkbox({
			renderTo: 'guiTabCheckbox_' + id,
			checked: check_value,
			handler: function (checkbox) {
				cf.guiTab.theGuiGrid.store.findExact('id', id ).data.isactive = checkbox.checked;
            }
		});
	}
	
		
	

	
	
};}();