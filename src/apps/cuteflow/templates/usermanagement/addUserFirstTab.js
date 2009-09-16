/**
* Class adds the first Tab when editing and creating new user
*
*/
cf.AddUserFirstTab = function(){return {
	
	theFirstPanel					: false,
	theComboRoleStore				: false,
	
	
	
	/** calls all necessary functions **/
	init: function() {
		this.initRoleStore();
		this.initPanel();
	},
	
	/** builds panel and its elements **/
	initPanel: function () {
		this.theFirstPanel = new Ext.Panel({
			title: '<?php echo __('Userdata',null,'usermanagementpopup'); ?>',
			frame: true,
			enableTabScroll:true,
			plain: true,
			layout: 'form',
			autoScroll: true,
			labelWidth : 200,
			width: 'auto',
			height: 529,
			items:[{
				xtype: 'hidden',
				id: 'hiddenfield',
				name: 'hiddenfield'
			},{
				xtype: 'fieldset',
				defaults:{height:22},
				title: '<?php echo __('Personel data',null,'usermanagementpopup'); ?>',
				items:[{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Firstname',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'firstname',
					id: 'firstname',
					allowBlank: false,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Lastname',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'lastname',
					id: 'lastname',
					allowBlank: false,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Email',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'email',
					id: 'email',
					allowBlank: false,
					width: 200
				}]
			},{
				xtype: 'fieldset',
				title: '<?php echo __('Login data',null,'usermanagementpopup'); ?>',
				defaults:{height:22},
				items:[{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Username',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					name: 'username',
					labelWidth: 150,
					id: 'username',
					allowBlank: false,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Password',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					inputType: 'password',
					name: 'password',
					id: 'password',
					allowBlank: false,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Password (again)',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					inputType: 'password',
					name: 'passwordAgain',
					id: 'passwordAgain',
					allowBlank: false,
					width: 200
				},{
					xtype: 'combo',
					fieldLabel : '<?php echo __('Userrole',null,'usermanagementpopup'); ?>',
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
				title: '<?php echo __('Useragent time',null,'usermanagementpopup'); ?>',
				items:[{
					xtype: 'panel',
					layout: 'column',
					border: 'none',
					layoutConfig: {
						columns: 2,
						fitHeight: true,
						split: true
					},
					labelWidth: 150,
					fieldLabel: '<?php echo __('Useragent time',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					width: 200,
					items: [{
						xtype: 'textfield',
						name: 'durationlength',
						id: 'durationlength',
						style: 'margin-right:5px;',
						value: 1,
						allowBlank: false,
						height: 22,
						width:40				
        			},{
						xtype: 'combo', // number of records to display in grid
						id: 'type',
						name: 'type',
						mode: 'local',
						editable:false,
						allowBlank: true,
						autoHeight:true,
						hiddenName: 'durationtype',
						triggerAction: 'all',
						foreSelection: true,
						store: new Ext.data.SimpleStore({
							 fields:['id','text'],
		       				 data:[['DAYS', '<?php echo __('Day(s)',null,'usermanagementpopup'); ?>'],['HOURS', '<?php echo __('Hour(s)',null,'usermanagementpopup'); ?>'],['MINUTES', '<?php echo __('Minute(s)',null,'usermanagementpopup'); ?>']]
		   				}),
		 				valueField:'id',
						displayField:'text',
						width:100
					}]
				}]
			}]
		});
		// Problem with layout in all browsers :(
		if (Ext.isIE6 == true) {
			Ext.getCmp('durationlength').setSize({width:40, height: 25});
		}
		else if(Ext.isOpera == true || Ext.isSafari == true) {
			Ext.getCmp('durationlength').setSize({width:40, height: 24});
		}
		Ext.getCmp('type').setValue('DAYS');
	},
	

	
	/** store for roles **/
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
		cf.AddUserFirstTab.theComboRoleStore.load();
	}
	
	
};}();