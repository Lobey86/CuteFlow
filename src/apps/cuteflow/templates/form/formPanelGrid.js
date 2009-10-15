/** class loads the overview grid **/
cf.formPanelGrid = function(){return {
	
	theFormGrid						:false,
	isInitialized					:false,
	theFormStore					:false,
	theFormCM						:false,
	theTopToolBar					:false,
	theBottomToolBar				:false,
	theLoadingMask					:false,

	
	/** inits all necessary functions to build the grid and its toolbars **/
	init: function () {
		cf.formPanelGrid.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Loading Data...',null,'form'); ?>'});					
		cf.formPanelGrid.theLoadingMask.show();
		this.initBottomToolbar();
		this.initCM();
		this.initStore();
		this.initTopToolBar();
		this.initGrid();
	},
	
	
	/** init CM for the grid **/
	initCM: function () {
		this.theFormCM  =  new Ext.grid.ColumnModel([
			{header: "#", width: 50, sortable: true, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Name',null,'form'); ?>", width: 280, sortable: false, dataIndex: 'name', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('mount of slots',null,'form'); ?>", width: 150, sortable: false, dataIndex: 'mountofslots', css : "text-align:center;font-size:12px;align:center;"},
			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/table_edit.png' />&nbsp;&nbsp;</td><td><?php echo __('Edit Document template',null,'form'); ?></td></tr><tr><td><img src='/images/icons/table_delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Delete Document template',null,'form'); ?></td></tr></table>\" ext:qwidth=\"200\"><?php echo __('Action',null,'form'); ?></div>", width: 80, sortable: false, dataIndex: 'action', css : "text-align : left;font-size:12px;align:center;" ,renderer: this.renderAction}
		]);
	},
	
	/** init store for the grid **/
	initStore: function () {
		this.theFormStore = new Ext.data.JsonStore({
				root: 'result',
				url: '<?php echo build_dynamic_javascript_url('form/LoadAllForms')?>',
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
				emptyText:'<?php echo __('Search for name',null,'form'); ?>',
				width:180
			},{
				xtype: 'button',
				text: '<?php echo __('Search',null,'form'); ?>',
				icon: '/images/icons/table_go.png'
			},'-',{	
				icon: '/images/icons/table_add.png',
	            tooltip:'<?php echo __('Add new Document template',null,'form'); ?>',
				disabled: <?php $arr = $sf_user->getAttribute('credential');echo $arr['management_form_addForm'];?>,
	            handler: function () {
					cf.createFormWindow.initNewForm('');
	            }
				
			}]
		});	
		
	},
	
	/** init paging toolbar **/
	initBottomToolbar: function () {
		this.theBottomToolBar =  new Ext.PagingToolbar({
			pageSize: <?php $arr = $sf_user->getAttribute('userSettings'); echo $arr['displayeditem'];?>,
			store: this.theFormStore,
			displayInfo: true,
			style: 'margin-bottom:10px;',
			displayMsg: '<?php echo __('Displaying topics',null,'form'); ?> {0} - {1} <?php echo __('of',null,'form'); ?> {2}',
			emptyMsg: '<?php echo __('No records found',null,'form'); ?>'
		});
	},
	
	/** init grid **/
	initGrid: function () {
		this.theFormGrid = new Ext.grid.GridPanel({
			title: '<?php echo __('Document templates',null,'form'); ?>',
			stripeRows: true,
			border: true,
			width: 'auto',
			height: cf.Layout.theRegionWest.getHeight() - 100,
			collapsible: false,
			style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			store: this.theFormStore,
			tbar: this.theTopToolBar,
			bbar: this.theBottomToolBar,
			cm: this.theFormCM
		});
		this.theFormGrid.on('afterrender', function(grid) {
			cf.formPanelGrid.theFormStore.load();
			cf.formPanelGrid.theLoadingMask.hide();
		});	
		
	}, 
	
	/** button renderer for edit and delete **/
	renderAction: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		cf.formPanelGrid.createEditButton.defer(500,this, [record.data['id']]);
		cf.formPanelGrid.createDeleteButton.defer(500,this, [record.data['id']]);
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
			disabled: <?php $arr = $sf_user->getAttribute('credential');echo $arr['management_form_editForm'];?>,
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							if (c.disabled == false) {
								cf.createFormWindow.initEditForm(id);
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
			disabled: <?php $arr = $sf_user->getAttribute('credential');echo $arr['management_form_deleteForm'];?>,
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							if (c.disabled == false) {
								cf.formCRUD.initDelete(id);
							}
						},
					scope: c
				});
				}
			}
		});
	}
};}();