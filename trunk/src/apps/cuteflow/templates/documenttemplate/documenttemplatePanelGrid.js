/** class loads the overview grid **/
cf.documenttemplatePanelGrid = function(){return {
	
	theDocumenttemplateGrid			:false,
	isInitialized					:false,
	theDocumenttemplateStore		:false,
	theDocumenttemplateCM			:false,
	theTopToolBar					:false,
	theBottomToolBar				:false,
	theLoadingMask					:false,

	
	/** inits all necessary functions to build the grid and its toolbars **/
	init: function () {
		cf.documenttemplatePanelGrid.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Loading Data...',null,'documenttemplate'); ?>'});					
		cf.documenttemplatePanelGrid.theLoadingMask.show();
		this.initStore();
		this.initBottomToolbar();
		this.initCM();
		this.initTopToolBar();
		this.initGrid();
	},
	
	doSearch: function () {
		var textfield = Ext.getCmp('documenttemplatePanelGrid_searchtextfield').getValue();
		if(textfield != '') {
			
			var url = encodeURI('<?php echo build_dynamic_javascript_url('form/LoadAllFormsFilter')?>/name/' + textfield);
			cf.documenttemplatePanelGrid.theDocumenttemplateStore.proxy.setApi(Ext.data.Api.actions.read,url);
			cf.documenttemplatePanelGrid.theDocumenttemplateStore.reload();	
			
		}
	},
	
	
	/** init CM for the grid **/
	initCM: function () {
		this.theDocumenttemplateCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: true, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Name',null,'documenttemplate'); ?>", width: 280, sortable: false, dataIndex: 'name', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('mount of slots',null,'documenttemplate'); ?>", width: 150, sortable: false, dataIndex: 'mountofslots', css : "text-align:center;font-size:12px;align:center;"},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/table_edit.png' />&nbsp;&nbsp;</td><td><?php echo __('Edit Document template',null,'documenttemplate'); ?></td></tr><tr><td><img src='/images/icons/table_delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Delete Document template',null,'documenttemplate'); ?></td></tr></table>\" ext:qwidth=\"200\"><?php echo __('Action',null,'documenttemplate'); ?></div>", width: 80, sortable: false, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;" ,renderer: this.renderAction}
		]);
	},
	
	/** init store for the grid **/
	initStore: function () {
		this.theDocumenttemplateStore = new Ext.data.JsonStore({
				root: 'result',
				totalProperty: 'total',
				url: '<?php echo build_dynamic_javascript_url('documenttemplates/LoadAllDocumenttemplates')?>',
				autoload: false,
				fields: [
					{name: '#'},
					{name: 'id'},
					{name: 'name'},
					{name: 'mountofslots'}
				]
		});
	},
	/** init toolbar for grid, contains ajax search **/
	initTopToolBar: function () {
		this.theTopToolBar = new Ext.Toolbar({
			items: [{
				xtype: 'textfield',
				id: 'documenttemplatePanelGrid_searchtextfield',
				emptyText:'<?php echo __('Search for name',null,'documenttemplate'); ?>',
				width:180
			},{
				xtype: 'button',
				text: '<?php echo __('Search',null,'documenttemplate'); ?>',
				icon: '/images/icons/table_go.png',
				handler: function (){
					cf.documenttemplatePanelGrid.doSearch();
				}
			},'-',{
				xtype: 'button',
				tooltip: '<?php echo __('Clear field',null,'documenttemplate'); ?>',
				icon: '/images/icons/delete.png',
				handler: function () {
					var textfield = Ext.getCmp('documenttemplatePanelGrid_searchtextfield').setValue();
					var url = encodeURI('<?php echo build_dynamic_javascript_url('form/LoadAllForms')?>/name/' + textfield);
					cf.documenttemplatePanelGrid.theDocumenttemplateStore.proxy.setApi(Ext.data.Api.actions.read,url);
					cf.documenttemplatePanelGrid.theDocumenttemplateStore.reload();	
				}
			},'-',{	
				icon: '/images/icons/table_add.png',
	            tooltip:'<?php echo __('Add new Document template',null,'documenttemplate'); ?>',
				disabled: <?php $arr = $sf_user->getAttribute('credential');echo $arr['management_documenttemplate_addDocumenttemplate'];?>,
				style: 'margin-left:20px;',
	            handler: function () {
					cf.documenttemplatePopUpWindow.initNewDocumenttemplate('');
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
		    				cf.documenttemplatePanelGrid.theBottomToolBar.pageSize = combo.getValue();
		    				cf.documenttemplatePanelGrid.theDocumenttemplateStore.load({params:{start: 0, limit: combo.getValue()}});
		    			}
		    		}
		    	}
			}]
		});	
		
	},
	
	/** init paging toolbar **/
	initBottomToolbar: function () {
		this.theBottomToolBar =  new Ext.PagingToolbar({
			pageSize: <?php $arr = $sf_user->getAttribute('userSettings'); echo $arr['displayeditem'];?>,
			store: this.theDocumenttemplateStore,
			displayInfo: true,
			style: 'margin-bottom:10px;',
			displayMsg: '<?php echo __('Displaying topics',null,'documenttemplate'); ?> {0} - {1} <?php echo __('of',null,'documenttemplate'); ?> {2}',
			emptyMsg: '<?php echo __('No records found',null,'documenttemplate'); ?>'
		});
	},
	
	/** init grid **/
	initGrid: function () {
		this.theDocumenttemplateGrid = new Ext.grid.GridPanel({
			title: '<?php echo __('Document templates',null,'documenttemplate'); ?>',
			stripeRows: true,
			border: true,
			width: 'auto',
			height: cf.Layout.theRegionWest.getHeight() - 100,
			collapsible: false,
			style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			store: this.theDocumenttemplateStore,
			tbar: this.theTopToolBar,
			bbar: this.theBottomToolBar,
			cm: this.theDocumenttemplateCM
		});
		this.theDocumenttemplateGrid.on('afterrender', function(grid) {
			cf.documenttemplatePanelGrid.theDocumenttemplateStore.load();
			cf.documenttemplatePanelGrid.theLoadingMask.hide();
		});	
		
	}, 
	
	/** button renderer for edit and delete **/
	renderAction: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		cf.documenttemplatePanelGrid.createEditButton.defer(500,this, [record.data['id']]);
		cf.documenttemplatePanelGrid.createDeleteButton.defer(500,this, [record.data['id']]);
		return '<center><table><tr><td width="16"><div id="form_edit'+ record.data['id'] +'"></div></td><td width="16"><div id="form_delete'+ record.data['id'] +'"></div></td></tr></table></center>';
	},
	
	/**
	* edit button
	*
	*@param int id, id of the record
	*/
	createEditButton: function (id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'form_edit' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/table_edit.png" /></span>',
			disabled: <?php $arr = $sf_user->getAttribute('credential');echo $arr['management_documenttemplate_editDocumenttemplate'];?>,
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							if (c.disabled == false) {
								cf.documenttemplatePopUpWindow.initEditDocumenttemplate(id);
							}
						},
					scope: c
				});
				}
			}
		});
	},
	/**
	* create delete button
	*
	*@param int id, id of record
	*
	*/
	createDeleteButton: function (id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'form_delete' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/table_delete.png" /></span>',
			disabled: <?php $arr = $sf_user->getAttribute('credential');echo $arr['management_documenttemplate_deleteDocumenttemplate'];?>,
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							if (c.disabled == false) {
								Ext.Msg.show({
								   title:'<?php echo __('Delete form?',null,'documenttemplate'); ?>',
								   msg: '<?php echo __('Delete form?',null,'documenttemplate'); ?>',
								   buttons: Ext.Msg.YESNO,
								   fn: function(btn, text) {
										if(btn == 'yes') {
											cf.formCRUD.initDelete(id);
										}
								   }
								});
							}
						},
					scope: c
				});
				}
			}
		});
	}
};}();