/**
* Class opens new window, to add a new user or edit an exisiting user
*/
cf.createUserWindow = function(){return {
	isInitialized 	                 : false,
	theUserId						 : false,
	theAddUserWindow	  	         : false,
	theTabPanel						 : false,
	theFormPanel				     : false,

	
	init:function () {
		if(cf.administration_myprofile.isInitialized == false) {
			this.theUserId = '';
			cf.userFirstTab.init();
			cf.userSecondTab.init(this.theUserId);
			cf.userThirdTab.init();
			cf.userFourthTab.init('<?php echo build_dynamic_javascript_url('systemsetting/LoadCirculationColumns')?>');
			this.initFormPanel();
			this.initTabPanel();
			this.initWindow();
			this.theTabPanel.add(cf.userFirstTab.thePanel);
			this.theTabPanel.add(cf.userSecondTab.thePanel);
			this.theTabPanel.add(cf.userThirdTab.thePanel);
			this.theTabPanel.add(cf.userFourthTab.thePanel);
			this.theFormPanel.add(this.theTabPanel);
			this.theAddUserWindow.add(this.theFormPanel);
			this.addData();
		}
	},
	
	
	initTabPanel: function () {
		this.theTabPanel = new Ext.TabPanel({
			activeTab: 0,
			enableTabScroll:true,
			border: false,
			deferredRender:true,
			frame: true,
			layoutOnTabChange: true,
			forceLayout : true,
			plain: false,
			closable:false
		});	
		
	},
	
	addData: function () {
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('usermanagement/LoadDefaultData')?>',
			success: function(objServerResponse){  
				var data = Ext.util.JSON.decode(objServerResponse.responseText);
				// first Tab
				Ext.getCmp('userFirstTab_username').setValue(data.result.username);
				Ext.getCmp('userFirstTab_password').setValue(data.result.password);
				Ext.getCmp('userFirstTab_passwordagain').setValue(data.result.password);
				
				Ext.getCmp('userFirstTab_emailformat_id').setValue(data.result.emailformat);
				Ext.getCmp('userFirstTab_emailtype_id').setValue(data.result.emailtype);
				
				
				// second Tab, load Grid here
				Ext.getCmp('userSecondTab_durationlength').setValue(data.result.durationlength);
				Ext.getCmp('userSecondTab_durationlength_type_id').setValue(data.result.durationtype);

				// fourth tab
				Ext.getCmp('userFourthTab_itemsperpage_id').setValue(data.result.displayeditem);
				Ext.getCmp('userFourthTab_refreshtime_id').setValue(data.result.refreshtime);
				Ext.getCmp('userFourthTab_circulationdefaultsortcolumn_id').setValue(data.result.circulationdefaultsortcolumn);
				Ext.getCmp('userFourthTab_circulationdefaultsortdirection_id').setValue(data.result.circulationdefaultsortdirection);
				Ext.getCmp('userFourthTab_showinpopup').setValue(data.result.showcirculationinpopup);
				Ext.getCmp('userFourthTab_markyellow').setValue(data.result.markyellow);
				Ext.getCmp('userFourthTab_markorange').setValue(data.result.markorange);
				Ext.getCmp('userFourthTab_markred').setValue(data.result.markred);
				
				cf.userFirstTab.thePanel.frame = true;
				cf.userSecondTab.thePanel.frame = true;
				cf.userThirdTab.thePanel.frame = true;
				cf.userFourthTab.thePanel.frame = true;
				cf.createUserWindow.theAddUserWindow.show();
				cf.createUserWindow.setRole.defer(1000, this, [data.result.role_id]);
			}
		});
	},
	setRole: function (value) {
		Ext.getCmp('userFirstTab_userrole_id').setValue(value);
	},
	
	initFormPanel: function () {
		this.theFormPanel = new Ext.FormPanel({
			frame:true       
		});
		
	},
	
	initWindow: function () {
		this.isInitialized = true;
		this.theAddUserWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: 800,
			width: 900,
			autoScroll: true,
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: true,
			title: '<?php echo __('Create new User',null,'usermanagement'); ?>',
	        buttonAlign: 'center',
			buttons:[{
				text:'<?php echo __('Store',null,'myprofile'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					if(Ext.getCmp('userFirstTab_password').getValue() == Ext.getCmp('userFirstTab_passwordagain').getValue()) {
						cf.saveUser.initSave(cf.createUserWindow.theFormPanel);
						cf.createUserWindow.isInitialized = false;
					}
					else {
						Ext.MessageBox.alert('<?php echo __('Error',null,'usermanagement'); ?>', '<?php echo __('Passwords not equal',null,'usermanagement'); ?>');
						cf.createUserWindow.theTabPanel.setActiveTab(0);
					}
				}
			},{
				text:'<?php echo __('Close',null,'usermanagement'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.createUserWindow.isInitialized = false;
					cf.createUserWindow.theAddUserWindow.hide();
					cf.createUserWindow.theAddUserWindow.destroy();
				}
			}]
		});
		this.theAddUserWindow.on('close', function() {
			cf.createUserWindow.isInitialized = false;
			cf.createUserWindow.theAddUserWindow.hide();
			cf.createUserWindow.theAddUserWindow.destroy();
		});
	}
	
	
	
};}();