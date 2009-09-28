cf.userFirstTab = function(){return {


	thePanel					:false,
	theUserdataFieldset			:false,
	theUserlogindataFieldset	:false,
	theUserroleFieldset			:false,
	theEmailformatFieldset		:false,
	theComboRoleStore			:false,
	
	
	
	init: function () {
		this.initRoleStore();
		this.initFirstPanel();
		this.initUserdata();
		this.initUserlogindata();
		this.initUserrole();
		this.initEmailformat();
		
		
		this.thePanel.add(this.theUserdataFieldset);
		this.thePanel.add(this.theUserlogindataFieldset);
		this.thePanel.add(this.theUserroleFieldset);
		this.thePanel.add(this.theEmailformatFieldset);
		
	},
	
	initUserdata: function () {
		this.theUserdataFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Userdata',null,'usermanagement'); ?>',
			width: 500,
			height: 120,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 180,
			items: [{
				xtype: 'textfield',
				fieldLabel: '<?php echo __('Firstname',null,'usermanagement'); ?>',
				allowBlank: false,
				id: 'userFirstTab_firstname',
				width:200
			},{
				xtype: 'textfield',
				fieldLabel: '<?php echo __('Lastname',null,'usermanagement'); ?>',
				allowBlank: false,
				id: 'userFirstTab_lastname',
				width:200
			},{
				xtype: 'textfield',
				fieldLabel: '<?php echo __('Email',null,'usermanagement'); ?>',
				allowBlank: false,
				id: 'userFirstTab_email',
				vtype:'email',
				width:200
			}]
		});
	},
	initUserlogindata: function () {
		this.theUserlogindataFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('User login data',null,'usermanagement'); ?>',
			width: 500,
			height: 120,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 180,
			items: [{
				xtype: 'textfield',
				fieldLabel: '<?php echo __('Username',null,'usermanagement'); ?>',
				allowBlank: false,
				id: 'userFirstTab_username',
				width:200
			},{
				xtype: 'textfield',
				fieldLabel: '<?php echo __('Password',null,'usermanagement'); ?>',
				allowBlank: false,
				id: 'userFirstTab_password',
				inputType: 'password',
				width:200
			},{
				xtype: 'textfield',
				fieldLabel: '<?php echo __('Password (repeat)',null,'usermanagement'); ?>',
				allowBlank: false,
				id: 'userFirstTab_passwordagain',
				inputType: 'password',
				width:200
			}]
		});
	},
	
	
	initUserrole: function () {
		this.theUserroleFieldset = new Ext.form.FieldSet({
				title: '<?php echo __('Userrole',null,'usermanagement'); ?>',
				width: 500,
				height: 80,
				labelWidth: 180,
				style: 'margin-top:20px;margin-left:5px;',
				items:[{
					xtype: 'combo',
					fieldLabel : '<?php echo __('Userrole',null,'usermanagementpopup'); ?>',
					id: 'userFirstTab_userrole_id',
					valueField: 'id',
					mode: 'local',
					hiddenName : 'userFirstTab_userrole',
					displayField: 'description',
					store: this.theComboRoleStore,
					editable: false,
					typeAhead: false,
					allowBlank: false,
					triggerAction: 'all',
					width: 200
				
				}]
		});
	},
	
	initEmailformat:function () {
		this.theEmailformatFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Email Format',null,'usermanagement'); ?>',
			width: 500,
			height: 80,
			labelWidth: 180,
			style: 'margin-top:20px;margin-left:5px;',
			items:[{
				xtype: 'panel',
				layout: 'column',
				border: false,
				layoutConfig: {
					columns: 3
				},
				labelWidth: 150,
				fieldLabel: '<?php echo __('Email Format',null,'usermanagement'); ?>',
				width: 200,
				items: [{
					xtype: 'combo',
					id: 'userFirstTab_emailformat_id',
					mode: 'local',
					value: 'plain',
					editable:false,
					allowBlank: true,
					autoHeight:true,
					hiddenName: 'userFirstTab_emailformat',
					triggerAction: 'all',
					foreSelection: true,
					store: new Ext.data.SimpleStore({
						 fields:['id','text'],
	       				 data:[['plain', '<?php echo __('Plain',null,'usermanagement'); ?>'],['html', '<?php echo __('HTML',null,'usermanagement'); ?>']]
	   				}),
	 				valueField:'id',
					displayField:'text',
					width:60				
    			},{
    				xtype: 'panel',
    				html : '&nbsp;',
    				border: false,
					id: 'userFirstTab_spacepanel1'
    			},{
					xtype: 'combo', // number of records to display in grid
					id: 'userFirstTab_emailtype_id',
					mode: 'local',
					value: 'NONE',
					editable:false,
					allowBlank: true,
					autoHeight:true,
					hiddenName: 'userFirstTab_emailtype',
					triggerAction: 'all',
					foreSelection: true,
					store: new Ext.data.SimpleStore({
						 fields:['id','text'],
	       				 data:[['NONE', '<?php echo __('None',null,'usermanagement'); ?>'],['VALUES', '<?php echo __('Only values',null,'usermanagement'); ?>'],['IFRAME', '<?php echo __('IFrame',null,'usermanagement'); ?>']]
	   				}),
	 				valueField:'id',
					displayField:'text',
					width:133,
					listeners: {
						select: {
							fn:function(combo, value) {
								if (Ext.getCmp('userFirstTab_emailformat_id').getValue() == 'plain' && combo.getValue() == 'IFRAME') {
									Ext.Msg.minWidth = 200;
									Ext.MessageBox.alert('<?php echo __('Notice',null,'usermanagement'); ?>', '<?php echo __('Plain cannot be combined with IFrame',null,'usermanagement'); ?>');
									combo.setValue('VALUES');
								}
							}
						}
					}
				}]
			}]
		});
		
		if (Ext.isIE6 == true) {
			Ext.getCmp('userFirstTab_spacepanel1').html = '';
			Ext.getCmp('userFirstTab_spacepanel1').setSize({width:5,height:0});
		}
		else if(Ext.isOpera == true || Ext.isSafari == true) {
		}
		else if (Ext.isIE7 == true) {
		}
	},
	
	initFirstPanel: function () {
		this.thePanel = new Ext.Panel ({
			modal: true,
			closable: false,
			modal: true,
			width: 650,
			height: 600,
			autoScroll: false,
			title: '<?php echo __('Login Data',null,'usermanagement'); ?>',
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: false
		});
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
		cf.userFirstTab.theComboRoleStore.load();
	}
	







};}();