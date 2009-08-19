cf.AddRoleTabpanel = function(){return {
	
	theTabpanelIsInitialized				:false,
	theTabpanel								:false,
	theRoleNameTextfiel						:false,
	theManagementTab						:false,
	
	
	init: function () {
		this.initTextfield();
		this.initManagement();
		this.initTabpanel();
		this.theTabpanel.add(this.theRoleNameText);
		this.theTabpanel.add(this.theManagementTab);
		this.theTabpanel.setActiveTab(this.theRoleNameText);
	},
	
	
	initTabpanel: function () {
		this.theTabpanel = new Ext.TabPanel({
			title: 'fwefew',
			frame: false,
			plain: false
		});
	},
	
	initTextfield: function () {
		this.theRoleNameText = new Ext.Panel({
			title: 'Name',
			frame: true,
			items:[{
				xtype: 'fieldset',
				title: 'Userrole Name',
				style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			    items: [{
	                xtype: 'textfield',
	                name: 'txt-test1',
	                fieldLabel: 'Userrole title'
            	}]
			}]
			
		});
	},
	
	initManagement: function () {
		this.theManagementTab = new Ext.Panel({
			title: 'Management',
			frame: true,
			items:[{
				xtype: 'fieldset',
				defaultType: 'checkbox',
				title: '<table><tr><td><img src="/images/icons/group.png" /></td><td>User Management</td></tr></table>',
				style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			    items: [{
               		fieldLabel: '<b>Modul:&nbsp;Benutzer&nbsp;verwalten</b>',
               		style:'margin-top:4px;margin-left:120px;',
               		width:500,
               		id: 'module_usermanagement',
	                name: '1',
	                handler: function () {
	                	var parent = Ext.getCmp('module_usermanagement');
	    				if(parent.getValue() == true) {
	    					Ext.getCmp('module_usermanagement_editUser').setValue(true);
	    					Ext.getCmp('module_usermanagement_createUser').setValue(true);
	    					Ext.getCmp('module_usermanagement_deleteUser').setValue(true);
	    				}
	    				else {
	    					Ext.getCmp('module_usermanagement_editUser').setValue(false);
	    					Ext.getCmp('module_usermanagement_createUser').setValue(false);
	    					Ext.getCmp('module_usermanagement_deleteUser').setValue(false);
	    				}
	    
	                }
            	},{
               		fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;Benutzer&nbsp;bearbeiten',
               		style:'margin-top:4px;margin-left:120px;',
               		id: 'module_usermanagement_editUser',
               		width:500,
	                name: '2'
            	},{
               		fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;Benutzer&nbsp;anlegen',
               		id: 'module_usermanagement_createUser',
               		style:'margin-top:4px;margin-left:120px;',
               		width:500,
	                name: '3'
            	},{
               		fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;Benutzer&nbsp;l&ouml;schen',
               		style:'margin-top:4px;margin-left:120px;',
               		id: 'module_usermanagement_deleteUser',
               		width:500,
	                name: '4'
            	}]
			},
			{
				xtype: 'fieldset',
				defaultType: 'checkbox',
				defaultWidth: 500,
				title: '<table><tr><td><img src="/images/icons/user_edit.png" /></td><td>My Profile</td></tr></table>',
				style:'margin-top:5px;margin-left:5px;margin-right:5px;',
		   		items: [{
               		fieldLabel: '<b>Modul:&nbsp;Mein&nbsp;Profil</b>',
               		style:'margin-top:4px;margin-left:120px;',
               		width:500,
               		id: 'module_myprofile',
	                name: '5',
	                handler: function () {
	                	var parent = Ext.getCmp('module_myprofile');
	    				if(parent.getValue() == true) {
	    					Ext.getCmp('module_myprofile_changedetails').setValue(true);
	    					Ext.getCmp('module_myprofile_changerole').setValue(true);
	    				}
	    				else {
	    					Ext.getCmp('module_myprofile_changedetails').setValue(false);
	    					Ext.getCmp('module_myprofile_changerole').setValue(false);
	    				}
	                }
            	},{
               		fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;Eigenes&nbsp;Profil&nbsp;&auml;ndern',
               		style:'margin-top:4px;margin-left:120px;',
               		id: 'module_myprofile_changedetails',
               		width:500,
	                name: '6'
            	},{
               		fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;Rolle&nbsp;&auml;ndern',
               		id: 'module_myprofile_changerole',
               		style:'margin-top:4px;margin-left:120px;',
               		width:500,
	                name: '7'
            	}]
			},{
				xtype: 'fieldset',
				defaultType: 'checkbox',
				defaultWidth: 500,
				title: '<table><tr><td><img src="/images/icons/wrench.png" /></td><td>Systemsettings</td></tr></table>',
				style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			    items: [{
               		fieldLabel: '<b>Modul:&nbsp;Systemeinstellungen</b>',
               		style:'margin-top:4px;margin-left:120px;',
               		width:500,
	                name: '1'
            	}]
			},{
				xtype: 'fieldset',
				defaultType: 'checkbox',
				defaultWidth: 500,
				title: '<table><tr><td><img src="/images/icons/cog.png" /></td><td>User&nbsp;role&nbsp;management</td></tr></table>',
				style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			    items: [{
               		fieldLabel: '<b>Modul:&nbsp;Rollenmanagement</b>',
               		style:'margin-top:4px;margin-left:120px;',
               		width:500,
	                name: '1'
            	}]
			},{
				xtype: 'fieldset',
				defaultType: 'checkbox',
				defaultWidth: 500,
				title: '<table><tr><td><img src="/images/icons/note_go.png" /></td><td>Systemmessage</td></tr></table>',
				style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			    items: [{
               		fieldLabel: '<b>Modul&nbsp;Systemnachrichten</b>',
               		style:'margin-top:4px;margin-left:120px;',
               		width:500,
	                name: '1'
            	}]
			}]
			
		});
		
	}
};}();