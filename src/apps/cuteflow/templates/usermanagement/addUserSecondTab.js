/**
* Class adds the second Tab when editing and creating new user
*
*/
cf.AddUserSecondTab = function(){return {
	
	theSecondPanel					: false,
	
	
	/** calls all necessary functions **/
	init: function() {
		this.initPanel();
	},
	
	/** builds panel and its elements **/
	initPanel: function () {
		this.theSecondPanel = new Ext.Panel({
			title: '<?php echo __('User details',null,'usermanagementpopup'); ?>',
			frame: true,
			enableTabScroll:true,
			plain: true,
			layout: 'form',
			labelWidth : 200,
			width: 'auto',
			height: 529,
			items:[{
				xtype: 'fieldset',
				title: '<?php echo __('Address',null,'usermanagementpopup'); ?>',
				items:[{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Street',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'street',
					id: 'street',
					allowBlank: true,
					width: 200
				},{
					xtype: 'panel',
					fieldLabel: '<?php echo __('Zip / City',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					border: 'none',
					id: 'testpanel',
					layout: 'column',
					allowBlank: true,
					height: 'auto',
					width: 200,
					items:[{
						xtype: 'textfield',
						labelStyle: 'font-weight:bolder;',
						style: 'margin-right:5px;',
						name: 'zip',
						id: 'zip',
						allowBlank: true,
						width: 45
					},{
						xtype: 'textfield',
						labelStyle: 'font-weight:bolder;',
						name: 'city',
						id: 'city',
						allowBlank: true,
						width: 162
					}]
				},{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Country',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'country',
					id: 'country',
					allowBlank: true,
					width: 200
				}]		
			},{
				xtype: 'fieldset',
				title: '<?php echo __('Phone and Fax',null,'usermanagementpopup'); ?>',
				items: [{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Phone 1',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'phone1',
					id: 'phone1',
					allowBlank: true,
					width: 200	
				},{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Phone 2',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'phone2',
					id: 'phone2',
					allowBlank: true,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Mobile',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'mobil',
					id: 'mobil',
					allowBlank: true,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Fax',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'fax',
					id: 'fax',
					allowBlank: true,
					width: 200
				}]
			},{
				xtype: 'fieldset',
				title: '<?php echo __('Organisation, Department and Burdence center',null,'usermanagementpopup'); ?>',
				items:[{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Organisation',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'organisation',
					id: 'organisation',
					allowBlank: true,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Department',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'department',
					id: 'department',
					allowBlank: true,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: '<?php echo __('Burdence center',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'burdencenter',
					id: 'burdencenter',
					allowBlank: true,
					width: 200
				}]
			},{
				xtype: 'fieldset',
				title: '<?php echo __('Comment',null,'usermanagementpopup'); ?>',
				items:[{
					xtype: 'textarea',
					fieldLabel: '<?php echo __('Comment',null,'usermanagementpopup'); ?>',
					labelStyle: 'font-weight:bolder;',
					id:'comment',
					name: 'comment',
					allowBlank: true,
					width: 200,
					height: 70
				}]
			}]
		});
		Ext.getCmp('testpanel').setSize();

		
	}
	
	
	
};}();