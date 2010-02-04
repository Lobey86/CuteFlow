cf.todoPanelGrid = function(){return {
	
	theTodoGrid						:false,
	isInitialized					:false,
	theTodoStore					:false,
	theTodoCM						:false,
	theTopToolBar					:false,
	theBottomToolBar				:false,
	theLoadingMask					:false,
	
	init:function () {
		this.initStore();
		//this.initBottomToolbar();
		this.initCM();
		this.initTopToolBar();
		this.initGrid();
	},
	
	
	/** init CM for the grid **/
	initCM: function () {
		<?php $arr = $sf_user->getAttribute('userWorkflowSettings');?>
		this.theTodoCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: true, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo $arr[0]['text'];?>", width: <?php echo $arr[0]['width']; ?>, sortable: true, dataIndex: '<?php echo $arr[0]['store']; ?>', css : "text-align : left;font-size:12px;align:center;", hidden: <?php echo $arr[0]['hidden']; ?>},
			{header: "<?php echo $arr[1]['text'];?>", width: <?php echo $arr[1]['width']; ?>, sortable: true, dataIndex: '<?php echo $arr[1]['store']; ?>', css : "text-align : left;font-size:12px;align:center;", hidden: <?php echo $arr[1]['hidden']; ?>},
			{header: "<?php echo $arr[2]['text'];?>", width: <?php echo $arr[2]['width']; ?>, sortable: true, dataIndex: '<?php echo $arr[2]['store']; ?>', css : "text-align : left;font-size:12px;align:center;", hidden: <?php echo $arr[2]['hidden']; ?>},
			{header: "<?php echo $arr[3]['text'];?>", width: <?php echo $arr[3]['width']; ?>, sortable: true, dataIndex: '<?php echo $arr[3]['store']; ?>', css : "text-align : left;font-size:12px;align:center;", hidden: <?php echo $arr[3]['hidden']; ?>},
			{header: "<?php echo $arr[4]['text'];?>", width: <?php echo $arr[4]['width']; ?>, sortable: true, dataIndex: '<?php echo $arr[4]['store']; ?>', css : "text-align : left;font-size:12px;align:center;", hidden: <?php echo $arr[4]['hidden']; ?>},
			{header: "<?php echo $arr[5]['text'];?>", width: <?php echo $arr[5]['width']; ?>, sortable: true, dataIndex: '<?php echo $arr[5]['store']; ?>', css : "text-align : left;font-size:12px;align:center;", hidden: <?php echo $arr[5]['hidden']; ?>},
			{header: "<?php echo $arr[6]['text'];?>", width: <?php echo $arr[6]['width']; ?>, sortable: true, dataIndex: '<?php echo $arr[6]['store']; ?>', css : "text-align : left;font-size:12px;align:center;", hidden: <?php echo $arr[6]['hidden']; ?>},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/table_edit.png' />&nbsp;&nbsp;</td><td><?php echo __('Edit Document template',null,'documenttemplate'); ?></td></tr><tr><td><img src='/images/icons/clock.png' />&nbsp;&nbsp;</td><td><?php echo __('Show Document template versions',null,'documenttemplate'); ?></td></tr><tr><td><img src='/images/icons/table_delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Delete Document template',null,'documenttemplate'); ?></td></tr></table>\" ext:qwidth=\"230\"><?php echo __('Action',null,'documenttemplate'); ?></div>", width: 80, sortable: false, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;", renderer: this.renderButton}
		]);
	},
	
	/** init store for the grid **/
	initStore: function () {
		this.theTodoStore = new Ext.data.JsonStore({
				root: 'result',
				totalProperty: 'total',
				url: '<?php echo build_dynamic_javascript_url('todooverview/LoadAllOwnWorkflow')?>',
				autoload: false,
				fields: [
					{name: '#'},
					{name: 'id'},
					{name: 'mailinglisttemplate_id'},
					{name: 'mailinglisttemplate'},
					{name: 'sender_id'},
					{name: 'sendername'},
					{name: 'currentstation'},
					{name: 'stationrunning'},
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
		this.theTodoGrid = new Ext.grid.GridPanel({
			title: '<?php echo __('Workflow templates',null,'workflowmanagement'); ?>',
			stripeRows: true,
			border: true,
			width: 'auto',
			loadMask: true,
			height: cf.Layout.theRegionWest.getHeight() - 100,
			collapsible: false,
			style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			store: this.theTodoStore,
			tbar: this.theTopToolBar,
			bbar: this.theBottomToolBar,
			cm: this.theTodoCM
		});
		this.theTodoGrid.on('afterrender', function(grid) {
			cf.todoPanelGrid.theTodoStore.load();
		});	
		
	},
	
	renderButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var id = record.data['id'];
		var activeversion_id = record.data['activeversion_id'];
		var isstopped = record.data['isstopped'];
		
		var rights = record.data['auth'];
		
		var btnDetails = cf.todoPanelGrid.createDetailsButton.defer(10,this, [id, activeversion_id, rights.detailsworkflow]);
		var btnEdit = cf.todoPanelGrid.createDeleteButton.defer(10,this, [id, activeversion_id, rights.deleteworkflow]);
		var btnEdit = cf.todoPanelGrid.createEditButton.defer(10,this, [id, activeversion_id]);
		return '<center><table><tr><td width="16"><div id="todooverview_delete'+ id +'"></div></td><td width="16"><div id="todooverview_details'+ id +'"></div></td><td width="16"><div id="todooverview_edit'+ id +'"></div></td></tr></table></center>';
	},
	
	
	createEditButton: function (template_id, activeversion_id) {
		var btn_copy = new Ext.form.Label({
			html: '<span style="cursor:pointer;"><img src="/images/icons/pencil.png" /></span>',
			renderTo: 'todooverview_edit' + template_id,
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							cf.workflowedit.init(template_id, activeversion_id);
						},
					scope: c
					});
				}
			}
		});
	},
	
	
	createDetailsButton: function (template_id, activeversion_id, right) {
		var btn_copy = new Ext.form.Label({
			renderTo: 'todooverview_details' + template_id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/zoom.png" /></span>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							if(right == 1) {
								cf.workflowdetails.init(template_id, activeversion_id, false, false);
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
			renderTo: 'todooverview_delete' + template_id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/delete.png" /></span>',
			listeners: {
				render: function(c){
					c.getEl().on({
						click: function(el){
							if(right == 1) {
								alert('delete');
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