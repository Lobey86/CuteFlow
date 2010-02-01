cf.mailinglistThirdTab = function(){return {
	
	
	
	
	thePanel 					:false,
	theAuthorizationFieldset	:false,
	theAuthorizationStore		:false,
	theAuthorizationCM			:false,
	theAuthorizationGrid		:false,
	
	
	init:function (storeurl,id) {
		this.initPanel();
		this.initAuthCM();
		this.initAuthStore(storeurl);
		this.initAuthGrid(storeurl);
		this.initAuthorizationFieldset();
		
		this.theAuthorizationFieldset.add(this.theAuthorizationGrid);
		this.thePanel.add(this.theAuthorizationFieldset);
		
	},
	
	
	/** init first tab formpanel **/
	initPanel: function () {
		this.thePanel = new Ext.FormPanel({
			title: '<?php echo __('Authorization settings',null,'mailinglist'); ?>',
			frame:true,
			autoScroll: true,
			height: cf.Layout.theRegionWest.getHeight() + cf.Layout.theRegionNorth.getHeight() - 148
		});
	},
	
		/** init auth fieldset **/
	initAuthorizationFieldset: function () {
		this.theAuthorizationFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Authorization',null,'mailinglist'); ?>',
			width: 700,
			height: cf.Layout.theRegionWest.getHeight() + cf.Layout.theRegionNorth.getHeight() - 200,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 120
		});
	
	},
	
	
	
	/**
	* init auth store, url is different in edit and new mode
	*
	*@param string url, url to load
	*/
	initAuthStore: function (url) {
		this.theAuthorizationStore = new Ext.data.Store({
				root: 'result',
				fields: [
					{name: 'type'},
					{name: 'id'},
					{name: 'raw_type'},
					{name: 'deleteworkflow'},
					{name: 'archiveworkflow'},
					{name: 'stopneworkflow'},
					{name: 'detailsworkflow'}
				]
		});
		
	},
	
	
		/** init cm for auth grid **/
	initAuthCM: function () {
		this.theAuthorizationCM  =  new Ext.grid.ColumnModel([
			{header: "<?php echo __('Action',null,'mailinglist'); ?>",  width: 200, sortable: false, dataIndex: 'type', css : "text-align :left; font-size:12px;"},
			{header: "<?php echo __('delete workflow',null,'mailinglist'); ?>",  width: 100, sortable: false, dataIndex: 'deleteworkflow', css : "text-align :left; font-size:12px;"},
			{header: "<?php echo __('archive workflow',null,'mailinglist'); ?>",  width: 120, sortable: false, dataIndex: 'archiveworkflow', css : "text-align :left; font-size:12px;"},
			{header: "<?php echo __('stop/new workflow',null,'mailinglist'); ?>",  width: 120, sortable: false, dataIndex: 'stopneworkflow', css : "text-align :left; font-size:12px;"},
			{header: "<?php echo __('show workflow details',null,'mailinglist'); ?>",  width: 130, sortable: false, dataIndex: 'detailsworkflow', css : "text-align :left; font-size:12px;"}
		]);	
	},
	
		
	/** init auth grid **/
	initAuthGrid: function (storeurl) {
		this.theAuthorizationGrid = new Ext.grid.GridPanel({
			frame:false,
			autoScroll: true,
			collapsible:false,
			closable: false,
			height: cf.Layout.theRegionWest.getHeight() + cf.Layout.theRegionNorth.getHeight() - 230,
			width: 'auto',
			border: true,
			store: this.theAuthorizationStore,
			cm: this.theAuthorizationCM
		});
		this.theAuthorizationGrid.on('render', function(grid) {
			Ext.Ajax.request({  
				url : storeurl,
				success: function(objServerResponse){
					var ServerResult = Ext.util.JSON.decode(objServerResponse.responseText);
					var data = ServerResult.result;
					for(var a=0;a<data.length;a++) {
						var item = data[a];
						var Rec = Ext.data.Record.create(
							{name: 'type'},
							{name: 'id'},
							{name: 'raw_type'},
							{name: 'deleteworkflow'},
							{name: 'archiveworkflow'},
							{name: 'stopneworkflow'},
							{name: 'detailsworkflow'}	
						);	
						
						
						var deletewf = cf.mailinglistThirdTab.createCheckbox(item.raw_type,'deleteworkflow',item.deleteworkflow);
						var archive = cf.mailinglistThirdTab.createCheckbox(item.raw_type,'archiveworkflow',item.archiveworkflow);
						var stopnew = cf.mailinglistThirdTab.createCheckbox(item.raw_type,'stopneworkflow',item.stopneworkflow);
						var details = cf.mailinglistThirdTab.createCheckbox(item.raw_type,'detailsworkflow',item.detailsworkflow);
						
			
						var id = item.raw_type;
						grid.store.add(new Rec({
							type: item.type,
							id: item.id, 
							raw_type: item.raw_type,
							deleteworkflow: '<center><table><tr><td><div id="deleteworkflow"></div></td></tr></table></center>',
							archiveworkflow: archive,
							stopneworkflow: stopnew,
							detailsworkflow: details
						}));
						
						
						
					}
					
					deletewf.applyToMarkup('deleteworkflow');
				}
				
				
			});
			
			
		});
	
	},
		/** create checkbox, toactivate a item **/
	createCheckbox: function (id, table, value) {
		value = value == 0 ? false : true;
		var name = id + '__' + table;
		var check = new Ext.form.Checkbox({
			name: 'mailinglistFirstTab[' + name + ']',
			inputValue: 1,
			checked: value
		});
		return check;
	}
	
	
		
	/** render checkbox to grid **/
	/*renderDeleteCheckbox: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var id = record.data['raw_type'];
		cf.mailinglistThirdTab.createCheckbox.defer(1500,this, [id, 'mailinglistFirstTabCheckboxDelete_'+ id, 'deleteworkflow', record.data['deleteworkflow']]);
		return '<center><table><tr><td><div id="mailinglistFirstTabCheckboxDelete_'+ id +'"></div></td></tr></table></center>';
	},
	renderArchiveCheckbox: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var id = record.data['raw_type'];
		cf.mailinglistThirdTab.createCheckbox.defer(1500,this, [id, 'mailinglistFirstTabCheckboxArchive_'+ id, 'archiveworkflow', record.data['archiveworkflow']]);
		return '<center><table><tr><td><div id="mailinglistFirstTabCheckboxArchive_'+ id +'"></div></td></tr></table></center>';
	},
	renderStopNewCheckbox: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var id = record.data['raw_type'];
		cf.mailinglistThirdTab.createCheckbox.defer(1500,this, [id, 'mailinglistFirstTabCheckboxStopNew_'+ id, 'stopneworkflow', record.data['stopneworkflow']]);
		return '<center><table><tr><td><div id="mailinglistFirstTabCheckboxStopNew_'+ id +'"></div></td></tr></table></center>';
	},
	renderShowCheckbox: function (data, cell, record, rowIndex, columnIndex, store, grid) {
		var id = record.data['raw_type'];
		cf.mailinglistThirdTab.createCheckbox.defer(1500,this, [id, 'mailinglistFirstTabCheckboxShow_'+ id, 'detailsworkflow', record.data['detailsworkflow']]);
		return '<center><table><tr><td><div id="mailinglistFirstTabCheckboxShow_'+ id +'"></div></td></tr></table></center>';
	},*/
	

	
	
};}();