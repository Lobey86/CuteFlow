cf.userAgentSetting = function(){return {
	
	theUserAgentTab			:false,
	theUserAgentFieldset	:false,
	theConfigFieldset		:false,
	

	init:function () {
		this.initUserAgentTab();
		this.initUserAgentFieldset();
		this.initConfigFieldset();
		this.theUserAgentTab.add(this.theUserAgentFieldset);
		this.theUserAgentTab.add(this.theConfigFieldset);
	},
	
	
	
	initUserAgentTab: function () {
		this.theUserAgentTab = new Ext.Panel({
			modal: true,
			closable: false,
			modal: true,
			width: 650,
			height: 600,
			autoScroll: false,
			title: '<?php echo __('Useragent Settings',null,'systemsetting'); ?>',
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: false
		});	
		
	},
	
	initConfigFieldset: function () {
		this.theConfigFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Useragent Settings',null,'systemsetting'); ?>',
			width: 600,
			height: 300,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 330,
			hidden: false,
			items:[{
				xtype:'label',
				html: '<span style="font-size:12px;font-family:Tahoma, Geneva, sans-serif;"><b><?php echo __('Cronjob will run on selected days',null,'systemsetting'); ?>:</b></span>'
			},{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				inputValue: '1',
				id: 'useragent_useragentsettings_monday',
				fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Monday',null,'systemsetting'); ?>'
			},{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				inputValue: '2',
				id: 'useragent_useragentsettings_tuesday',
				fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Tuesday',null,'systemsetting'); ?>'
			},{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				inputValue: '4',
				id: 'useragent_useragentsettings_wednesday',
				fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Wednesday',null,'systemsetting'); ?>'
			},{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				inputValue: '8',
				id: 'useragent_useragentsettings_thursday',
				fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Thursday',null,'systemsetting'); ?>'
			},{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				inputValue: '16',
				id: 'useragent_useragentsettings_friday',
				fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Friday',null,'systemsetting'); ?>'
			},{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				inputValue: '32',
				id: 'useragent_useragentsettings_saturday',
				fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Saturday',null,'systemsetting'); ?>'
			},{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				inputValue: '64',
				id: 'useragent_useragentsettings_sunday',
				fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Sonday',null,'systemsetting'); ?>'
			},{
				xtype:'label',
				html: '<span style="font-size:12px;font-family:Tahoma, Geneva, sans-serif;"><b><?php echo __('Set Time when cronjob will run',null,'systemsetting'); ?>:</b></span>'
			},{
				xtype: 'combo',
				mode: 'local',
				fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Time from',null,'systemsetting'); ?>',
				editable:false,
				id: 'useragent_useragentsettings_from_id',
				allowBlank: true,
				valueField:'id',
				hiddenName : 'useragent_useragentsettings_from',
				width: 70,
				displayField:'text',
				triggerAction: 'all',
				foreSelection: true,
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[['mysql', '<?php echo __('MySQL',null,'systemsetting'); ?>'],['oracle', '<?php echo __('Oracle',null,'systemsetting'); ?>'],['sqlite', '<?php echo __('Sqlite',null,'systemsetting'); ?>'],['postgresql', '<?php echo __('PostgresQL',null,'systemsetting'); ?>']]
   				})
			},{
				
				xtype: 'combo',
				mode: 'local',
				editable:false,
				id: 'useragent_useragentsettings_to_id',
				allowBlank: true,
				valueField:'id',
				hiddenName : 'useragent_useragentsettings_to',
				width: 70,
				fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Time to',null,'systemsetting'); ?>',
				displayField:'text',
				triggerAction: 'all',
				foreSelection: true,
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[['mysql', '<?php echo __('MySQL',null,'systemsetting'); ?>'],['oracle', '<?php echo __('Oracle',null,'systemsetting'); ?>'],['sqlite', '<?php echo __('Sqlite',null,'systemsetting'); ?>'],['postgresql', '<?php echo __('PostgresQL',null,'systemsetting'); ?>']]
   				})
			}]
		});	
		
		if (Ext.isIE6 == true) {
			/*Ext.getCmp('userThirdTab_zip').style = 'margin-top:0px;margin-bottom:0px;margin-right:5px;';
			Ext.getCmp('userThirdTab_city').style = 'margin-top:0px;margin-bottom:0px;';
			Ext.getCmp('userThirdTab_city').setSize({width: 164})*/
		}
		else if (Ext.isIE7 == true) {
			/*Ext.getCmp('userThirdTab_zip').style = 'margin-top:0px;margin-bottom:0px;margin-right:5px;';
			Ext.getCmp('userThirdTab_city').style = 'margin-top:0px;margin-bottom:0px;';
			Ext.getCmp('userThirdTab_city').setSize({width: 162})*/
			
		}
	},
	
	initUserAgentFieldset: function () {
		this.theUserAgentFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Useragent Settings',null,'systemsetting'); ?>',
			width: 600,
			height: 120,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 330,
			items:[{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				inputValue: '1',
				id: 'useragent_useragentsettings',
				fieldLabel: '<?php echo __('Set Time for UserAgent Cronjob',null,'systemsetting'); ?>',
				handler: function (check) {
					if(check.checked) {
						cf.userAgentSetting.theConfigFieldset.setVisible(true);
					}
					else {
						cf.userAgentSetting.theConfigFieldset.setVisible(false);
					}
				}
			},{
				xtype:'checkbox',
				style: 'margin-top:3px;',
				id: 'useragent_useragentcreation',
				fieldLabel: '<?php echo __('Create ueragent foreach cronjob run',null,'systemsetting'); ?>'			
			}]
		});
	}

	
};}();


