/**
* Function inits the popup to add a new role, or to edit an existing role 
*
*/
cf.AddRoleWindow = function(){return {
	
	theAddRoleWin						:false,
	theAddRoleWindowIsInitialzied		:false,
	
	/**
	* calls all necessary functions to display the popup
	*
	* @param boolean new_flag, if 1 then a new record will be created, if 0 then a exisitng record is edited
	* @param int id, is only set, if a record is edited
	*
	*/
	init: function (new_flag, id) {
		this.initWindow(new_flag,id);
		cf.AddRoleTabpanel.init(id);
		this.theAddRoleWin.add(cf.AddRoleTabpanel.theFormPanel);
		this.theAddRoleWin.show();
	},
	
	
	/**
	* Inits the main popup window, with save and cancel button. 
	*
	* @param boolean new_flag, if 1 then a new record will be created, if 0 then a exisitng record is edited
	* @param int id, is only set, if a record is edited
	*/
	initWindow: function(new_flag,id) {
		if(new_flag == 1) {
			var title = '<?php echo __('Add new Userrole',null,'userrolemanagementpopup'); ?>';
		}
		else {
			var title = '<?php echo __('Edit Userrole',null,'userrolemanagementpopup'); ?>';
		}
		
		this.theAddRoleWindowIsInitialzied = true;
		this.theAddRoleWin = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: 800,
			width: 650,
			autoScroll: true,
			title: title,
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: false,
	        buttonAlign: 'center',
			close : function(){
				cf.AddRoleWindow.theAddRoleWin.hide();
				cf.AddRoleWindow.theAddRoleWin.destroy();
			},
			buttons:[{
				text:'<?php echo __('Store',null,'userrolemanagementpopup'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.RoleCRUD.saveRole(new_flag,id);
				}
			},{
				text:'<?php echo __('Close',null,'userrolemanagementpopup'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.AddRoleWindow.theAddRoleWin.hide();
					cf.AddRoleWindow.theAddRoleWin.destroy();
				}
			}]
		});
	}
};}();