cf.workflowdetailsDetails = function(){return {
	
	theFieldset 					:false,
	theCM							:false,
	theStore						:false,
	theGrid							:false,
	
	
	init: function (data) {
		this.initCM();
		this.initStore();
		this.initFieldset();
		
	},
	
	
	initGrid: function () {
		this.theGrid = new Ext.grid.GridPanel({
			//title: '<?php echo __('Workflow templates',null,'workflowmanagement'); ?>',
			stripeRows: true,
			border: true,
			width: 'auto',
			height: cf.Layout.theRegionWest.getHeight() - 400,
			collapsible: false,
			style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			store: this.theStore,
			cm: this.theCM
		});
		
	},
	
	initStore: function () {
		var reader = new Ext.data.ArrayReader({}, [
			{name: 'station'},
			{name: 'receivedat'},
			{name: 'status'},
			{name: 'duration'},
			{name: 'slotgroup'},
			{name: 'action'}
		]);
		this.theStore = new Ext.data.GroupingStore({
            reader: reader,
            groupField:'slotgroup'
        })
		
	},
	
	initCM: function () {
		this.theCM  =  new Ext.grid.ColumnModel([
			{header: "<?php echo __('Station',null,'workflowmanagement'); ?>", width: 150, sortable: true, dataIndex: 'station', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Received at',null,'workflowmanagement'); ?>", width: 150, sortable: true, dataIndex: 'receivedat', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Status',null,'workflowmanagement'); ?>", width: 150, sortable: true, dataIndex: 'status', css : "text-align : left;font-size:12px;align:center;", hidden: false},
			{header: "<?php echo __('Bearbeitungsdauer',null,'workflowmanagement'); ?>", width: 150, sortable: true, dataIndex: 'duration', css : "text-align : left;font-size:12px;align:center;",  hidden: false},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/table_edit.png' />&nbsp;&nbsp;</td><td><?php echo __('Edit Document template',null,'documenttemplate'); ?></td></tr><tr><td><img src='/images/icons/clock.png' />&nbsp;&nbsp;</td><td><?php echo __('Show Document template versions',null,'documenttemplate'); ?></td></tr><tr><td><img src='/images/icons/table_delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Delete Document template',null,'documenttemplate'); ?></td></tr></table>\" ext:qwidth=\"230\"><?php echo __('Action',null,'documenttemplate'); ?></div>", width: 80, sortable: false, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;"/*, renderer: this.renderButton*/}
		]);
		
	},
	
	initFieldset: function () {
		this.theFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Workflow details',null,'workflowmanagement'); ?>',
			allowBlank: false,
			style: 'margin-top:5px;margin-left:10px;',
			width: cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 100,
			height: 'auto'
		});
		
		
	}
	
	
	
	
	
	
	
	
};}();