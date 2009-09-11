/**
* Class initialzies the Grid to display all roles stored in DB.
* Paging and Searchbar is not needed here
* 
*/
cf.UserRoleGrid = function(){return {
	theUserRoleGrid					:false,
	isInitialized					:false,
	theUserRoleStore				:false,
	theUserRoleStoreIsInitialized	:false,
	theUserRoleCM					:false,
	theTopToolBar					:false,
	theToolTip						:false,
	
	/** inits all necessary functions to build the grid and its toolbars **/
	init: function () {
			this.isInitialized = true;
			this.initToolTip();
			this.initUserRoleStore();
			this.theUserRoleStore.load();
			this.initUserRoleCM();
			this.initTopToolBar();
			this.initUserRoleGrid();
	},
	
	initToolTip: function () {
		this.theToolTip = new Ext.ToolTip({
			title: 'tip'
		});
	},
	
	/** Grid and store, toolbar and cm are binded **/
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
	


	/** columnModel **/
	initUserRoleCM: function() {

		this.theUserRoleCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: true, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Role description',null,'userrolemanagement'); ?>", width: 220, sortable: false, dataIndex: 'description', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('currently used by',null,'userrolemanagement'); ?>", width: 150, sortable: false, dataIndex: 'users', css : "text-align:center;font-size:12px;align:center;"},
			{header: "<?php echo __('Action',null,'userrolemanagement'); ?>", width: 80, sortable: false, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;" ,renderer: this.renderAction }
		]);

		
		
	},
	
	
	/** Toolbar, to add new role **/
	initTopToolBar: function () {
		this.theTopToolBar = new Ext.Toolbar({
			items: [{
                icon: '/images/icons/cog_add.png',
                tooltip:'<?php echo __('Add new Userrole',null,'userrolemanagement'); ?>',
                disabled: false,
                handler: function () {
                	cf.AddRoleWindow.init(1,''); // new popup is opened, 1 = new record
                }
			}]
		});	
	},
	
	
	/** the store for grid **/
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
					{name: 'users'}
				]
		});
	},
	
	/** 
	* function created edit and delete button for the grid. button is displayed as label 
	*
	* @param int id, id of the record
	**/
	createButtons: function (id) {
		var btn_delete = new Ext.form.Label(  {
			renderTo: 'role_del_' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/delete.png" /></span>',
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
	
	/** render both buttons to grid **/
	renderAction: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		cf.UserRoleGrid.createButtons.defer(500, this, [record.data['id']]);
		return '<center><table><tr><td><div id="role_edit_'+ record.data['id'] +'"></div></td><td><div style="float:left;" id="role_del_'+ record.data['id'] +'"></div></td></tr></table></center>'

		
	}

};}();







