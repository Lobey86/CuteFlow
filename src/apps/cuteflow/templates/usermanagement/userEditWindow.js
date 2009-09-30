/**
* Class opens new window, to add a new user or edit an exisiting user
*/
cf.editUserWindow = function(){return {
	isInitialized 	                 : false,
	theUserId						 : false,
	theEditUserWindow	  	         : false,
	theTabPanel						 : false,
	theFormPanel				     : false,

	
	init:function (id) {
		if(cf.administration_myprofile.isInitialized == false) {
			this.theUserId = id;
			cf.userFirstTab.init();
			cf.userSecondTab.init(this.theUserId);
			cf.userThirdTab.init();
			cf.userFourthTab.init('<?php echo build_dynamic_javascript_url('myprofile/LoadUserCirculationColumns')?>/id/' + this.theUserId);
			this.initFormPanel();
			this.initTabPanel();
			this.initWindow();
			this.theTabPanel.add(cf.userFirstTab.thePanel);
			this.theTabPanel.add(cf.userSecondTab.thePanel);
			this.theTabPanel.add(cf.userThirdTab.thePanel);
			this.theTabPanel.add(cf.userFourthTab.thePanel);
			this.theFormPanel.add(this.theTabPanel);
			this.theEditUserWindow.add(this.theFormPanel);
			this.addData();
		}
		else {
			Ext.Msg.minWidth = 200;
			Ext.MessageBox.alert('<?php echo __('Error',null,'usermanagement'); ?>', '<?php echo __('Profile changes and editing/creating user at same time is not supported',null,'usermanagement'); ?>');
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
			url : '<?php echo build_dynamic_javascript_url('usermanagement/LoadSingleUser')?>/id/' + cf.editUserWindow.theUserId,
			success: function(objServerResponse){  
				try {
					var data = Ext.util.JSON.decode(objServerResponse.responseText);
					// first Tab
					Ext.getCmp('userFirstTab_firstname').setValue(data.result.firstname);
					Ext.getCmp('userFirstTab_lastname').setValue(data.result.lastname);
					Ext.getCmp('userFirstTab_email').setValue(data.result.email);
					Ext.getCmp('userFirstTab_username').setValue(data.result.username);
					Ext.getCmp('userFirstTab_username').setDisabled(true);
					Ext.getCmp('userFirstTab_password').setValue(data.result.password);
					Ext.getCmp('userFirstTab_passwordagain').setValue(data.result.password);
					Ext.getCmp('userFirstTab_emailformat_id').setValue(data.result.emailformat);
					Ext.getCmp('userFirstTab_emailtype_id').setValue(data.result.emailtype);
					
					
					// second Tab, load Grid here
					Ext.getCmp('userSecondTab_durationlength').setValue(data.result.durationlength);
					Ext.getCmp('userSecondTab_durationlength_type_id').setValue(data.result.durationtype);
					
					// third tab
					Ext.getCmp('userThirdTab_street').setValue(data.result.street);
					Ext.getCmp('userThirdTab_zip').setValue(data.result.zip);
					Ext.getCmp('userThirdTab_city').setValue(data.result.city);
					Ext.getCmp('userThirdTab_country').setValue(data.result.country);
					Ext.getCmp('userThirdTab_phone1').setValue(data.result.phone1);
					Ext.getCmp('userThirdTab_phone2').setValue(data.result.phone1);
					Ext.getCmp('userThirdTab_mobil').setValue(data.result.mobil);
					Ext.getCmp('userThirdTab_fax').setValue(data.result.fax);
					Ext.getCmp('userThirdTab_organisation').setValue(data.result.organisation);
					Ext.getCmp('userThirdTab_department').setValue(data.result.department);
					Ext.getCmp('userThirdTab_burdencenter').setValue(data.result.burdencenter);
					Ext.getCmp('userThirdTab_comment').setValue(data.result.comment);
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
					cf.editUserWindow.theEditUserWindow.show();
					cf.userSecondTab.theUserAgentStore.load();
					cf.editUserWindow.setRole.defer(1000, this, [data.result.role_id]);
				}
				catch(e) {
					
				}
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
		this.theEditUserWindow = new Ext.Window({
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
			title: '<?php echo __('Edit existing User',null,'usermanagement'); ?>',
	        buttonAlign: 'center',
			buttons:[{
				text:'<?php echo __('Store',null,'myprofile'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					if(Ext.getCmp('userFirstTab_password').getValue() == Ext.getCmp('userFirstTab_passwordagain').getValue()) {
						cf.updateUser.initSave(cf.editUserWindow.theFormPanel,cf.editUserWindow.theUserId);
					}
					else {
						Ext.MessageBox.alert('<?php echo __('Error',null,'usermanagement'); ?>', '<?php echo __('Passwords not equal',null,'usermanagement'); ?>');
						cf.editUserWindow.theTabPanel.setActiveTab(0);
					}
				}
			},{
				text:'<?php echo __('Close',null,'usermanagement'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {	
					cf.editUserWindow.isInitialized = false;
					cf.editUserWindow.theEditUserWindow.hide();
					cf.editUserWindow.theEditUserWindow.destroy();
				}
			}]
		});
		this.theEditUserWindow.on('close', function() {
			cf.editUserWindow.isInitialized = false;	
			cf.editUserWindow.theEditUserWindow.hide();
			cf.editUserWindow.theEditUserWindow.destroy();		
		});
	}
	
	
	
};}();