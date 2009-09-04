/**
* Class opens new window, to add a new user
*/
cf.AddUserWindow = function(){return {
	
	theAddUserWindow					:false,
	theAddUserWindowIsInitialized		:false,
	theFormPanel						:false,
	theTabpanel							:false,
	
	init: function (new_flag, id) {
		cf.AddUserFirstTab.init();
		cf.AddUserSecondTab.init();
		this.initTabpanel();
		this.theTabpanel.add(cf.AddUserFirstTab.theFirstPanel);
		this.theTabpanel.add(cf.AddUserSecondTab.theSecondPanel);
		this.theTabpanel.setActiveTab(0);
		this.initFormPanel();
		this.theFormPanel.add(this.theTabpanel);
		this.addValues(new_flag,id);
		this.initWindow(new_flag, id);
		this.theAddUserWindow.add(this.theFormPanel);
		this.theAddUserWindow.show();
	},
	
	addValues: function (new_flag, id) {
		
		if (new_flag != 1) {
			Ext.getCmp('username').setDisabled(true);
			Ext.Ajax.request({  
				url : '<?php echo url_for('usermanagement/LoadSingleUser')?>/id/' + id,
				success: function(objServerResponse){
				
					userData = Ext.util.JSON.decode(objServerResponse.responseText);
					Ext.getCmp('firstname').setValue(userData.result.firstname);
					Ext.getCmp('lastname').setValue(userData.result.lastname);
					Ext.getCmp('email').setValue(userData.result.email);
					Ext.getCmp('username').setValue(userData.result.username);
					Ext.getCmp('password').setValue(userData.result.password);
					Ext.getCmp('passwordAgain').setValue(userData.result.password);
					Ext.getCmp('userrole').setValue(userData.result.role_id);
					Ext.getCmp('agent').setValue(userData.result.useragent);
					if(userData.result.useragent != '') {
						Ext.getCmp('type').setValue(userData.result.durationtype);
						Ext.getCmp('durationlength').setValue(userData.result.durationlength);
					}
					Ext.getCmp('street').setValue(userData.result.street);
					Ext.getCmp('zip').setValue(userData.result.zip);
					Ext.getCmp('city').setValue(userData.result.city);
					Ext.getCmp('country').setValue(userData.result.country);
					Ext.getCmp('phone1').setValue(userData.result.phone1);
					Ext.getCmp('phone2').setValue(userData.result.phone2);
					Ext.getCmp('mobil').setValue(userData.result.mobil);
					Ext.getCmp('fax').setValue(userData.result.fax);
					Ext.getCmp('organisation').setValue(userData.result.organisation);
					Ext.getCmp('department').setValue(userData.result.department);
					Ext.getCmp('burdencenter').setValue(userData.result.burdencenter);
					Ext.getCmp('comment').setValue(userData.result.comment);
				}
			});
		}
	},
	
	initWindow: function(new_flag, id) {
		if(new_flag == 1) {
			var title = 'Neuer Benutzer anlegen';
			Ext.getCmp('hiddenfield').setValue('');
		}
		else {
			var title = 'Benutzer editieren';
			Ext.getCmp('hiddenfield').setValue(id);
		}
		
		this.theAddUserWindowIsInitialized = true;
		this.theAddUserWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: 700,
			width: 650,
			autoScroll: true,
			title: title,
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: false,
			id: 'mainWindow_id',
	        buttonAlign: 'center',
			close : function(){
				cf.AddUserWindow.theAddUserWindow.hide();
				cf.AddUserWindow.theAddUserWindow.destroy();
			},
			buttons:[{
				id: 'addButton',
				text:'<?php echo __('Store',null,'userrolemanagementpopup'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.UserCRUD.saveUser(new_flag,id);
				}
			},{
				id: 'cancelButton',
				text:'<?php echo __('Close',null,'userrolemanagementpopup'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.AddUserWindow.theAddUserWindow.hide();
					cf.AddUserWindow.theAddUserWindow.destroy();
				}
			}]
		});
	},
	
	initFormPanel: function () {
		this.theFormPanel = new Ext.FormPanel({
			id: 'submitUser'
		})
		
	},
	initTabpanel: function () {
		this.theTabpanel = new Ext.TabPanel({
			frame: true,
			enableTabScroll:true,
			plain: false,
			id: 'addUserTabPanel',
			deferredRender:false
		});
	}
	
	
	
};}();