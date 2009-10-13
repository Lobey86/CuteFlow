/**
* Function inits the popup to add a new role, or to edit an existing role 
*
*/
cf.rolePopUpWindow = function(){return {
	
	theRoleWindow						:false,
	theRoleWindowIsInitialzied			:false,
	
	/**
	* calls all necessary functions to display the popup
	*
	* @param boolean new_flag, if 1 then a new record will be created, if 0 then a exisitng record is edited
	* @param int id, is only set, if a record is edited
	*
	*/
	initNewRole: function (id) {
		this.initWindow(id, '<?php echo __('Add new Userrole',null,'userrolemanagement'); ?>');
		cf.PopUpRoleTabpanel.init(id);
		this.theRoleWindow.add(cf.PopUpRoleTabpanel.theFormPanel);
		this.theRoleWindow.show();
	},
	
	initEditRole: function (id) {
		this.initWindow(id, '<?php echo __('Edit Userrole',null,'userrolemanagement'); ?>');
		cf.PopUpRoleTabpanel.init(id);
		this.theRoleWindow.add(cf.PopUpRoleTabpanel.theFormPanel);
		this.theRoleWindow.show();
		
	},
	
	/**
	* Inits the main popup window, with save and cancel button. 
	*
	* @param boolean new_flag, if 1 then a new record will be created, if 0 then a exisitng record is edited
	* @param int id, is only set, if a record is edited
	*/
	initWindow: function(id, title) {
		this.theRoleWindowIsInitialzied = true;
		this.theRoleWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: cf.Layout.theRegionWest.getHeight() - 120,
			width: 650,
			autoScroll: false,
			title: title,
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: false,
	        buttonAlign: 'center',
			close : function(){
				cf.rolePopUpWindow.theRoleWindow.hide();
				cf.rolePopUpWindow.theRoleWindow.destroy();
			},
			buttons:[{
				text:'<?php echo __('Store',null,'userrolemanagement'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.RoleCRUD.saveRole(id);
				}
			},{
				text:'<?php echo __('Close',null,'userrolemanagement'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.rolePopUpWindow.theRoleWindow.hide();
					cf.rolePopUpWindow.theRoleWindow.destroy();
				}
			}]
		});
	}
};}();