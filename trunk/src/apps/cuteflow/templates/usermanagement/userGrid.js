/**
* Class inits the usergrid with all necessary components:
*	- toolbar
* 	- paging
*	- renderer of the button for the grid
*
*/

cf.UserGrid = function(){return {
	isInitialized				: false,
	theUserGrid					: false,
	theUserStore				: false,
	theUserStoreIsInitialized	: false,
	theGridCM					: false,
	theGridTopToolbar			: false,
	theGridPanel				: false,
	theGridBottomToolbar		: false,

	/** main init function **/
	init:function() {	
		this.initGridStore();
		this.theUserStore.load();
		this.initColumnModel();
		this.initTopToolBar();
		this.initBottomToolBar();
		this.initUserGrid();
		this.initGridbarPanel();
		this.theGridPanel.add(this.theUserGrid);
	},
	
	
	/** function initializes the usergrid and sets columnmodel, store and toolbars **/
	initUserGrid: function () {
		this.isInitialized  = true;
		
		this.theUserGrid = new Ext.grid.GridPanel({
			frame:false,
			id: 'grid',
			autoScroll: true,
			collapsible:true,
			closable: true,
			width: 'auto',
			title: '<?php echo __('User overview',null,'usermanagement'); ?>',
			height: cf.Layout.theRegionWest.getHeight() - 110,
			border: true,
			store: this.theUserStore,
			cm: this.theGridCm,
			tbar: this.theGridTopToolbar,
			bbar: this.theGridBottomToolbar
		});
	},
	
	/** Function inits top Toolbar, with Add, Delete User and number of pages displayed **/
	initTopToolBar: function () {
		this.theGridTopToolbar = new Ext.Toolbar({
			items: [{
                icon: '/images/icons/user_add.png',
                tooltip:'<?php echo __('Add new user',null,'usermanagement'); ?>',
                disabled: <?php $arr = $sf_user->getAttribute('credential');echo $arr['administration_usermanagement_addUser'];?>,
                handler: function () {
                	cf.AddUserWindow.init(); // pop will be displayed to add new User
                }
		    },'-',
            {
				icon: '/images/icons/user_delete.png',
                tooltip:'<?php echo __('Delete existing user',null,'usermanagement'); ?>',
                disabled: <?php $arr = $sf_user->getAttribute('credential');echo $arr['administration_usermanagement_removeUser'];?>,
                handler: function () {
					cf.UserCRUD.deleteUser(); // calls Delete function to remove
                }
            },'->',
            {
				xtype: 'combo', // number of records to display in grid
				id: 'itemsDisplay',
				mode: 'local',
				value: '<?php echo $sf_user->getAttribute('userSettings')->getDisplayeditem();?>',
				editable:false,
				triggerAction: 'all',
				foreSelection: true,
				fieldLabel: 'fewfew',
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[[25, '25'],[50, '50'],[75, '75'],[100, '100']]
   				}),
 				valueField:'id',
				displayField:'text',
				width:50,
				listeners: {
		    		select: {
		    			fn:function(combo, value) {
		    				cf.UserGrid.theUserStore.load({params:{start: 0, limit: combo.getValue()}});
		    			}
		    		}
		    	}
		   }]
		});	
	},
	
	/** Paging toolbar **/
	initBottomToolBar: function () {
		this.theGridBottomToolbar =  new Ext.PagingToolbar({
			pageSize: <?php echo $sf_user->getAttribute('userSettings')->getDisplayeditem();?>,
			store: this.theUserStore,
			displayInfo: true,
			displayMsg: '<?php echo __('Displaying topics',null,'usermanagement'); ?> {0} - {1} <?php echo __('of',null,'usermanagement'); ?> {2}',
			emptyMsg: '<?php echo __('No records found',null,'usermanagement'); ?>'
		});
	},
	
	/** Panel where grid is rendered in **/
	initGridbarPanel: function () {
		this.theGridPanel = new Ext.Panel({
			closable: false,
			plain: true,
			frame: false,
			border: false,
			height: 'auto',
			style:'margin-top:5px;margin-left:5px;margin-right:10px;'
		});
	},
	
	/** Columnmodel for grid **/
	initColumnModel: function () {
		this.theGridCm  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: true, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Firstname',null,'usermanagement'); ?>", width: 150, sortable: true, dataIndex: 'firstname', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Lastname',null,'usermanagement'); ?>", width: 150, sortable: true, dataIndex: 'lastname', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Email',null,'usermanagement'); ?>", width: 150, sortable: true, dataIndex: 'email', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Username',null,'usermanagement'); ?>", width: 150, sortable: true, dataIndex: 'username', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Userrole',null,'usermanagement'); ?>", width: 150, sortable: true, dataIndex: 'role_description', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Action',null,'usermanagement'); ?>", width: 80, sortable: true, dataIndex: 'action', css : "text-align :center; font-size:12px;",  renderer: cf.UserGrid.renderAction}
		]);
     },
	
     /** Store for grid **/
	initGridStore: function () {
		this.theUserStoreIsInitialized = true;
		this.theUserStore = new Ext.data.JsonStore({
				totalProperty: 'total',
				root: 'result',
				url: '<?php echo url_for('usermanagement/LoadAllUser')?>',
				fields: [
					{name: '#'},
					{name: 'id'},
					{name: 'firstname'},
					{name: 'lastname'},
					{name: 'email'},
					{name: 'username'},
					{name: 'role_description'},
					{name: 'role_id'},
					{name: 'action'}
				]
		});	
	},
	
	/** Function to render "Edit Button" into datagrid **/
	renderAction: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var action = record.data['action'];
		cf.UserGrid.createButton.defer(500,this, [record.data['action']]);
		return '<center><table><tr><td><div id="user_edit'+ record.data['id'] +'"></div></td></tr></table></center>';
	},
	
	
	/**
	* Function loads a label that includes an image into grid.
	* Problem is, that a button has ugly borders, a label not.
	* @param int id, ID of the current record
	*/
	createButton:function (id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'user_edit' + id,
			id: id,
			disabled: <?php $arr = $sf_user->getAttribute('credential');echo $arr['administration_usermanagement_editUser'];?>,
			html: '<span style="cursor:pointer;"><img src="/images/icons/pencil.png" /></span>',
			tooltip: '<?php echo __('Edit user',null,'usermanagement'); ?>',
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							if (Ext.getCmp(id).disabled == false) {
								cf.UserCRUD.editUser(id);
							}
						},
					scope: c
				});
				}
			}
		});
		
	}

	
	
	
	
	
};}();