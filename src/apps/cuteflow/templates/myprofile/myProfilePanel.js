cf.administration_myprofile = function(){return {
	
	isInitialized 	                 : false,
	theUserId						 : false,
	theMyProfilePanel	  	         : false,
	theTabPanel						 : false,
	
	init:function () {
		
		this.theUserId = '<?php echo $sf_user->getAttribute('id')?>';
		cf.userFirstTab.init();
		cf.userSecondTab.init();
		cf.userThirdTab.init();
		cf.userFourthTab.init();
		this.initTabPanel();
		this.initWindow();
		this.theTabPanel.add(cf.userFirstTab.thePanel);
		this.theTabPanel.add(cf.userSecondTab.thePanel);
		this.theTabPanel.add(cf.userThirdTab.thePanel);
		this.theTabPanel.add(cf.userFourthTab.thePanel);
		this.theMyProfilePanel.add(this.theTabPanel);
		this.addData();
		

	},
	
	initTabPanel: function () {
		this.theTabPanel = new Ext.TabPanel({
			activeTab: 0,
			enableTabScroll:true,
			border: false,
			deferredRender:true,
			frame: true,
			layoutOnTabChange: true,
			style: 'margin-top:5px;',
			plain: false,
			closable:false
		});	
		
	},
	
	addData: function () {
		/*var url =  '<?php echo build_dynamic_javascript_url('usermanagement/LoadRightGrid')?>/id/'+cf.administration_myprofile.theUserId;
		cf.userSecondTab.theUserAgentStore.proxy.setApi(Ext.data.Api.actions.read,url);
		cf.userSecondTab.theUserAgentStore.load();*/
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('usermanagement/LoadSingleUser')?>/id/' + cf.administration_myprofile.theUserId,
			success: function(objServerResponse){  
				var data = Ext.util.JSON.decode(objServerResponse.responseText);
				// first Tab
				Ext.getCmp('userFirstTab_firstname').setValue(data.result.firstname);
				Ext.getCmp('userFirstTab_lastname').setValue(data.result.lastname);
				Ext.getCmp('userFirstTab_email').setValue(data.result.email);
				Ext.getCmp('userFirstTab_username').setValue(data.result.username);
				Ext.getCmp('userFirstTab_username').setDisabled(true);
				Ext.getCmp('userFirstTab_password').setValue(data.result.password);
				Ext.getCmp('userFirstTab_passwordagain').setValue(data.result.password);
				Ext.getCmp('userFirstTab_userrole_id').setValue(data.result.rolename);
				Ext.getCmp('userFirstTab_emailformat_id').setValue(data.result.emailformat);
				Ext.getCmp('userFirstTab_emailtype_id').setValue(data.result.emailtype);
				
				// second Tab, load Grid here
				Ext.getCmp('userSecondTab_durationlength').setValue(data.result.durationlength);
				Ext.getCmp('userSecondTab_durationlength_type_id').setValue(data.result.durationtype);
				
				// third tab
				
				
				
				
			}
		});
	},
	
	initWindow: function () {
		this.isInitialized = true;
		this.theMyProfilePanel = new Ext.FormPanel({
			modal: true,
			closable: true,
			modal: true,
			layout: 'fit',
			autoScroll: false,
			title: '<?php echo __('Profile Settings',null,'myprofile'); ?>',
			shadow: false,
			minimizable: false,
			draggable: false,
			border: true,
			resizable: false,
	        plain: false,
	        buttonAlign: 'center',
			close : function(){
				var activeTab = cf.TabPanel.theTabPanel.getActiveTab();
				cf.TabPanel.theTabPanel.remove(activeTab);
				cf.administration_myprofile.theMyProfilePanel.hide();
				cf.administration_myprofile.theMyProfilePanel.destroy();
			},
			buttons:[{
				text:'<?php echo __('Store',null,'myprofile'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					alert('save changes');
				}
			},{
				text:'<?php echo __('Close',null,'myprofile'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					var activeTab = cf.TabPanel.theTabPanel.getActiveTab();
					cf.TabPanel.theTabPanel.remove(activeTab);
					cf.administration_myprofile.theMyProfilePanel.hide();
					cf.administration_myprofile.theMyProfilePanel.destroy();
				}
			}]
		});
	},

	/** 
	 * Part of the API
	 * set value if class is already initialized. 
	 * @param boolean value
	 *
	 **/
	setInitialized: function (value) {
		this.isInitialized = value;
	},
	
	/**
	* Part of the API
	* This function returns the window, to add it into tabpanel
	*
	*/
	getInstance: function() {
		return this.theMyProfilePanel;
	}
	
};}();