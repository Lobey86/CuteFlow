cf.mainWindow = function(){return {
	
	
	thePanel				:false,
	theWindow				:false,
	theLoadingMask			:false,
	
	/** Calls all necessary function to display the login form **/
	init: function(){
		cf.mainWindow.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Loading Data...',null,'installer'); ?>'});					
		cf.mainWindow.theLoadingMask.show();
		cf.installerSettingsTab.init();
		cf.firstTab.init();
		cf.secondTab.init();
		this.initWindow();
		this.initPanel();
		this.initTabPanel();
		this.theTabPanel.add(cf.installerSettingsTab.thePanel);
		this.theTabPanel.add(cf.firstTab.thePanel);
		this.theTabPanel.add(cf.secondTab.thePanel);
		this.thePanel.add(this.theTabPanel);
		this.theWindow.add(this.thePanel);
		this.theTabPanel.setActiveTab(2);
		cf.mainWindow.setActiveTab.defer(500,this, [1]);
		cf.mainWindow.setActiveTab.defer(500,this, [0]);			
	},
	
	setActiveTab: function (panel) {
		cf.mainWindow.theTabPanel.setActiveTab(panel);
		cf.mainWindow.theLoadingMask.hide();
	},
	
	initTabPanel: function (){
		this.theTabPanel = new Ext.TabPanel({
			activeTab: 0,
			enableTabScroll:true,
			border: false,
			deferredRender:true,
			frame: true,
			layoutOnTabChange: true,
			style: 'margin-top:5px;',
			plain: true,
			closable:false
		});		
	},
	
	initWindow: function () {
		

		this.theWindow = new Ext.Window({
			modal: true,
			closable: false,
			modal: true,
			width: 800,
			height: 600,
			autoScroll: true,
			title: '<?php echo __('CuteFlow Installer',null,'installer'); ?>',
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: true,
	        buttonAlign: 'center',
			buttons:[{
				text:'<?php echo __('Store',null,'installer'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.mainWindow.thePanel.getForm().submit({
						url: '<?php echo build_dynamic_javascript_url('installer/SaveData')?>',
						method: 'POST',
						waitMsg: '<?php echo __('Building System',null,'installer'); ?>',
						success: function(objServerResponse){
							Ext.Msg.minWidth = 200;
							Ext.MessageBox.alert('<?php echo __('CuteFlow installed',null,'installer'); ?>','<?php echo __('CuteFlow installed',null,'installer'); ?>');
							var url = Ext.get('url_login').dom.value;
							window.location.href = url;
						},
						failure: function (objServerResponse) {
							Ext.Msg.minWidth = 200;
							Ext.MessageBox.alert('<?php echo __('CuteFlow installed',null,'installer'); ?>','<?php echo __('CuteFlow installed',null,'installer'); ?>');
							var url = Ext.get('url_login').dom.value;
							window.location.href = url;
							
						}
					});
				}
			},{
				text:'<?php echo __('Close',null,'installer'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.mainWindow.theWindow.hide();
					cf.mainWindow.theWindow.destroy();
				}
			}]
		});
		
		
	},
	
	initPanel: function () {
		this.thePanel = new Ext.FormPanel({
			modal: false,
			closable: false,
			modal: true,
			bodyStyle: 'background-color: #CCD8E7;',
			layout: 'form',
			autoScroll: true,
			shadow: false,
			minimizable: false,
			autoScroll: false,
			draggable: false,
			resizable: false,
			plain: true
		});
		
		
	}
	
	
	
	
};}();