cf.userFourthTab = function(){return {	thePanel				:false,	theGuiFieldset			:false,	theWorkflowFieldset		:false,		theFourthGrid			:false,	theFourthCM				:false,	theFourthStore			:false,	theFourthTopToolBar		:false,					init: function (storeURL) {		this.initFourthPanel();		this.initGuiFieldset();		this.initWorkflowFieldset();		this.initCM();		this.initStore(storeURL);		this.initGrid();		this.thePanel.add(this.theGuiFieldset);		this.theWorkflowFieldset.add(this.theFourthGrid);		this.thePanel.add(this.theWorkflowFieldset);	},		initCM: function () {		this.theFourthCM  =  new Ext.grid.ColumnModel([			{header: "<center><div ext:qtip=\"<table><tr><td><img src='/images/icons/checkbox.png' />&nbsp;&nbsp;</td><td><?php echo __('Activate Item',null,'systemsetting'); ?></td></tr></table>\" ext:qwidth=\"200\"><?php echo __('Activate Item',null,'usermanagement'); ?></div></center>",  width: 90, sortable: false, dataIndex: 'id', css : "text-align :left; font-size:12px;", renderer: cf.userFourthTab.renderAction},			{header: "<?php echo __('Column',null,'usermanagement'); ?>",  width: 200, sortable: false, dataIndex: 'columntext', css : "text-align :left; font-size:12px;"}		]);		},		initStore: function (storeURL) {		this.theFourthStore = new Ext.data.JsonStore({			totalProperty: 'total',			root: 'result',			url:  storeURL,			fields: [				{name: 'id'},				{name: 'columntext'},				{name: 'column'},				{name: 'isactive',type: 'bool'}			]		});		cf.userFourthTab.theFourthStore.load();		},		initGrid: function () {		this.theFourthGrid = new Ext.grid.GridPanel({			frame:false,			autoScroll: true,			collapsible:false,			closable: false,			ddGroup : 'userFourthTabGridDD',			height: 340,			width: 400,			allowContainerDrop : true,			enableDragDrop:true,			border: true,			store: this.theFourthStore,			cm: this.theFourthCM		});			this.theFourthGrid.on('render', function(grid) {			var ddrow = new Ext.dd.DropTarget(grid.container, {                ddGroup: 'userFourthTabGridDD',                copy:false,                notifyDrop: function(ddSource, e, data){					var sm = grid.getSelectionModel();  					var rows = sm.getSelections();  					var cindex = ddSource.getDragData(e).rowIndex;  					 if (sm.hasSelection()) {  						if(typeof(cindex) != "undefined") {							for (i = 0; i < rows.length; i++) {  								grid.store.remove(grid.store.getById(rows[i].id));  								grid.store.insert(cindex,rows[i]);  							}  						}						else { // when trying to add data to the end of the grid							var total_length = grid.store.data.length+1;							for (i = 0; i < rows.length; i++) {  								grid.store.remove(grid.store.getById(rows[i].id));							}							grid.store.add(rows);						}					} 					sm.clearSelections();				}		   }); 		});	},		renderAction: function (data, cell, record, rowIndex, columnIndex, store, grid) {		var id = record.data['id'];		cf.userFourthTab.createCheckbox.defer(500,this, [id,  record.data['isactive']]);		return '<center><table><tr><td><div id="theFourthGridCheckbox_'+ id +'"></div></td></tr></table></center>';	},		createCheckbox: function (id, check_value) {				var check = new Ext.form.Checkbox({			renderTo: 'theFourthGridCheckbox_' + id,			checked: check_value,			handler: function (checkbox) {				cf.userFourthTab.theFourthGrid.store.findExact('id', id ).data.isactive = checkbox.checked;	            }		});	},		initFourthPanel: function () {		this.thePanel = new Ext.Panel({			modal: true,			closable: false,			modal: true,			width: 650,			height: 680,			autoScroll: true,			title: '<?php echo __('GUI Settings',null,'usermanagement'); ?>',			shadow: false,			minimizable: false,			draggable: false,			resizable: false,	        plain: false		});	},		initGuiFieldset: function () {		this.theGuiFieldset = new Ext.form.FieldSet({			title: '<?php echo __('GUI Settings',null,'usermanagement'); ?>',			width: 600,			height: 220,			style: 'margin-top:20px;margin-left:5px;',			labelWidth: 330,			items:[{				xtype: 'combo', // number of records to display in grid				id: 'userFourthTab_itemsperpage_id',				mode: 'local',				value: '25',				fieldLabel: '<?php echo __('Items per page',null,'usermanagement'); ?>',				editable:false,				allowBlank: true,				autoHeight:true,				hiddenName: 'userFourthTab_itemsperpage',				triggerAction: 'all',				foreSelection: true,				store: new Ext.data.SimpleStore({					 fields:['id','text'],       				 data:[[25, '25'],[50, '50'],[75, '75'],[100, '100']]   				}), 				valueField:'id',				displayField:'text',				width:50			},{				xtype: 'combo', // number of records to display in grid				id: 'userFourthTab_refreshtime_id',				mode: 'local',				value: '30',				fieldLabel: '<?php echo __('Refreshtime in seconds',null,'usermanagement'); ?>',				editable:false,				allowBlank: true,				autoHeight:true,				hiddenName: 'userFourthTab_refreshtime',				triggerAction: 'all',				foreSelection: true,				store: new Ext.data.SimpleStore({					 fields:['id','text'],       				 data:[[30, '30'],[60, '60'],[120, '120'],[180, '180'],[240, '240'], [300, '300']]   				}), 				valueField:'id',				displayField:'text',				width:50			},{				xtype: 'panel',				layout: 'column',				border: false,				layoutConfig: {					columns:3				},				labelWidth: 150,				fieldLabel: '<?php echo __('Circulations default sort',null,'usermanagement'); ?>',				width: 200,				items: [{					xtype: 'combo', // number of records to display in grid					id: 'userFourthTab_circulationdefaultsortcolumn_id',					name: 'type',					mode: 'local',					value: 'NAME',					editable:false,					allowBlank: true,					autoHeight:true,					hiddenName: 'userFourthTab_circulationdefaultsortcolumn',					triggerAction: 'all',					foreSelection: true,					store: new Ext.data.SimpleStore({						 fields:['id','text'],	       				 data:[['NAME', '<?php echo __('Name',null,'usermanagement'); ?>'],['STATION', '<?php echo __('Station',null,'usermanagement'); ?>'],['DAYS', '<?php echo __('Days',null,'usermanagement'); ?>'],['START', '<?php echo __('Start',null,'usermanagement'); ?>'],['SENDER', '<?php echo __('Sender',null,'usermanagement'); ?>'],['TOTALTIME', '<?php echo __('Total time',null,'usermanagement'); ?>'],['MAILINGLIST', '<?php echo __('Mailing list',null,'usermanagement'); ?>'],['TEMPLATE', '<?php echo __('Template',null,'usermanagement'); ?>']]	   				}),	 				valueField:'id',					displayField:'text',					width:85								},{    				xtype: 'panel',    				html : '&nbsp;',    				border: false,					id: 'userFourthTab_spacepanel1'    			},{					xtype: 'combo', // number of records to display in grid					id: 'userFourthTab_circulationdefaultsortdirection_id',					name: 'type',					mode: 'local',					value: 'ASC',					editable:false,					allowBlank: true,					autoHeight:true,					hiddenName: 'userFourthTab_circulationdefaultsortdirection',					triggerAction: 'all',					foreSelection: true,					store: new Ext.data.SimpleStore({						 fields:['id','text'],	       				 data:[['ASC', '<?php echo __('Ascending',null,'usermanagement'); ?>'],['DESC', '<?php echo __('Descending',null,'usermanagement'); ?>']]	   				}),	 				valueField:'id',					displayField:'text',					width:100				}]			},{				xtype: 'checkbox',				fieldLabel: '<?php echo __('Show Circulationdetails in PopUp?',null,'usermanagement'); ?>',				inputValue: "1",				id: 'userFourthTab_showinpopup'						},{				xtype:'textfield',				fieldLabel: '<?php echo __('Change yellow after...days',null,'usermanagement'); ?>',				width:40,				value: '7',				id:'userFourthTab_markyellow'			},{				xtype:'textfield',				fieldLabel: '<?php echo __('Change orange after...days',null,'usermanagement'); ?>',				width:40,				value: '10',				id:'userFourthTab_markorange'			},{				xtype:'textfield',				fieldLabel: '<?php echo __('Change red after...days',null,'usermanagement'); ?>',				width:40,				value: '12',				id:'userFourthTab_markred'			}]		});				if (Ext.isIE6 == true) {			Ext.getCmp('userFourthTab_spacepanel1').html = '';			Ext.getCmp('userFourthTab_spacepanel1').setSize({width:5,height:0});		}		else if(Ext.isOpera == true || Ext.isSafari == true) {		}		else if (Ext.isIE7 == true) {			Ext.getCmp('userFourthTab_circulationdefaultsortdirection_id').style = 'margin-top:0px;margin-bottom:1px;';			Ext.getCmp('userFourthTab_circulationdefaultsortcolumn_id').style = 'margin-top:0px;margin-bottom:1px;';			Ext.getCmp('userFourthTab_itemsperpage_id').style = 'margin-top:0px;margin-bottom:1px;';			Ext.getCmp('userFourthTab_refreshtime_id').style = 'margin-top:0px;margin-bottom:1px;';		}	},		initWorkflowFieldset: function () {		this.theWorkflowFieldset = new Ext.form.FieldSet({			title: '<?php echo __('Worklfow Circulation settings',null,'usermanagement'); ?>',			width: 600,			height: 400,			style: 'margin-top:20px;margin-left:5px;',			labelWidth: 330		});	}};}();