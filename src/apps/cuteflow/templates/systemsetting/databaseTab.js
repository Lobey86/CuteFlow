cf.databaseTab = function(){return {
	
	theDatabaseTab				:false,
	theProdFieldset				:false,
	theDevFieldset				:false,
	theCheckbox					:false,
	
	
	init: function () {
		this.initProd();
		this.initDev();
		this.initDatabaseTab();
		this.theDatabaseTab.add(this.theCheckbox);
		this.theDatabaseTab.add(this.theProdFieldset);
		this.theDatabaseTab.add(this.theDevFieldset);
	},
	
	initDatabaseTab: function () {
		this.theDatabaseTab = new Ext.Panel({
			modal: true,
			closable: false,
			modal: true,
			width: 650,
			height: 500,
			autoScroll: true,
			title: '<?php echo __('Database',null,'systemsetting'); ?>',
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: false
		});
	},
	
	initProd: function () {
		this.theProdFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Productive Database',null,'systemsetting'); ?>',
			width: 600,
			height: 200,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 330,
			items:[{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				id: 'productive_checkbox',
				fieldLabel: '<?php echo __('Edit productive Database',null,'systemsetting'); ?>',
				handler: function (check) {
					if(check.checked) {
						Ext.getCmp('productive_database_id').setDisabled(false);
						Ext.getCmp('productive_host').setDisabled(false);
						Ext.getCmp('productive_databasename').setDisabled(false);
						Ext.getCmp('productive_password').setDisabled(false);
						Ext.getCmp('productive_username').setDisabled(false);
					}
					else {
						Ext.getCmp('productive_database_id').setDisabled(true);
						Ext.getCmp('productive_host').setDisabled(true);
						Ext.getCmp('productive_databasename').setDisabled(true);
						Ext.getCmp('productive_password').setDisabled(true);
						Ext.getCmp('productive_username').setDisabled(true);
					}
				}
			},{
				xtype: 'combo',
				mode: 'local',
				editable:false,
 				valueField:'id',
 				value: 'mysql',
 				disabled: true,
 				id: 'productive_database_id',
 				hiddenName : 'productive_database',
				displayField:'text',
				triggerAction: 'all',
				foreSelection: true,
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[['mysql', '<?php echo __('MySQL',null,'systemsetting'); ?>'],['oracle', '<?php echo __('Oracle',null,'systemsetting'); ?>'],['sqlite', '<?php echo __('Sqlite',null,'systemsetting'); ?>'],['postgresql', '<?php echo __('PostgresQL',null,'systemsetting'); ?>']]
   				}),
   				fieldLabel: '<?php echo __('Select Database',null,'systemsetting'); ?>',
   				width:200
			},{
				xtype : 'textfield',
				fieldLabel: '<?php echo __('Host',null,'systemsetting'); ?>',
				id: 'productive_host',
				disabled: true,
				width:200
			},{
				xtype : 'textfield',
				fieldLabel: '<?php echo __('Database',null,'systemsetting'); ?>',
				id: 'productive_databasename',
				disabled: true,
				width:200
			},{
				xtype : 'textfield',
				fieldLabel: '<?php echo __('Password',null,'systemsetting'); ?>',
				id: 'productive_password',
				disabled: true,
				width:200
			},{
				xtype : 'textfield',
				fieldLabel: '<?php echo __('Username',null,'systemsetting'); ?>',
				id: 'productive_username',
				disabled: true,
				width:200
			}]
		});
		
	},
	
	initDev: function () {
		this.theDevFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Development Database',null,'systemsetting'); ?>',
			width: 600,
			labelWidth: 330,
			height: 200,
			style: 'margin-top:20px;margin-left:5px;',
			items:[{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				id: 'development_checkbox',
				fieldLabel: '<?php echo __('Edit development Database',null,'systemsetting'); ?>',
				handler: function (check) {
					if(check.checked) {
						Ext.getCmp('development_database_id').setDisabled(false);
						Ext.getCmp('development_host').setDisabled(false);
						Ext.getCmp('development_databasename').setDisabled(false);
						Ext.getCmp('development_password').setDisabled(false);
						Ext.getCmp('development_username').setDisabled(false);
					}
					else {
						Ext.getCmp('development_database_id').setDisabled(true);
						Ext.getCmp('development_host').setDisabled(true);
						Ext.getCmp('development_databasename').setDisabled(true);
						Ext.getCmp('development_password').setDisabled(true);
						Ext.getCmp('development_username').setDisabled(true);
					}
				}
				
			},{
				xtype: 'combo',
				mode: 'local',
				editable:false,
 				valueField:'id',
 				value: 'mysql',
 				id: 'development_database_id',
 				hiddenName : 'development_database',
				displayField:'text',
				triggerAction: 'all',
				disabled: true,
				foreSelection: true,
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[['mysql', '<?php echo __('MySQL',null,'systemsetting'); ?>'],['oracle', '<?php echo __('Oracle',null,'systemsetting'); ?>'],['sqlite', '<?php echo __('Sqlite',null,'systemsetting'); ?>'],['postgresql', '<?php echo __('PostgresQL',null,'systemsetting'); ?>']]
   				}),
   				fieldLabel: '<?php echo __('Select Database',null,'systemsetting'); ?>',
   				width:200
			},{
				xtype : 'textfield',
				fieldLabel: '<?php echo __('Host',null,'systemsetting'); ?>',
				id: 'development_host',
				disabled: true,
				width:200
			},{
				xtype : 'textfield',
				fieldLabel: '<?php echo __('Database',null,'systemsetting'); ?>',
				id: 'development_databasename',
				disabled: true,
				width:200
			},{
				xtype : 'textfield',
				fieldLabel: '<?php echo __('Password',null,'systemsetting'); ?>',
				id: 'development_password',
				disabled: true,
				width:200
			},{
				xtype : 'textfield',
				fieldLabel: '<?php echo __('Username',null,'systemsetting'); ?>',
				id: 'development_username',
				disabled: true,
				width:200
			}]
		});
		
	}
	
	
};}();