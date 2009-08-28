cf.AddRoleWindow = function(){return {
	
	theAddRoleWin						:false,
	theAddRoleWindowIsInitialzied		:false,
	
	init: function () {
		this.initWindow();
		cf.AddRoleTabpanel.init();
		this.theAddRoleWin.add(cf.AddRoleTabpanel.theFormPanel);
		this.theAddRoleWin.show();
	},
	
	
	initWindow: function() {
		//this.theAddRoleWindowIsInitialzied = true;
		this.theAddRoleWin = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: 700,
			width: 650,
			autoScroll: true,
			title: '<?php echo __('Add new Userrole',null,'userrolemanagement'); ?>',
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: false,
			id: 'mainWindow_id',
	        buttonAlign: 'center',
			close : function(){
				cf.AddRoleWindow.theAddRoleWin.hide();
				cf.AddRoleWindow.theAddRoleWin.destroy();
			},
			buttons:[{
				id: 'addButton',
				text:'<?php echo __('Store',null,'userrolemanagement'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					var textfield = Ext.getCmp('userrole_title_id');
					if(textfield.getValue() == '') {
						cf.AddRoleTabpanel.theTabpanel.setActiveTab(0);
						textfield.focus();
					}
					else {
						Ext.Ajax.request({  
							url : '<?php echo url_for('userrolemanagement/CheckForExistingRole')?>/description/' + textfield.getValue(),
							success: function(objServerResponse){
								if(objServerResponse.responseText == 1) {
									
									cf.AddRoleTabpanel.theFormPanel.getForm().submit({
										url: '<?php echo url_for('userrolemanagement/AddRole')?>',
										success: function(theForm, action) {
											alert("ok");
											cf.UserRoleGrid.theUserRoleStore.reload();
											cf.AddRoleWindow.theAddRoleWin.hide();
											cf.AddRoleWindow.theAddRoleWin.destroy();
										}
									});
								}
								else {
									Ext.MessageBox.alert('<?php echo __('Error',null,'userrolemanagement'); ?>', '<?php echo __('Role is already existing',null,'userrolemanagement'); ?>');
									cf.AddRoleTabpanel.theTabpanel.setActiveTab(0);
									textfield.focus();
									textfield.setValue();
								}
							}
						});
					}
				}
			},{
				id: 'cancelButton',
				text:'<?php echo __('Close',null,'userrolemanagement'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.AddRoleWindow.theAddRoleWin.hide();
					cf.AddRoleWindow.theAddRoleWin.destroy();
				}
			}]
		});
			
	}
};}();