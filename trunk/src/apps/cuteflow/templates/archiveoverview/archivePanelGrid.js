cf.archiveWorkflow = function(){return {
	
	theArchiveGrid					:false,
	isInitialized					:false,
	theArchiveStore					:false,
	theArchiveCM					:false,
	theTopToolBar					:false,
	theBottomToolBar				:false,
	theLoadingMask					:false,
	
	init:function () {
		cf.archiveWorkflow.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Loading Data...',null,'workflowmanagement'); ?>'});					
		cf.archiveWorkflow.theLoadingMask.show();
		this.initStore();
		//this.initBottomToolbar();
		this.initCM();
		this.initTopToolBar();
		this.initGrid();
	},
	
	
	/** init CM for the grid **/
	initCM: function () {
		this.theArchiveCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: true, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Name',null,'workflowmanagement'); ?>", width: 140, sortable: true, dataIndex: 'name', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Current station',null,'workflowmanagement'); ?>", width: 140, sortable: true, dataIndex: 'currentstation', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Template',null,'workflowmanagement'); ?>", width: 150, sortable: true, dataIndex: 'mailinglisttemplate', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Sender',null,'workflowmanagement'); ?>", width: 230, sortable: true, dataIndex: 'sendername', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<?php echo __('Running for',null,'workflowmanagement'); ?>", width: 80, sortable: true, dataIndex: 'currentlyrunning', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<?php echo __('Sendet at',null,'workflowmanagement'); ?>", width: 120, sortable: true, dataIndex: 'versioncreated_at', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/database_refresh.png' />&nbsp;&nbsp;</td><td><?php echo __('Move to workflow and remove from archive',null,'workflowmanagement'); ?></td></tr></table>\" ext:qwidth=\"230\"><?php echo __('Action',null,'documenttemplate'); ?></div>", width: 80, sortable: false, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;", renderer: this.renderButton}
		]);
	},
	
	/** init store for the grid **/
	initStore: function () {
		this.theArchiveStore = new Ext.data.JsonStore({
				root: 'result',
				totalProperty: 'total',
				url: '<?php echo build_dynamic_javascript_url('archiveoverview/LoadAllArchivedWorkflow')?>',
				autoload: false,
				fields: [
					{name: '#'},
					{name: 'id'},
					{name: 'mailinglisttemplate_id'},
					{name: 'mailinglisttemplate'},
					{name: 'sender_id'},
					{name: 'sendername'},
					{name: 'currentstation'},
					{name: 'isstopped'},
					{name: 'name'},
					{name: 'isstopped'},
					{name: 'auth'},
					{name: 'currentlyrunning'},
					{name: 'versioncreated_at'},
					{name: 'activeversion_id'}
				]
		});
	},
	
	
	/** init toolbar for grid, contains ajax search **/
	initTopToolBar: function () {
		this.theTopToolBar = new Ext.Toolbar({
			items: ['->',{
				xtype: 'combo', // number of records to display in grid
				mode: 'local',
				value: '<?php $arr = $sf_user->getAttribute('userSettings'); echo $arr['displayeditem'];?>',
				editable:false,
				triggerAction: 'all',
				foreSelection: true,
				fieldLabel: '',
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
		    				//cf.documenttemplatePanelGrid.theBottomToolBar.pageSize = combo.getValue();
		    				//cf.documenttemplatePanelGrid.theDocumenttemplateStore.load({params:{start: 0, limit: combo.getValue()}});
		    			}
		    		}
		    	}
			}]
		});	
		
	},
	
	
	
	initGrid: function () {
		this.theArchiveGrid = new Ext.grid.GridPanel({
			title: '<?php echo __('Workflow templates',null,'workflowmanagement'); ?>',
			stripeRows: true,
			border: true,
			width: 'auto',
			height: cf.Layout.theRegionWest.getHeight() - 100,
			collapsible: false,
			style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			store: this.theArchiveStore,
			tbar: this.theTopToolBar,
			bbar: this.theBottomToolBar,
			cm: this.theArchiveCM
		});
		this.theArchiveGrid.on('afterrender', function(grid) {
			cf.archiveWorkflow.theArchiveStore.load();
			cf.archiveWorkflow.theLoadingMask.hide();
		});	
		
	},
	
	renderButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var id = record.data['id'];
		var activeversion_id = record.data['activeversion_id'];
		var isstopped = record.data['isstopped'];
		
		var rights = record.data['auth'];
		
		var btnDetails = cf.archiveWorkflow.createRemoveFromArchive.defer(10,this, [id, activeversion_id, rights.archiveworkflow]);
		var btnDetails = cf.archiveWorkflow.createDetailsButton.defer(10,this, [id, activeversion_id, rights.detailsworkflow]);
		var btnEdit1 = cf.archiveWorkflow.createDeleteButton.defer(10,this, [id, activeversion_id, rights.deleteworkflow]);
		return '<center><table><tr><td width="16"><div id="archiveoverview_delete'+ id +'"></div></td><td width="16"><div id="archiveoverview_details'+ id +'"></div></td><td width="16"><div id="archiveoverview_remove'+ id +'"></div></td></tr></table></center>';
	},
	
	
	
	createDetailsButton: function (template_id, activeversion_id, right) {
		var btn_copy = new Ext.form.Label({
			renderTo: 'archiveoverview_details' + template_id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/zoom.png" /></span>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							if(right == 1) {
								cf.workflowdetails.init(template_id, activeversion_id, false, true);
							}
							else {
								Ext.Msg.minWidth = 200;
								Ext.MessageBox.alert('<?php echo __('Error',null,'workflowmanagement'); ?>', '<?php echo __('Permission denied',null,'workflowmanagement'); ?>');
							}
						},
					scope: c
					});
				}
			}
		});
		
	},
	
	createDeleteButton: function (template_id, activeversion_id, right) {
		var btn_copy = new Ext.form.Label({
			renderTo: 'archiveoverview_delete' + template_id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/delete.png" /></span>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							if(right == 1) {
								Ext.Msg.show({
								   title:'<?php echo __('Delete workflow',null,'workflowmanagement'); ?>?',
								   msg: '<?php echo __('Delete workflow',null,'workflowmanagement'); ?>?',
								   buttons: Ext.Msg.YESNO,
								   fn: function(btn, text) {
										if(btn == 'yes') {
											cf.workflowmanagementPanelCRUD.deleteWorkflow(template_id, activeversion_id);
										}
								   }
								});
							}
							else {
								Ext.Msg.minWidth = 200;
								Ext.MessageBox.alert('<?php echo __('Error',null,'workflowmanagement'); ?>', '<?php echo __('Permission denied',null,'workflowmanagement'); ?>');
							}
						},
					scope: c
					});
				}
			}
		});
		
	},
	
	
	createRemoveFromArchive: function (template_id, activeversion_id, right) {
		var btn_copy = new Ext.form.Label({
			html: '<span style="cursor:pointer;"><img src="/images/icons/database_refresh.png" /></span>',
			renderTo: 'archiveoverview_remove' + template_id,
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							if(right == 1) {
								Ext.Msg.show({
								   title:'<?php echo __('Remove from Archive',null,'workflowmanagement'); ?>?',
								   msg: '<?php echo __('Remove from Archive',null,'workflowmanagement'); ?>?',
								   buttons: Ext.Msg.YESNO,
								   fn: function(btn, text) {
										if(btn == 'yes') {
											cf.archivePanelCRUD.removeFromArchive(template_id, activeversion_id);
										}
								   }
								});
							}
							else {
								Ext.Msg.minWidth = 200;
								Ext.MessageBox.alert('<?php echo __('Error',null,'workflowmanagement'); ?>', '<?php echo __('Permission denied',null,'workflowmanagement'); ?>');
							}
						},
					scope: c
					});
				}
			}
		});
	}
	
	
	
	
	
};}();