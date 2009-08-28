cf.DeleteRoleWindow = function(){return {

	theRoleDeleteWindow			:false,
	theCombobox					:false,
	theComboStore				:false,
	thePanel					:false,
	
	
	init:function (id) {
		this.initComboStore(id);
		this.theComboStore.load();
		this.initPanel();
		this.initWindow(id);
		this.theRoleDeleteWindow.add(this.thePanel);
		this.theRoleDeleteWindow.show();
	
	},
	
	
	initComboStore: function (id) {
		this.theComboStore = new Ext.data.JsonStore({
			mode: 'local',
			autoload: true,
			url: '<?php echo url_for('userrolemanagement/LoadDeletableRoles')?>/id/' + id,
			root: 'result',
			fields: [
				{name: 'value'},
				{name: 'text'}
			]
		});
	},
	
	initPanel: function () {
		this.thePanel = new Ext.Panel({
			plain: false,
			frame: true,
			buttonAlign: 'center',
			layout: 'form',
			height: 110,
			autoScroll: false,
			items: [{
				xtype: 'combo',
				fieldLabel: '<?php echo __('Move all users to',null,'userrolemanagement'); ?>',
				valueField: 'value',
				displayField: 'text',
				editable: false,
				mode: 'local',
				id: 'deletCombo',
				triggerAction: 'all',
				selectOnFocus:true,
				allowBlank: false,
				forceSelection:true,
				store: cf.DeleteRoleWindow.theComboStore
				
			}]
		});
	},
	
	
	initWindow: function (deleteid) {
		this.theRoleDeleteWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: 130,
			width: 400,
			autoScroll: false,
			title: '<?php echo __('Delete Role',null,'userrolemanagement'); ?>',
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: false,
			close : function(){
				cf.DeleteRoleWindow.theRoleDeleteWindow.hide();
				cf.DeleteRoleWindow.theRoleDeleteWindow.destroy();
			},
	        buttonAlign: 'center',
			buttons:[{
	                    id: 'removeButton',
						text:'<?php echo __('Delete',null,'userrolemanagement'); ?>', 
						icon: '/images/icons/accept.png',
						handler: function () {
							if(Ext.getCmp('deletCombo').getValue() != '') {
								var updateid = (Ext.getCmp('deletCombo').getValue());
								Ext.Ajax.request({ 
									url : '<?php echo url_for('userrolemanagement/DeleteRole')?>/deleteid/' + deleteid + '/updateid/' + updateid, 
									success: function(objServerResponse){
										cf.UserRoleGrid.theUserRoleStore.reload();
										if(cf.UserGrid.theUserStoreIsInitialized == true) {
											cf.UserGrid.theUserStore.reload();
										}
										Ext.Msg.minWidth = 200;
										Ext.MessageBox.alert('<?php echo __('OK',null,'userrolemanagement'); ?>', objServerResponse.responseText + ' <?php echo __('profiles changed',null,'userrolemanagement'); ?>');
									}
								});
								cf.DeleteRoleWindow.theRoleDeleteWindow.hide();
								cf.DeleteRoleWindow.theRoleDeleteWindow.destroy();
							}
						}
					},{
	                    id: 'cancelButton',
						text:'<?php echo __('Verwerfen',null,'userrolemanagement'); ?>', 
						icon: '/images/icons/cancel.png',
						handler: function () {
							cf.DeleteRoleWindow.theRoleDeleteWindow.hide();
							cf.DeleteRoleWindow.theRoleDeleteWindow.destroy();
						}
					}]
		});
	
	}






};}();