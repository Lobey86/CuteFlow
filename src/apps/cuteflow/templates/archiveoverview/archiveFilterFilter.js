cf.archiveFilterFilter = function(){return {
	
	
	
	thePanel				:false,
	theGrid					:false,
	theCM					:false,
	theStore				:false,
	theId					:false,
	
	
	init: function () {
		this.initStore();
		this.initCM();
		this.initGrid();
		this.initPanel();
		this.thePanel.add(this.theGrid);
		
	},
	
	initStore: function () {
		this.theStore = new Ext.data.JsonStore({
			root: 'result',
			url: '<?php echo build_dynamic_javascript_url('filter/LoadFilter')?>',
			autoload: false,
			fields: [
				{name: 'id'},
				{name: 'filtername'}
			]
		});	
	},
	
	initCM: function () {
		this.theCM = new Ext.grid.ColumnModel([
			{header: "<?php echo __('Filter Name',null,'archivemanagement'); ?>", width: 160, sortable: true, dataIndex: 'filtername', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/wand.png' />&nbsp;&nbsp;</td><td><?php echo __('Load Filter',null,'archivemanagement'); ?></td></tr><tr><td><img src='/images/icons/zoom_out.png' />&nbsp;&nbsp;</td><td><?php echo __('Delete Filter',null,'archivemanagement'); ?></td></tr></table>\" ext:qwidth=\"80\"><?php echo __('Action',null,'archivemanagement'); ?></div>", width: 45, sortable: true, dataIndex: 'id', css : "text-align : left;font-size:12px;align:center;", renderer: this.renderButton}		
		]);
		
	},
	
	
	renderButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var id = record.data['id'];
		var btnEdit1 = cf.archiveFilterFilter.createLoadButton.defer(10,this, [id]);
		var btnEdit2 = cf.archiveFilterFilter.createDeleteButton.defer(10,this, [id]);
		return '<center><table><tr><td width="16"><div id="ArchiveFilterLoad'+ id +'"></div></td><td width="16"><div id="ArchiveFilterDelete'+ id +'"></div></td></tr></table></center>';
	},
	
	createLoadButton: function (id, idName) {
		var btn_copy = new Ext.form.Label({
			html: '<span style="cursor:pointer;"><img src="/images/icons/wand.png" /></span>',
			renderTo: 'ArchiveFilterLoad'+ id,
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
						},
					scope: c
					});
				}
			}
		});
	},
	
		
	createDeleteButton: function (id, idName) {
		var btn_copy = new Ext.form.Label({
			html: '<span style="cursor:pointer;"><img src="/images/icons/zoom_out.png" /></span>',
			renderTo: 'ArchiveFilterDelete'+ id,
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							Ext.Msg.show({
							   title:'<?php echo __('Delete Filter',null,'archivemanagement'); ?>?',
							   msg: '<?php echo __('Delete Filter',null,'archivemanagement'); ?>?',
							   buttons: Ext.Msg.YESNO,
							   fn: function(btn, text) {
									if(btn == 'yes') {
										cf.archiveFilterCRUD.deleteFilter(id);
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
	
	initGrid: function () {
		this.theGrid = new Ext.grid.GridPanel({
			title: '<?php echo __('Available Filters',null,'archivemanagement'); ?>',
			stripeRows: true,
			border: true,
			width: 220,
			autoScroll: true,
			height: 449,
			collapsible: true,
			collapsed: false,
			style:'margin-top:1px;margin-left:20px;',
			store: this.theStore,
			cm: this.theCM
		});
		this.theGrid.on('afterrender', function(grid) {
			grid.store.load();
		});	
	},
	
	initPanel: function () {
		this.thePanel = new Ext.Panel({
			closable: false,
			plain: false,
			frame: false,
			border: false,
			layout: 'form',
			width: 240,
			autoScroll:true,
			height: 500,
			style:'margin-top:5px;margin-left:5px;',
			collapsible:true,
			collapsed: false
		});
		
		
	}
	
	
	
	
};}();