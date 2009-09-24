cf.userTab = function(){return {
	
	
	theUserTab					:false,
	theUserSystemFieldset		:false,
	theUserGuiFieldset			:false,
	theComboRoleStore			:false,
	
	
	/** load all nedded functions **/
	init: function () {
		this.initRoleStore();
		this.initUserTab();
		this.initDefaultUserSystemFieldset();
		this.initDefaultUserGuiFieldset();
		this.theUserTab.add(this.theUserSystemFieldset);
		this.theUserTab.add(this.theUserGuiFieldset);
		
	},
	
	/** init the tab **/
	initUserTab: function () {
		this.theUserTab = new Ext.Panel({
			modal: true,
			closable: false,
			modal: true,
			width: 650,
			height: 600,
			autoScroll: false,
			title: '<?php echo __('User Settings',null,'systemsetting'); ?>',
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: false
		});
	},
	
	initDefaultUserGuiFieldset: function () {
		this.theUserGuiFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Default user GUI settings',null,'systemsetting'); ?>',
			width: 600,
			height: 190,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 330,
			items:[{
				xtype: 'combo', // number of records to display in grid
				id: 'userTab_itemsperpage_id',
				mode: 'local',
				value: '25',
				fieldLabel: '<?php echo __('Items per page',null,'systemsetting'); ?>',
				editable:false,
				allowBlank: true,
				autoHeight:true,
				hiddenName: 'userTab_itemsperpage',
				triggerAction: 'all',
				foreSelection: true,
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[[25, '25'],[50, '50'],[75, '75'],[100, '100']]
   				}),
 				valueField:'id',
				displayField:'text',
				width:50
			},{
				xtype: 'combo', // number of records to display in grid
				id: 'userTab_refreshtime_id',
				mode: 'local',
				value: '30',
				fieldLabel: '<?php echo __('Refreshtime in seconds',null,'systemsetting'); ?>',
				editable:false,
				allowBlank: true,
				autoHeight:true,
				hiddenName: 'userTab_refreshtime',
				triggerAction: 'all',
				foreSelection: true,
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[[30, '30'],[60, '60'],[120, '120'],[180, '180'],[240, '240'], [300, '300']]
   				}),
 				valueField:'id',
				displayField:'text',
				width:50
			},{
				xtype: 'panel',
				layout: 'column',
				border: false,
				layoutConfig: {
					columns: 3
				},
				labelWidth: 150,
				fieldLabel: '<?php echo __('Circulations default sort',null,'systemsetting'); ?>',
				width: 300,
				items: [{
					xtype: 'combo', // number of records to display in grid
					id: 'userTab_circulationdefaultsortcolumn_id',
					name: 'type',
					mode: 'local',
					value: 'NAME',
					editable:false,
					allowBlank: true,
					autoHeight:true,
					hiddenName: 'userTab_circulationdefaultsortcolumn',
					triggerAction: 'all',
					foreSelection: true,
					store: new Ext.data.SimpleStore({
						 fields:['id','text'],
	       				 data:[['NAME', '<?php echo __('Name',null,'systemsetting'); ?>'],['STATION', '<?php echo __('Station',null,'systemsetting'); ?>'],['DAYS', '<?php echo __('Days',null,'systemsetting'); ?>'],['START', '<?php echo __('Start',null,'systemsetting'); ?>'],['SENDER', '<?php echo __('Sender',null,'systemsetting'); ?>'],['TOTALTIME', '<?php echo __('Total time',null,'systemsetting'); ?>'],['MAILINGLIST', '<?php echo __('Mailing list',null,'systemsetting'); ?>'],['TEMPLATE', '<?php echo __('Template',null,'systemsetting'); ?>']]
	   				}),
	 				valueField:'id',
					displayField:'text',
					width:100				
				},{
					xtype: 'combo', // number of records to display in grid
					id: 'userTab_circulationdefaultsortdirection_id',
					name: 'type',
					mode: 'local',
					value: 'ASC',
					editable:false,
					allowBlank: true,
					autoHeight:true,
					hiddenName: 'userTab_circulationdefaultsortdirection',
					triggerAction: 'all',
					foreSelection: true,
					store: new Ext.data.SimpleStore({
						 fields:['id','text'],
	       				 data:[['ASC', '<?php echo __('Ascending',null,'systemsetting'); ?>'],['DESC', '<?php echo __('Descending',null,'systemsetting'); ?>']]
	   				}),
	 				valueField:'id',
					displayField:'text',
					width:100
				}]
			},{
				xtype:'textfield',
				fieldLabel: '<?php echo __('Change yellow after...days',null,'systemsetting'); ?>',
				width:40,
				value: '7',
				id:'userTab_markyellow'
			},{
				xtype:'textfield',
				fieldLabel: '<?php echo __('Change orange after...days',null,'systemsetting'); ?>',
				width:40,
				value: '10',
				id:'userTab_markorange'
			},{
				xtype:'textfield',
				fieldLabel: '<?php echo __('Change red after...days',null,'systemsetting'); ?>',
				width:40,
				value: '12',
				id:'userTab_markred'
			}]
		});
	},
	
	initDefaultUserSystemFieldset: function () {
		this.theUserSystemFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Default user system settings',null,'systemsetting'); ?>',
			width: 600,
			height: 160,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 330,
			items:[{
				xtype: 'textfield',
				fieldLabel: '<?php echo __('Default Password',null,'systemsetting'); ?>',
				id: 'userTab_defaultpassword',
				width:200
			},{
				xtype: 'panel',
				layout: 'column',
				border: false,
				layoutConfig: {
					columns: 2,
					fitHeight: true,
					split: true
				},
				labelWidth: 150,
				fieldLabel: '<?php echo __('Useragent time',null,'systemsetting'); ?>',
				width: 200,
				items: [{
					xtype: 'textfield',
					id: 'userTab_defaultdurationlength',
					style: 'margin-right:5px;',
					value: 1,
					allowBlank: false,
					height: 22,
					width:40				
    			},{
					xtype: 'combo', // number of records to display in grid
					id: 'userTab_defaultdurationtype_id',
					name: 'type',
					mode: 'local',
					value: 'DAYS',
					editable:false,
					allowBlank: true,
					autoHeight:true,
					hiddenName: 'userTab_defaultdurationtype',
					triggerAction: 'all',
					foreSelection: true,
					store: new Ext.data.SimpleStore({
						 fields:['id','text'],
	       				 data:[['DAYS', '<?php echo __('Day(s)',null,'systemsetting'); ?>'],['HOURS', '<?php echo __('Hour(s)',null,'systemsetting'); ?>'],['MINUTES', '<?php echo __('Minute(s)',null,'systemsetting'); ?>']]
	   				}),
	 				valueField:'id',
					displayField:'text',
					width:159
					}]
				},{
				xtype: 'panel',
				layout: 'column',
				border: false,
				layoutConfig: {
					columns: 2,
					fitHeight: true,
					split: true
				},
				labelWidth: 150,
				fieldLabel: '<?php echo __('Email format',null,'systemsetting'); ?>',
				width: 200,
				items: [{
					xtype: 'combo',
					id: 'userTab_emailformat_id',
					mode: 'local',
					//style: 'margin-right:5px;',
					value: 'plain',
					editable:false,
					allowBlank: true,
					autoHeight:true,
					hiddenName: 'userTab_emailformat',
					triggerAction: 'all',
					foreSelection: true,
					store: new Ext.data.SimpleStore({
						 fields:['id','text'],
	       				 data:[['plain', '<?php echo __('Plain',null,'systemsetting'); ?>'],['html', '<?php echo __('HTML',null,'systemsetting'); ?>']]
	   				}),
	 				valueField:'id',
					displayField:'text',
					width:60				
    			},{
					xtype: 'combo', // number of records to display in grid
					id: 'userTab_emailtype_id',
					mode: 'local',
					value: 'NONE',
					editable:false,
					allowBlank: true,
					autoHeight:true,
					hiddenName: 'userTab_emailtype',
					triggerAction: 'all',
					foreSelection: true,
					store: new Ext.data.SimpleStore({
						 fields:['id','text'],
	       				 data:[['NONE', '<?php echo __('None',null,'systemsetting'); ?>'],['VALUES', '<?php echo __('Only values',null,'systemsetting'); ?>'],['IFRAME', '<?php echo __('IFrame',null,'systemsetting'); ?>']]
	   				}),
	 				valueField:'id',
					displayField:'text',
					width:111
					}]
				},{
					xtype: 'combo',
					fieldLabel : '<?php echo __('Userrole',null,'systemsetting'); ?>',
					id: 'userTab_userrole_id',
					valueField: 'id',
					mode: 'local',
					hiddenName : 'userTab_userrole',
					displayField: 'description',
					store: this.theComboRoleStore,
					editable: false,
					typeAhead: false,
					allowBlank: true,
					triggerAction: 'all',
					foreSelection: true,
					width: 200
				}]
		});
		if (Ext.isIE6 == true) {
			Ext.getCmp('userTab_defaultdurationlength').setSize({width:40, height: 25});
		}
		else if(Ext.isOpera == true || Ext.isSafari == true) {
			Ext.getCmp('userTab_defaultdurationlength').setSize({width:40, height: 24});
		}
		
	},
	
	initRoleStore: function () {
		this.theComboRoleStore = new Ext.data.JsonStore({
				root: 'result',
				url: '<?php echo build_dynamic_javascript_url('usermanagement/LoadAllRole')?>',
				autoload: true,
				fields: [
					{name: 'id'},
					{name: 'description'}
				]
		});
		cf.userTab.theComboRoleStore.load();
	},
	
	addData: function (data) {
		Ext.getCmp('userTab_defaultpassword').setValue(data.password);
		Ext.getCmp('userTab_defaultdurationlength').setValue(data.durationlength);
		Ext.getCmp('userTab_defaultdurationtype_id').setValue(data.durationtype);
		Ext.getCmp('userTab_emailformat_id').setValue(data.emailformat);
		Ext.getCmp('userTab_emailtype_id').setValue(data.emailtype);
		Ext.getCmp('userTab_userrole_id').setValue(data.role_id);
		
		Ext.getCmp('userTab_itemsperpage_id').setValue(data.displayeditem);
		Ext.getCmp('userTab_refreshtime_id').setValue(data.refreshtime);
		Ext.getCmp('userTab_circulationdefaultsortcolumn_id').setValue(data.circulationdefaultsortcolumn);
		Ext.getCmp('userTab_circulationdefaultsortdirection_id').setValue(data.circulationdefaultsortdirection);
		Ext.getCmp('userTab_markyellow').setValue(data.markyellow);
		Ext.getCmp('userTab_markorange').setValue(data.markorange);
		Ext.getCmp('userTab_markred').setValue(data.markred);
		
		
		
		
	}
	
	
	
	
	
	
};}();