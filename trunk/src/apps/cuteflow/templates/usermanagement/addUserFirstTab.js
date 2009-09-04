/**
* Class adds the first Tab when editing and creating new user
*
*/
cf.AddUserFirstTab = function(){return {
	
	theFirstPanel					: false,
	theComboUserStore				: false,
	theComboRoleStore				: false,
	
	
	
	/** calls all necessary functions **/
	init: function() {
		this.initRoleStore();
		this.initUserStore();
		this.initPanel();
	},
	
	/** builds panel and its elements **/
	initPanel: function () {
		this.theFirstPanel = new Ext.Panel({
			title: 'Benutzerdaten',
			frame: true,
			enableTabScroll:true,
			plain: true,
			layout: 'form',
			autoScroll: true,
			labelWidth : 200,
			width: 500,
			height: 600,
			items:[{
				xtype: 'hidden',
				id: 'hiddenfield',
				name: 'hiddenfield'
			},{
				xtype: 'fieldset',
				title: 'pers&ouml;nliche Daten',
				items:[{
					xtype: 'textfield',
					fieldLabel: 'Vorname',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'firstname',
					id: 'firstname',
					allowBlank: false,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: 'Nachname',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'lastname',
					id: 'lastname',
					allowBlank: false,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: 'E-Mail',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'email',
					id: 'email',
					allowBlank: false,
					width: 200
				}]
			},{
				xtype: 'fieldset',
				title: 'Anmeldedaten',
				items:[{
					xtype: 'textfield',
					fieldLabel: 'Benutzername',
					labelStyle: 'font-weight:bolder;',
					name: 'username',
					labelWidth: 150,
					id: 'username',
					allowBlank: false,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: 'Passwort',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					inputType: 'password',
					name: 'password',
					id: 'password',
					allowBlank: false,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: 'Passwort&nbsp;(Wdh)',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					inputType: 'password',
					name: 'passwordAgain',
					id: 'passwordAgain',
					allowBlank: false,
					width: 200
				},{
					xtype: 'combo',
					fieldLabel : 'Benutzerrolle',
					id: 'userrole',
					name: 'userrole',
					labelStyle: 'font-weight:bolder;',
					valueField: 'id',
					mode: 'local',
					hiddenName : 'roleid',
					displayField: 'description',
					store: this.theComboRoleStore,
					editable: false,
					typeAhead: false,
					allowBlank: false,
					triggerAction: 'all',
					width: 200
				}]
			},{
				xtype: 'fieldset',
				title: 'Stellvertreter',
				items:[{
				    xtype: 'superboxselect',
				    fieldLabel: 'Stellvertreter',
				    name: 'agent[]',
				    id: 'agent',
				    width: 200,
				    labelWidth: 150,
				    hideOnSelect: false,
				    maxHeight: 500,
				    store: this.theComboUserStore,
				    labelStyle: 'font-weight:bolder;',
				    triggerAction: 'all',
				    valueField: 'id',
				    displayField: 'text',
				    mode: 'local'
				},{
					xtype: 'panel',
					layout: 'column',
					border: 'none',
					labelWidth: 150,
					fieldLabel: 'Stellvertreterzeit',
					labelStyle: 'font-weight:bolder;',
					width: 200,
					items: [{
						xtype: 'textfield',
						name: 'durationlength',
						id: 'durationlength',
						style: 'margin-right:5px;',
						value: 1,
						allowBlank: true,
						width: 35
        			},{
						xtype: 'combo', // number of records to display in grid
						id: 'type',
						name: 'type',
						mode: 'local',
						//value: 'Tage(n)',
						editable:false,
						allowBlank: true,
						hiddenName: 'durationtype',
						triggerAction: 'all',
						foreSelection: true,
						store: new Ext.data.SimpleStore({
							 fields:['id','text'],
		       				 data:[['DAYS', 'Tage(n)'],['HOURS', 'Stunde(n)'],['MINUTES', 'Minute(n)']]
		   				}),
		 				valueField:'id',
						displayField:'text',
						width:165
					}]
				}]
			}]
		});
		Ext.getCmp('type').setValue('DAYS');
		
	},
	
	
	/** Store for superselectbox **/
	initUserStore: function () {
		this.theComboUserStore = new Ext.data.JsonStore({
			root: 'result',
			url: '<?php echo url_for('usermanagement/LoadSuperComboboxUser')?>',
			autoload: true,
			fields: [
				{name: 'id'},
				{name: 'text'}
			]
		});
		cf.AddUserFirstTab.theComboUserStore.load();
	},
	
	/** store for roles **/
	initRoleStore: function () {
		this.theComboRoleStore = new Ext.data.JsonStore({
				root: 'result',
				url: '<?php echo url_for('usermanagement/LoadAllRole')?>',
				autoload: true,
				fields: [
					{name: 'id'},
					{name: 'description'}
				]
		});
		cf.AddUserFirstTab.theComboRoleStore.load();
	}
	
	
};}();