cf.documenttemplateVersionFirstTab = function(){return {
	
	
	thePanel				:false,
	theFieldset				:false,
	theGrid					:false,
	theGridCM				:false,
	theGridStore			:false,
	
	
	init:function (parent_id) {
		this.initStore(parent_id);
		this.initCM();
		this.initGrid();
		this.initPanel();
		this.initFieldset();
		this.theFieldset.add(this.theGrid);
		this.thePanel.add(this.theFieldset);
	},
	
	
	
	initStore: function (parent_id) {
		this.theGridStore =  new Ext.data.JsonStore({
			root: 'result',
			url: '<?php echo build_dynamic_javascript_url('documenttemplate/LoadAllVersion')?>/id/' + parent_id,
			autoload: false,
			fields: [
				{name: '#'},
				{name: 'id'},
				{name: 'documenttemplate_id'},
				{name: 'name'},
				{name: 'activeversion'},
				{name: 'created_at'}
			]
		});	
		
	},
	
	initCM: function () {
		this.theGridCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: true, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Name',null,'documenttemplate'); ?>", width: 280, sortable: false, dataIndex: 'name', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('created at',null,'documenttemplate'); ?>", width: 130, sortable: false, dataIndex: 'created_at', css : "text-align:center;font-size:12px;align:center;"},
			{header: "<?php echo __('currently active',null,'documenttemplate'); ?>", width: 120, sortable: false, dataIndex: 'activeversion', css : "text-align:center;font-size:12px;align:center;"},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/clock_go.png' />&nbsp;&nbsp;</td><td><?php echo __('Activate Document template',null,'documenttemplate'); ?></td></tr><tr><td><img src='/images/icons/zoom.png' />&nbsp;&nbsp;</td><td><?php echo __('Show Document template version',null,'documenttemplate'); ?></td></tr></table>\" ext:qwidth=\"230\"><?php echo __('Action',null,'documenttemplate'); ?></div>", width: 80, sortable: false, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;" ,renderer: this.renderAction}
		]);
	},
	
	
	
	
		/** button renderer for edit and delete **/
	renderAction: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		cf.documenttemplateVersionFirstTab.createActivateButton.defer(500,this, [record.data['id'],record.data['documenttemplate_id']]);
		cf.documenttemplateVersionFirstTab.createShowButton.defer(500,this, [record.data['#'],record.data['id'],record.data['created_at'],record.data['documenttemplate_id']]);
		return '<center><table><tr><td width="16"><div id="documenttemplateversion_activate'+ record.data['id'] +'"></div></td><td width="16"><div id="documenttemplateversion_show'+ record.data['id'] +'"></div></td></tr></table></center>';
	},
	
		/**
	* version button
	*
	*@param int id, id of the record
	*/
	createActivateButton: function (id, documenttemplate_id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'documenttemplateversion_activate' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/clock_go.png" /></span>',
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							Ext.Msg.show({
							   title:'<?php echo __('Activate Template?',null,'documenttemplate'); ?>',
							   msg: '<?php echo __('Activate Template?',null,'documenttemplate'); ?>',
							   buttons: Ext.Msg.YESNO,
							   fn: function(btn, text) {
									if(btn == 'yes') {
										Ext.Ajax.request({  
											url : '<?php echo build_dynamic_javascript_url('documenttemplate/ActivateDocumenttemplate')?>/id/' + id + '/documenttemplateid/' + documenttemplate_id, 
											success: function(objServerResponse){
												cf.documenttemplateVersionPopUp.theVersionWindow.hide();
												cf.documenttemplateVersionPopUp.theVersionWindow.destroy();
												cf.documenttemplatePanelGrid.theDocumenttemplateStore.reload();
												Ext.Msg.minWidth = 200;
												Ext.MessageBox.alert('<?php echo __('OK',null,'documenttemplate'); ?>','<?php echo __('Template activated',null,'documenttemplate'); ?>');
											}
										});
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
	/**
	* edit button
	*
	*@param int id, id of the record
	*/
	createShowButton: function (grid_id, id, created_at,documenttemplateid) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'documenttemplateversion_show' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/zoom.png" /></span>',
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							Ext.Ajax.request({  
								url : '<?php echo build_dynamic_javascript_url('documenttemplate/LoadSingleDocumenttemplate')?>/id/' + id, 
								success: function(objServerResponse){
									theJsonTreeData = Ext.util.JSON.decode(objServerResponse.responseText);
									cf.documenttemplateVersionSecondTab.init(id, theJsonTreeData.result, created_at, grid_id, documenttemplateid);
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
			title: '<?php echo __('Document templates',null,'documenttemplate'); ?>',
			stripeRows: true,
			border: true,
			width: 'auto',
			height: cf.Layout.theRegionWest.getHeight() - 210,
			collapsible: false,
			style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			store: this.theGridStore,
			cm: this.theGridCM
		});
		this.theGrid.on('afterrender', function(grid) {
			cf.documenttemplateVersionFirstTab.theGridStore.load();
		});	
		
	},
	
	initFieldset:function () {
		this.theFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Select Document template',null,'documenttemplate'); ?>',
			width: 'auto',
			height: cf.Layout.theRegionWest.getHeight() - 190,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 170
		});
		
	},
	
	
	initPanel: function () {
		this.thePanel = new Ext.Panel({
			title: '<?php echo __('Select Document template',null,'documenttemplate'); ?>',
			frame:true,
			height: cf.Layout.theRegionWest.getHeight() - 148
		});
		
	}
	
	
	
	
	
	
	
	
};}();