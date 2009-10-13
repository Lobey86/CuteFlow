/** create the grid for the second tab **/
cf.formPopUpWindowFieldGrid = function(){return {

	theFieldGrid					:false,
	theFieldCM						:false,
	theFieldStore					:false,
	theSearchToolbar				:false,
	theSearchbarTextfield			:false,
	theSearchbarCombobox			:false,
	theSearchbarComboboxSelect		:false,
	theTopToolBar					:false,
	
	init: function () {
		this.initSearchbarTextfield();
		this.initSearchbarCombobox();
		this.initSearchbarComboboxSelect();
		this.initCM();
		this.initStore();
		this.initTopToolBar();
		this.initGrid();
	},
	
	initTopToolBar: function () {
		this.theTopToolBar = new Ext.Toolbar({
			items: [this.theSearchbarTextfield,this.theSearchbarCombobox,'-',this.theSearchbarComboboxSelect,'-',
			{
				icon: '/images/icons/delete.png',
	            tooltip:'<?php echo __('Clear field',null,'field'); ?>',
	            handler: function () {
					cf.formPopUpWindowFieldGrid.theSearchbarTextfield.setValue();
					cf.formPopUpWindowFieldGrid.theSearchbarCombobox.setValue();
					var grid = cf.formPopUpWindowFieldGrid.theFieldGrid;
					var needle = cf.formPopUpWindowFieldGrid.theSearchbarCombobox.getRawValue();
					grid.store.filter('title', '');
	            }
			}]
		});	
		
	},
		/** init live-search **/
	initSearchbarTextfield: function () {
		this.theSearchbarTextfield = new Ext.form.TextField({
			allowBlank: true,
			emptyText:'<?php echo __('Search for...',null,'field'); ?>',
			enableKeyEvents: true,
			width: 140,
			listeners: {
				keyup: function(el, type) {
					var grid = cf.formPopUpWindowFieldGrid.theFieldGrid;
					var needle = cf.formPopUpWindowFieldGrid.theSearchbarComboboxSelect.getValue();
					grid.store.filter(needle, el.getValue());
				}
			}
		});
	},
	
	/** init combobox to search by fields **/
	initSearchbarCombobox: function () {
		this.theSearchbarCombobox = new Ext.form.ComboBox({
			valueField: 'id',
			displayField: 'text',
			editable: false,
			mode: 'local',
			emptyText:'<?php echo __('Search for...',null,'field'); ?>',
			store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[
       					 ['TEXTFIELD', '<?php echo __('Textfield',null,'field'); ?>'],
	       				 ['CHECKBOX', '<?php echo __('Checkbox (yes/no)',null,'field'); ?>'],
	       				 ['NUMBER', '<?php echo __('Number',null,'field'); ?>'],
	       				 ['DATE', '<?php echo __('Date',null,'field'); ?>'],
	       				 ['TEXTAREA', '<?php echo __('Textarea',null,'field'); ?>'],
	       				 ['RADIOGROUP', '<?php echo __('Radiogroup',null,'field'); ?>'],
	       				 ['CHECKBOXGROUP', '<?php echo __('Checkboxgroup',null,'field'); ?>'],
	       				 ['COMBOBOX', '<?php echo __('Combobox',null,'field'); ?>'],
	       				 ['FILE', '<?php echo __('File',null,'field'); ?>']
       				 ]
			}),
			triggerAction: 'all',
			selectOnFocus:true,
			allowBlank: true,
			hidden: true,
			forceSelection:true,
			width: 140
		});
		this.theSearchbarCombobox.on('select', function(combo) {
			var grid = cf.formPopUpWindowFieldGrid.theFieldGrid;
			var needle = cf.formPopUpWindowFieldGrid.theSearchbarCombobox.getRawValue();
			grid.store.filter('type', needle);
		});	
	},
	 /** inint combo to chanage the search options **/
	initSearchbarComboboxSelect: function () {
		this.theSearchbarComboboxSelect	= new Ext.form.ComboBox({
			valueField: 'id',
			displayField: 'text',
			editable: false,
			mode: 'local',
			store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[['title', '<?php echo __('Title',null,'field'); ?>'],['type', '<?php echo __('Field type',null,'field'); ?>']]
			}),
			triggerAction: 'all',
			selectOnFocus:true,
			allowBlank: true,
			forceSelection:true,
			value: 'title',
			width: 150,
			listeners: {
				select: {
					fn:function(combo, value) {
						if(combo.getValue() == 'title') {
							cf.formPopUpWindowFieldGrid.theSearchbarTextfield.setVisible(true);
							cf.formPopUpWindowFieldGrid.theSearchbarCombobox.setVisible(false);
							cf.formPopUpWindowFieldGrid.theSearchbarTextfield.setValue();
						}
						else {
							cf.formPopUpWindowFieldGrid.theSearchbarCombobox.setVisible(true);
							cf.formPopUpWindowFieldGrid.theSearchbarTextfield.setVisible(false);
							cf.formPopUpWindowFieldGrid.theSearchbarTextfield.setValue();
							
						}
					}
				}
			}
		});
	},
	
	initCM: function () {
		this.theFieldCM = new Ext.grid.ColumnModel([
			{header: "#", width: 30, sortable: false, dataIndex: '#', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Field name',null,'form'); ?>", width: 175, sortable: false, dataIndex: 'title', css : "text-align : left;font-size:12px;align:left;"},
			{header: "<?php echo __('Field type',null,'form'); ?>", width: 120, sortable: false, dataIndex: 'type', css : "text-align : left;font-size:12px;align:left;"}
		]);
	},
	
	
	initStore: function () {
		this.theFormStore = new Ext.data.JsonStore({
				root: 'result',
				url: '<?php echo build_dynamic_javascript_url('form/LoadAllFields')?>',
				autoload: false,
				fields: [
					{name: '#'},
					{name: 'id'},
					{name: 'type'},
					{name: 'title'}
				]
		});
	},
	
	initGrid: function () {
		this.theFieldGrid = new Ext.grid.GridPanel({
			title: '<?php echo __('Available Fields',null,'form'); ?>',
			stripeRows: true,
			border: false,
			width: 'auto',
			enableDragDrop:true,
			autoScroll: true,
			ddGroup : 'formgrid',
			height: cf.Layout.theRegionWest.getHeight() - 190,
			width:'auto',
			collapsible: false,
			store: this.theFormStore,
			tbar: this.theTopToolBar,
			cm: this.theFieldCM
		});
		this.theFieldGrid.on('afterrender', function(grid) {
			cf.formPopUpWindowFieldGrid.theFormStore.load();
		});	
	
	}



};}();