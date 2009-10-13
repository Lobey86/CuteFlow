/** build useragent tab **/cf.userSecondTab = function(){return {	theUserAgentSettingsFieldset		:false,	thePanel							:false,	theUserAgenGridFieldset				:false,		theUserGrid							:false,	theUserCM							:false,	theUserStore						:false,	theUserToolbar						:false,		theUserAgentGrid					:false,	theUserAgentCM						:false,	theUserAgentStore					:false,	theUserAgentToolbar					:false,			theUniqueId							:0,				/** init functions **/	init: function (id) {		this.initUserCM();		this.initUserStore();		this.initUserToolbar();		this.initUserGrid();				this.initUserAgentCM();		this.initUserAgentStore(id);		this.initUserAgentToolbar();		this.initUserAgentGrid();				this.initSecondPanel();		this.initUserAgentFieldset();		this.userAgentGridFieldset();						this.theUserAgenGridFieldset.add(this.theUserGrid);		this.theUserAgenGridFieldset.add(this.theUserAgentGrid);		this.thePanel.add(this.theUserAgentSettingsFieldset);				this.thePanel.add(this.theUserAgenGridFieldset);			},		/** init the paenel **/	initSecondPanel: function () {		this.thePanel = new Ext.Panel ({			modal: true,			closable: false,			modal: true,			width: 650,			height: cf.Layout.theRegionWest.getHeight() - 230,			autoScroll: true,			title: '<?php echo __('Useragent Settings',null,'usermanagement'); ?>',			shadow: false,			minimizable: false,			draggable: false,			resizable: false,	        plain: false		});	},	/** init the useragent fieldset **/		initUserAgentFieldset: function () {		this.theUserAgentSettingsFieldset = new Ext.form.FieldSet({			title: '<?php echo __('Useragent Settings',null,'usermanagement'); ?>',			width: 630,			height: 65,			labelWidth: 180,			style: 'margin-top:20px;margin-left:5px;',			items:[{				xtype: 'panel',				layout: 'column',				border: false,				layoutConfig: {					columns: 2				},				labelWidth: 150,				fieldLabel: '<?php echo __('Useragent time',null,'usermanagement'); ?>',				width: 200,				items: [{					xtype: 'textfield',					id: 'userSecondTab_durationlength',					style: 'margin-right:5px;',					value: 1,					allowBlank: false,					height: 22,					width:40								},{					xtype: 'combo', // number of records to display in grid					id: 'userSecondTab_durationlength_type_id',					mode: 'local',					editable:false,					allowBlank: true,					autoHeight:true,					value: 'DAYS',					hiddenName: 'userSecondTab_durationlength_type',					triggerAction: 'all',					foreSelection: true,					store: new Ext.data.SimpleStore({						 fields:['id','text'],						 data:[['DAYS', '<?php echo __('Day(s)',null,'usermanagement'); ?>'],['HOURS', '<?php echo __('Hour(s)',null,'usermanagement'); ?>'],['MINUTES', '<?php echo __('Minute(s)',null,'usermanagement'); ?>']]					}),					valueField:'id',					displayField:'text',					width:100				}]			}]		});		if (Ext.isIE6 == true) {			Ext.getCmp('userSecondTab_durationlength').setSize({width:40, height: 24});			Ext.getCmp('userSecondTab_durationlength').style = 'margin-top:0px;margin-bottom:1px;margin-right:5px;';		}		else if(Ext.isOpera == true || Ext.isSafari == true) {			Ext.getCmp('userSecondTab_durationlength').setSize({width:40, height: 24});		}				else if (Ext.isIE7 == true) {			Ext.getCmp('userSecondTab_durationlength').setSize({width:40, height: 24});			Ext.getCmp('userSecondTab_durationlength').style = 'margin-top:0px;margin-bottom:1px;margin-right:5px;';						Ext.getCmp('userSecondTab_durationlength_type_id').style = 'margin-top:0px;margin-bottom:1px;';		}	},		/** fieldset for useragent grids **/	userAgentGridFieldset: function () {		this.theUserAgenGridFieldset = new Ext.form.FieldSet({			title: '<?php echo __('Set Useragents',null,'usermanagement'); ?>',			width: 630,			layout: 'column',			height: 535,			style: 'margin-top:20px;margin-left:5px;'		});	},		/** left usergrid **/	initUserGrid: function () {		this.theUserGrid = new Ext.grid.GridPanel({			frame:false,			autoScroll: true,			collapsible:false,			closable: false,            ddText: '<?php echo __('Move right please',null,'usermanagement'); ?>',  			title: '<table><tr><td><img src="/images/icons/user_suit.png" /></td><td><?php echo __('User',null,'usermanagement'); ?></td></tr></table>',			height: 490,			width:290,			border: true,			style: 'margin-right:20px;',			ddGroup : 'rightGridDDGroup',			plain: false,			enableDragDrop:true,			expand: true,			store: this.theUserStore,			tbar: this.theUserToolbar,			cm: this.theUserCM		});	},		/** store for left grid **/	initUserStore: function () {		this.theUserStore = new Ext.data.JsonStore({			root: 'result',			url: '<?php echo build_dynamic_javascript_url('usermanagement/LoadUserGrid')?>',			fields: [				{name: 'id'},				{name: 'text'}			]		});			cf.userSecondTab.theUserStore.load();	},		/** CM for left grid **/	initUserCM: function () {		this.theUserCM = new Ext.grid.ColumnModel([			{header: "<?php echo __('Name',null,'usermanagement'); ?>", width: 255, sortable: true, dataIndex: 'text', css : "text-align : left;font-size:12px;align:center;height:26px;"}		]);		},		/** toolbar for left grid **/	initUserToolbar: function () {		this.theUserToolbar = new Ext.Toolbar({			items: [{				xtype: 'textfield',				id: 'userSecondTab_UserLivesearch',				emptyText:'<?php echo __('Search for User...',null,'usermanagement'); ?>',				width: 200,				enableKeyEvents: true,				listeners: {					keyup: function(el, type) {						var grid = cf.userSecondTab.theUserGrid;						grid.store.filter('text', el.getValue());					}				}			},'-',{				icon: '/images/icons/delete.png',				tooltip: '<?php echo __('Clear field',null,'usermanagement'); ?>',				handler: function () {					Ext.getCmp('userSecondTab_UserLivesearch').setValue();					cf.userSecondTab.theUserGrid.store.filter('text', '');                }			}]		});	},		/** CM for right grid **/	initUserAgentCM: function () {		this.theUserAgentCM = new Ext.grid.ColumnModel([			{header: "<?php echo __('Name',null,'usermanagement'); ?>", width: 200, sortable: true, dataIndex: 'text', css : "text-align : left;font-size:12px;align:center;"},			{header: "<div ext:qtip=\"<table><tr><td><img src='/images/icons/user_delete.png' />&nbsp;&nbsp;</td><td><?php echo __('Remove user',null,'usermanagement'); ?></td></tr></table>\" ext:qwidth=\"200\"><?php echo __('Action',null,'usermanagement'); ?></div>", width: 60, sortable: true, dataIndex: 'unique_id', css : "text-align : left;font-size:12px;align:center;", renderer:cf.userSecondTab.deleteUseragentButton}		]);	},		/** store for right grid **/	initUserAgentStore: function (id) {		this.theUserAgentStore = new Ext.data.JsonStore({			root: 'result',			url: '<?php echo build_dynamic_javascript_url('usermanagement/LoadUserAgentGrid')?>/id/'+id,			fields: [				{name: 'unique_id'},				{name: 'user_id'},				{name: 'text'}			]		});		},		/** toolbar for right grid **/	initUserAgentToolbar: function () {		this.theUserAgentToolbar = new Ext.Toolbar({			items: [{				xtype: 'textfield',				id: 'userSecondTab_UserAgentLivesearch',				emptyText:'<?php echo __('Search for Useragent...',null,'usermanagement'); ?>',				width: 200,				enableKeyEvents: true,				listeners: {					keyup: function(el, type) {						var grid = cf.userSecondTab.theUserAgentGrid;						grid.store.filter('text', el.getValue());					}				}		    },'-',{				icon: '/images/icons/delete.png',				tooltip: '<?php echo __('Clear field',null,'usermanagement'); ?>',				handler: function () {					Ext.getCmp('userSecondTab_UserAgentLivesearch').setValue();					cf.userSecondTab.theUserAgentGrid.store.filter('text', '');                }			}]		});	},		/** right grid **/	initUserAgentGrid: function () {		this.theUserAgentGrid = new Ext.grid.GridPanel({			frame:false,			autoScroll: true,			collapsible:false,			closable: false,			ddGroup : 'rightGridDDGroup',			allowContainerDrop : true,            ddText: '<?php echo __('Drag Drop to change order',null,'usermanagement'); ?>', 			title: '<table><tr><td><img src="/images/icons/user_gray.png" /></td><td><?php echo __('Useragent',null,'usermanagement'); ?></td></tr></table>',			height:'false',			width:290,			height: 490,			border: true,			plain: false,			autoHeight : false,			stripRows: true,			enableDragDrop:true,			store: this.theUserAgentStore,			tbar: this.theUserAgentToolbar,			cm: this.theUserAgentCM		});				this.theUserAgentGrid.on('render', function(grid) {			var secondGridDropTargetEl = grid.getView().scroller.dom;			var secondGridDropTarget = new Ext.dd.DropTarget(secondGridDropTargetEl, {				ddGroup: 'rightGridDDGroup',				copy: false,				notifyDrop: function(ddSource, e, data){ // when droppping a container in the right grid					if (ddSource.grid != grid){						for(var a=0;a<data.selections.length;a++) { // if data is from left grid, add it to store. 							var item = data.selections[a].data;							var Rec = Ext.data.Record.create({name: 'unique_id'},{name: 'user_id'}, {name: 'text'});							grid.store.add(new Rec({unique_id: cf.userSecondTab.theUniqueId++, user_id: item.id,text: item.text})); // important to add unique ID's						}					}					else { // if data is coming from right, then reorder is done.						var sm = grid.getSelectionModel();  						var rows = sm.getSelections();  						var cindex = ddSource.getDragData(e).rowIndex;  						 if (sm.hasSelection()) {  							if(typeof(cindex) != "undefined") {								for (i = 0; i < rows.length; i++) {  									grid.store.remove(grid.store.getById(rows[i].id));  									grid.store.insert(cindex,rows[i]);  								}  							}							else { // when trying to add data to the end of the grid								var total_length = grid.store.data.length+1;								for (i = 0; i < rows.length; i++) {  									grid.store.remove(grid.store.getById(rows[i].id));								}								grid.store.add(rows);							}						} 						sm.clearSelections();					}					return true;				}			});		});	},				/** render Delete Button to right grid in each row **/	deleteUseragentButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {			cf.userSecondTab.theUniqueId++;			var id = record.data['unique_id'];			var btn = cf.userSecondTab.createDeleteButton.defer(1,this, [id]);			return '<center><table><tr><td><div id="remove_useragent'+ id +'"></div></td></tr></table></center>';	},		/**	* build the label and the button for the grid	*	*	*@param int id, id of the record	*/	createDeleteButton: function (id) {		var btn_edit = new Ext.form.Label({			renderTo: 'remove_useragent' + id,			html: '<span style="cursor:pointer;"><img src="/images/icons/user_delete.png" /></span>',			listeners: {				render: function(c){					  c.getEl().on({						click: function(el){							var item = cf.userSecondTab.theUserAgentGrid.store.findExact('unique_id', id );							cf.userSecondTab.theUserAgentGrid.store.remove(item);						},					scope: c				});				}			}		});	}};}();/** override findExact method, to return a dataset in a store **/Ext.override( Ext.data.Store, {    findExact: function( fld, val ) {        var hit = null;        this.each( function(rec) { if( rec.get(fld) == val ) { hit = rec; return false; }; } );        return hit;    }});