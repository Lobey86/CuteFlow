cf.menueSettingGroupWindow = function(){return {
	
	theMenueSettingGroupWindow 			:false,
	theFormPanel						:false,
	
	
	init: function (id) {
		
		
		cf.menueSettingGroupGrid.init(id);
		
		this.initWindow(id);
		this.initFormPanel();
		this.theMenueSettingGroupWindow.add(this.theFormPanel);
		this.theMenueSettingGroupWindow.add(cf.menueSettingGroupGrid.theGroupGrid);
		this.theMenueSettingGroupWindow.show();	
	},
	
	
	initFormPanel: function () {
		this.theFormPanel = new Ext.FormPanel({
		});
	},
	
	initWindow: function (id) {
		this.theMenueSettingGroupWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			width: 563,
			height: 550,
			autoScroll: true,
			title: 'Edit',
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: false,
	        buttonAlign: 'center',
			close : function(){
				cf.menueSettingGroupWindow.theMenueSettingGroupWindow.hide();
				cf.menueSettingGroupWindow.theMenueSettingGroupWindow.destroy();
			},
			buttons:[{
				text:'Speichern', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.menueSettingGroupCRUD.saveGroupOrder(id);
				}
			},{
				text:'Zu', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.menueSettingGroupWindow.theMenueSettingGroupWindow.hide();
					cf.menueSettingGroupWindow.theMenueSettingGroupWindow.destroy();
				}
			}]
			
		});
		
	}
	
	
	
};}();