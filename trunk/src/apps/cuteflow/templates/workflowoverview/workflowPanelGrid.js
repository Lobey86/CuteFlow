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
			{header: "<?php echo __('Current station',null,'workflowmanagement'); ?>", width: 140, sortable: true, dataIndex: 'currentstation', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Template',null,'workflowmanagement'); ?>", width: 150, sortable: true, dataIndex: 'mailinglisttemplate', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Sender',null,'workflowmanagement'); ?>", width: 230, sortable: true, dataIndex: 'sendername', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<?php echo __('Running for',null,'workflowmanagement'); ?>", width: 80, sortable: true, dataIndex: 'currentlyrunning', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<?php echo __('Sendet at',null,'workflowmanagement'); ?>", width: 120, sortable: true, dataIndex: 'versioncreated_at', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/table_edit.png' />&nbsp;&nbsp;</td><td><?php echo __('Edit Document template',null,'documenttemplate'); ?></td></tr><tr><td><img src='/images/icons/clock.png' />&nbsp;&nbsp;</td><td><?php echo __('Show Document template versions',null,'documenttemplate'); ?></td></tr><tr><td><img src='/images/icons/table_delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Delete Document template',null,'documenttemplate'); ?></td></tr></table>\" ext:qwidth=\"230\"><?php echo __('Action',null,'documenttemplate'); ?></div>", width: 80, sortable: false, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;", renderer: this.renderButton}
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
					{name: 'isstopped'},
					{name: 'openinpopup'},
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
			height: cf.Layout.theRegionWest.getHeight() - 100,
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
		var btnDetails = cf.workflowmanagementPanelGrid.createDetailsButton.defer(10,this, [id, activeversion_id, openinpopup]);
		var btnEdit = cf.workflowmanagementPanelGrid.createDeleteButton.defer(10,this, [id, activeversion_id]);
		var btnEdit = cf.workflowmanagementPanelGrid.createStopButton.defer(10,this, [id, activeversion_id, isstopped]);
		return '<table><tr><td width="16"><div id="workflowoverview_stop'+ id +'"></div></td><td width="16"><div id="workflowoverview_delete'+ id +'"></div></td><td width="16"><div id="workflowoverview_details'+ id +'"></div></td></tr></table></center>';
	},
	
	
	createStopButton: function (template_id, activeversion_id, isstopped) {
		var btn_copy = new Ext.form.Label({
			html: '<span style="cursor:pointer;"><img src="/images/icons/stop.png" /></span>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							if(isstopped == 1) {
								alert('restart');
							}
							else {
								cf.workflowmanagementPanelCRUD.stopWorkflow(template_id, activeversion_id);
							}
						},
					scope: c
					});
				}
			}
		});
		
		if(isstopped == 1) {
			btn_copy.html = '<span style="cursor:pointer;"><img src="/images/icons/resultset_next.png" /></span>';
		}
		btn_copy.render('workflowoverview_details' + template_id);
	},
	
	
	createDetailsButton: function (template_id, activeversion_id, openinpopup) {
		var btn_copy = new Ext.form.Label({
			renderTo: 'workflowoverview_details' + template_id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/zoom.png" /></span>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							cf.workflowdetails.init(template_id, activeversion_id, openinpopup, false);
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