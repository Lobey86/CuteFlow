cf.UserRoleGrid = function(){return {
	theUserRoleGrid					:false,
	isInitialized					:false,
	theUserRoleStore				:false,
	theUserRoleStoreIsInitialized	:false,
	theUserRoleCM					:false,
	theTopToolBar					:false,
	

	init: function () {
			this.isInitialized = true;
			this.initUserRoleStore();
			this.theUserRoleStore.load();
			this.initUserRoleCM();
			this.initTopToolBar();
			this.initUserRoleGrid();
	},
	
	
	initUserRoleGrid: function () {
		this.theUserRoleGrid = new Ext.grid.GridPanel({
				title: '<?php echo __('Role management',null,'userrolemanagement'); ?>',
				stripeRows: true,
				border: true,
				collapsible: true,
				height: cf.Layout.theRegionWest.getHeight()-65,
				style:'margin-top:5px;margin-left:5px;margin-right:5px;',
				store: this.theUserRoleStore,
				tbar: this.theTopToolBar,
				cm: this.theUserRoleCM
			});
		
	},
	
	
	initUserRoleCM: function() {
		this.theUserRoleCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: true, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Role description',null,'userrolemanagement'); ?>", width: 220, sortable: true, dataIndex: 'description', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('currently used by',null,'userrolemanagement'); ?>", width: 150, sortable: true, dataIndex: 'users', css : "text-align:center;font-size:12px;align:center;"},
			{header: "<?php echo __('Action',null,'userrolemanagement'); ?>", width: 80, sortable: true, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;", renderer: this.renderAction}
		]);
	},
	
	initTopToolBar: function () {
		this.theTopToolBar = new Ext.Toolbar({
			items: [{
                icon: '/images/icons/cog_add.png',
                tooltip:'<?php echo __('Add new Userrole',null,'userrolemanagement'); ?>',
                disabled: false,
                handler: function () {
                	cf.AddRoleWindow.init(1,'');
                }
			}]
		});	
	},
	
	initUserRoleStore: function () {
		this.theUserRoleStoreIsInitialized = true;
		this.theUserRoleStore = new Ext.data.JsonStore({
				totalProperty: 'total',
				root: 'result',
				url: '<?php echo url_for('userrolemanagement/LoadAllRoles')?>',
				autoload: true,
				fields: [
					{name: '#'},
					{name: 'id'},
					{name: 'description'},
					{name: 'users'},
					{name: 'deletable'},
					{name: 'editable'},
					{name: 'action'}
				]
		});
	},
	
	setInitialized: function (value) {
		this.isInitialized = value;
	},
	
	getInstance: function() {
		return this.theUserRoleGrid;
	},
	
	createButtons: function (id) {
		var btn_delete = new Ext.form.Label(  {
			renderTo: 'role_del_' + id,
			id: id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/delete.png" /></span>',
			tooltip: '<?php echo __('Delete Role',null,'userrolemanagement'); ?>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							cf.RoleCRUD.deleteRole(id);
						},
					scope: c
					});
				}
			}
		});
		
		var btn_edit = new Ext.form.Label(  {
			renderTo: 'role_edit_' + id,
			id: id,
			tooltip: '<?php echo __('Edit Role',null,'userrolemanagement'); ?>',
			html: '<span style="cursor:pointer;"><img src="/images/icons/pencil.png" /></span>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							cf.AddRoleWindow.init(0,id);
						},
					scope: c
					});
				}
			}
		});
		
	
	},
	
	renderAction: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var action = record.data['action'];
		if (action == 1) { // item is deleteable and editable!
			cf.UserRoleGrid.createButtons.defer(500, this, [record.data['id']]);
			return '<center><table><tr><td><div id="role_edit_'+ record.data['id'] +'"></div></td><td><div style="float:left;" id="role_del_'+ record.data['id'] +'"></div></td></tr></table></center>'
		}
		else {
			return '<div style="height:18px;"></div>';
		}
		
	}

};}();