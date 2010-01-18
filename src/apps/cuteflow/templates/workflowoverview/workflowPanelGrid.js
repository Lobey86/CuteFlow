cf.workflowmanagementPanelGrid = function(){return {
	
	theWorkflowGrid					:false,
	isInitialized					:false,
	theWorkflowStore				:false,
	theWorkflowCM					:false,
	theTopToolBar					:false,
	theBottomToolBar				:false,
	theLoadingMask					:false,
	
	init:function () {
		cf.workflowmanagementPanelGrid.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Loading Data...',null,'workflowmanagement'); ?>'});					
		cf.workflowmanagementPanelGrid.theLoadingMask.show();
		this.initStore();
		//this.initBottomToolbar();
		this.initCM();
		this.initTopToolBar();
		this.initGrid();
	},
	
	
	/** init CM for the grid **/
	initCM: function () {
		this.theWorkflowCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: true, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Name',null,'workflowmanagement'); ?>", width: 140, sortable: true, dataIndex: 'name', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Current station',null,'workflowmanagement'); ?>", width: 180, sortable: true, dataIndex: 'currentstation', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Template',null,'workflowmanagement'); ?>", width: 150, sortable: true, dataIndex: 'mailinglisttemplate', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Sender',null,'workflowmanagement'); ?>", width: 230, sortable: true, dataIndex: 'sendername', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<?php echo __('Running for',null,'workflowmanagement'); ?>", width: 80, sortable: true, dataIndex: 'currentlyrunning', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<?php echo __('Sendet at',null,'workflowmanagement'); ?>", width: 120, sortable: true, dataIndex: 'versioncreated_at', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<?php echo __('already done',null,'workflowmanagement'); ?>", width: 110, sortable: true, dataIndex: 'process', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/table_edit.png' />&nbsp;&nbsp;</td><td><?php echo __('Edit Document template',null,'documenttemplate'); ?></td></tr><tr><td><img src='/images/icons/clock.png' />&nbsp;&nbsp;</td><td><?php echo __('Show Document template versions',null,'documenttemplate'); ?></td></tr><tr><td><img src='/images/icons/table_delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Delete Document template',null,'documenttemplate'); ?></td></tr></table>\" ext:qwidth=\"230\"><?php echo __('Action',null,'documenttemplate'); ?></div>", width: 100, sortable: false, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;", renderer: this.renderButton}
		]);
	},
	
	/** init store for the grid **/
	initStore: function () {
		this.theWorkflowStore = new Ext.data.JsonStore({
				root: 'result',
				totalProperty: 'total',
				url: '<?php echo build_dynamic_javascript_url('workflowoverview/LoadAllWorkflow')?>',
				autoload: false,
				fields: [
					{name: '#'},
					{name: 'id'},
					{name: 'mailinglisttemplate_id'},
					{name: 'mailinglisttemplate'},
					{name: 'sender_id'},
					{name: 'sendername'},
					{name: 'currentstation'},
					{name: 'workflowisstarted'},
					{name: 'iscompleted'},
					{name: 'isstopped'},
					{name: 'openinpopup'},
					{name: 'process'},
					{name: 'name'},
					{name: 'isstopped'},
					{name: 'currentlyrunning'},
					{name: 'versioncreated_at'},
					{name: 'activeversion_id'}
				]
		});
	},
	
	
	/** init toolbar for grid, contains ajax search **/
	initTopToolBar: function () {
		this.theTopToolBar = new Ext.Toolbar({
			items: [{	
				icon: '/images/icons/report_add.png',
	            tooltip:'<?php echo __('Create new Workflow',null,'workflowmanagement'); ?>',
				disabled: <?php $arr = $sf_user->getAttribute('credential');echo $arr['management_documenttemplate_addDocumenttemplate'];?>,
	            handler: function () {
					cf.createWorkflowWindow.init();
	            }
				
			},'->',{
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
		this.theWorkflowGrid = new Ext.grid.GridPanel({
			title: '<?php echo __('Workflow templates',null,'workflowmanagement'); ?>',
			stripeRows: true,
			border: true,
			width: 'auto',
			height: cf.Layout.theRegionWest.getHeight() + cf.Layout.theRegionNorth.getHeight() - 100,
			collapsible: false,
			style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			store: this.theWorkflowStore,
			tbar: this.theTopToolBar,
			bbar: this.theBottomToolBar,
			cm: this.theWorkflowCM
		});
		this.theWorkflowGrid.on('afterrender', function(grid) {
			cf.workflowmanagementPanelGrid.theWorkflowStore.load();
			cf.workflowmanagementPanelGrid.theLoadingMask.hide();
		});	
		
	},
	
	renderButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var id = record.data['id'];
		var activeversion_id = record.data['activeversion_id'];
		
		var openinpopup = record.data['openinpopup'];
		var isstopped = record.data['isstopped'];
		var iscompleted = record.data['iscompleted'];
		var workflowisstarted = record.data['workflowisstarted'];
		
		var btnDetails = cf.workflowmanagementPanelGrid.createDetailsButton.defer(10,this, [id, activeversion_id, openinpopup]);
		var btnEdit = cf.workflowmanagementPanelGrid.createDeleteButton.defer(10,this, [id, activeversion_id]);
		var btnEdit = cf.workflowmanagementPanelGrid.createArchiveButton.defer(10,this, [id, activeversion_id]);
		var btnEdit = cf.workflowmanagementPanelGrid.createStopButton.defer(10,this, [id, activeversion_id, isstopped, workflowisstarted, iscompleted]);
		return '<table><tr><td width="16"><div id="workflowoverview_delete'+ id +'"></div></td><td width="16"><div id="workflowoverview_details'+ id +'"></div></td><td width="16"><div id="workflowoverview_acrhive'+ id +'"></div></td><td width="16"><div id="workflowoverview_stop'+ id +'"></div></td></tr></table></center>';
	},
	
	
	createArchiveButton: function (version_id) {
		var btn_copy = new Ext.form.Label({
			html: '<span style="cursor:pointer;"><img src="/images/icons/disk.png" /></span>',
			renderTo: 'workflowoverview_acrhive' + version_id,
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							alert('archiv');
						},
					scope: c
					});
				}
			}
		});
	},
	
	createStopButton: function (template_id, activeversion_id, isstopped, workflowisstarted, iscompleted) {
		//alert(iscompleted);
		var disabled = iscompleted == 1 ? true : false;
		var btn_copy = new Ext.form.Label({
			html: '<span style="cursor:pointer;"><img src="/images/icons/control_stop_blue.png" /></span>',
			disabled: disabled,
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							if(iscompleted != 1) {
								if(isstopped == 1) {
									cf.restartWorkflowWindow.init(activeversion_id);
								}
								else {
									if(workflowisstarted == 1) {
										cf.workflowmanagementPanelCRUD.stopWorkflow(template_id, activeversion_id);
									}
									else {
										cf.workflowmanagementPanelCRUD.startWorkflow(template_id);
									}
								}
							}
							else {
								Ext.Msg.minWidth = 300;
								Ext.MessageBox.alert('<?php echo __('Notice',null,'workflowmanagement'); ?>','<?php echo __('Workflow has already been completed',null,'workflowmanagement'); ?>!');
							}
						},
					scope: c
					});
				}
			}
		});
		
		if(isstopped == 1) {
			btn_copy.html = '<span style="cursor:pointer;"><img src="/images/icons/control_play_blue.png" /></span>';
		}
		if(workflowisstarted == 0) {
			btn_copy.html = '<span style="cursor:pointer;"><img src="/images/icons/control_play.png" /></span>';
		}
		if(iscompleted == 1) {
			btn_copy.html = '<span style="cursor:pointer;"><img src="/images/icons/accept.png" /></span>';
		}
		btn_copy.render('workflowoverview_stop' + template_id);
	},
	
	
	createDetailsButton: function (template_id, activeversion_id, openinpopup) {
		var btn_copy = new Ext.form.Label({
			renderTo: 'workflowoverview_details' + template_id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/zoom.png" /></span>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							cf.workflowdetails.init(template_id, activeversion_id, openinpopup, false, true);
						},
					scope: c
					});
				}
			}
		});
		
	},
	
	createDeleteButton: function (template_id, activeversion_id) {
		var btn_copy = new Ext.form.Label({
			renderTo: 'workflowoverview_delete' + template_id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/delete.png" /></span>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							alert('delete');
						},
					scope: c
					});
				}
			}
		});
		
	}
	
	
	
	
	
};}();