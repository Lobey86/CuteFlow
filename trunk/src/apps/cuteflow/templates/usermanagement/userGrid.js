cf.UserGrid = function(){return {
	isInitialized				: false,
	theUserGrid					: false,
	theUserStore				: false,
	theUserStoreIsInitialized	: false,
	theGridCM					: false,
	theGridTopToolbar			: false,
	theGridPanel				: false,
	theGridBottomToolbar		: false,

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
			stripeRows: true,
			border: true,
			store: this.theUserStore,
			cm: this.theGridCm,
			tbar: this.theGridTopToolbar,
			bbar: this.theGridBottomToolbar
		});
	},
	
	initTopToolBar: function () {
		var states = [['25','25'],['50','50']];

		
		this.theGridTopToolbar = new Ext.Toolbar({
			items: [{
                icon: '/images/icons/user_add.png',
                tooltip:'<?php echo __('Add new user',null,'usermanagement'); ?>',
                disabled: false,
                handler: function () {
                	cf.AddUserWindow.init();
                }
               },'-',
               {
				icon: '/images/icons/user_delete.png',
                tooltip:'<?php echo __('Delete existing user',null,'usermanagement'); ?>',
                disabled: false,
                handler: function () {
					var grid = Ext.getCmp('grid');
                	var rows = grid.getSelectionModel().getSelections();
					if (rows.length > 0) {
						for(var i=0;i<rows.length;i++) {
							var r = rows[i];
							var deleteurl = '<?php echo url_for('usermanagement/DeleteUser')?>/id/' + r.get('id');
							if(r.get('id') != '<?php echo $sf_user->getAttribute('id')?>') {
								cf.UserGrid.theUserStore.remove(rows[i]);
								Ext.Ajax.request({  
									url : deleteurl
								});
							}
						}
						Ext.Msg.minWidth = 200;
						if(r.get('id') != '<?php echo $sf_user->getAttribute('id')?>') {
							Ext.MessageBox.alert('<?php echo __('OK',null,'usermanagement'); ?>', '<?php echo __('Delete Success',null,'usermanagement'); ?>');
						}
						else {
							Ext.MessageBox.alert('<?php echo __('Error',null,'usermanagement'); ?>', '<?php echo __('Deleting own account not working',null,'usermanagement'); ?>');
						}
					}
				
                }
               },'->',
               {
				xtype: 'combo',
				id: 'itemsDisplay',
				mode: 'local',
				value: '<?php echo $sf_user->getAttribute('userSettings')->getDisplayeditem();?>',
				editable:false,
				triggerAction: 'all',
				foreSelection: true,
				fieldLabel: 'fewfew',
				store: new Ext.data.SimpleStore({
       				 id: 0,
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
	
	
	initBottomToolBar: function () {
		this.theGridBottomToolbar =  new Ext.PagingToolbar({
			pageSize: <?php echo $sf_user->getAttribute('userSettings')->getDisplayeditem();?>,
			store: this.theUserStore,
			displayInfo: true,
			displayMsg: '<?php echo __('Displaying topics',null,'usermanagement'); ?> {0} - {1} <?php echo __('of',null,'usermanagement'); ?> {2}',
			emptyMsg: '<?php echo __('No records found',null,'usermanagement'); ?>'
		});
	},
	
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
	
	initColumnModel: function () {
		this.theGridCm  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: true, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Firstname',null,'usermanagement'); ?>", width: 150, sortable: true, dataIndex: 'firstname', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Lastname',null,'usermanagement'); ?>", width: 150, sortable: true, dataIndex: 'lastname', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Email',null,'usermanagement'); ?>", width: 150, sortable: true, dataIndex: 'email', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Username',null,'usermanagement'); ?>", width: 150, sortable: true, dataIndex: 'username', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Userrole',null,'usermanagement'); ?>", width: 150, sortable: true, dataIndex: 'role_description', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Action',null,'usermanagement'); ?>", width: 80, sortable: true, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;", renderer: this.renderAction}
		]);
     },
	
	initGridStore: function () {
		this.theUserStoreIsInitialized = true;
		this.theUserStore = new Ext.data.JsonStore({
				totalProperty: 'total',
				root: 'result',
				url: '<?php echo url_for('usermanagement/LoadAllUser')?>',
				autoload: true,
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
	
	renderAction: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var action = record.data['action'];
		cf.UserGrid.createButtons.defer(1, this, [record.data['action']]);
		var myDiv = '<center><table><tr><td><div style="float:left;" id="user_edit'+ record.data['id'] +'"></div></td></tr></table></center>'
		return myDiv;
	},
	
	createButtons:function (id) {
		var btn_edit = new Ext.Button(  {
			renderTo: 'user_edit' + id,
			id: id,
			tooltip: '<?php echo __('Edit Role',null,'userrolemanagement'); ?>',
			icon: '/images/icons/pencil.png',
			tooltip: '<?php echo __('Edit user',null,'usermanagement'); ?>',
			handler: cf.UserGrid.handleEdit,
			scope : this
		});
	},
	
	handleEdit: function (button) {
		alert(button.getId());
	}
	
	
	
	
	
};}();