/** init fieldset for file **/
cf.createFormWindow = function(){return {
	
	theFormPopUpWindow				:false,
	theLoadingMask					:false,
	theTabPanel						:false,
	theFirstTab						:false,
	theFirstTabFieldset				:false,

	/**
	* calls all necessary functions, to create a new form
	*@param int id, id of the record is set when in edit mode
	*/
	initNewForm: function (id) {
		this.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Loading Data...',null,'form'); ?>'});					
		this.theLoadingMask.show();
		this.initFirstTabFieldset();
		this.initFirstTab();
		cf.formPopUpWindowSecondTab.init();
		this.theFirstTab.add(this.theFirstTabFieldset);
		this.initTabPanel();
		this.theTabPanel.add(this.theFirstTab);
		this.theTabPanel.add(cf.formPopUpWindowSecondTab.theSecondTab);
		this.initWindow(id, '<?php echo __('Create new form',null,'form'); ?>');
		this.theFormPopUpWindow.add(this.theTabPanel);
		this.theFormPopUpWindow.show();
		this.theTabPanel.setActiveTab(1);
		cf.formPopUpWindowSecondTab.addGrid();
	},
	
	/** init first tab to enter description of the template **/
	initFirstTab: function () {
		this.theFirstTab = new Ext.Panel({
			title: '<?php echo __('Description',null,'field'); ?>',
			frame:true,
			height: cf.Layout.theRegionWest.getHeight() - 134
		});
	},

	/** init fieldset for first tab, with description **/
	initFirstTabFieldset: function () {
		this.theFirstTabFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Set name of Document template',null,'form'); ?>',
			width: 600,
			height: 100,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 170,
			items: [{
				xtype: 'textfield',
				id:'createFileWindow_fieldname',
				allowBlank: false,
				fieldLabel: '<?php echo __('Template name',null,'form'); ?>',
				width:220
			}]
		});
	
	},
	
	/**
	* init the popupwindow
	*
	* @param int id, id is set if in edit mode
	* @param string title, title of window
	*/
	initWindow: function (id, title) {
		this.theFormPopUpWindow = new Ext.Window({
			modal: true,
			closable: true,
			modal: true,
			height: cf.Layout.theRegionWest.getHeight() - 30,
			width: 800,
			autoScroll: true,
			shadow: false,
			minimizable: false,
			draggable: true,
			resizable: true,
	        plain: true,
			title: title,
	        buttonAlign: 'center',
			buttons:[{
				text:'<?php echo __('Store',null,'myprofile'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					//cf.fieldCRUD.initSave(id,cf.createFileWindow.theCurrentObject);
					alert("fewfew");
				}
			},{
				text:'<?php echo __('Close',null,'usermanagement'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.createFormWindow.theFormPopUpWindow.hide();
					cf.createFormWindow.theFormPopUpWindow.destroy();
				}
			}]
		});
		this.theFormPopUpWindow.on('close', function() {
			cf.createFormWindow.theFormPopUpWindow.hide();
			cf.createFormWindow.theFormPopUpWindow.destroy();
		});
	},
	
	/** init tabpanel **/
	initTabPanel: function () {
		this.theTabPanel = new Ext.TabPanel({
			activeTab: 0,
			enableTabScroll:true,
			border: false,
			deferredRender:true,
			frame: true,
			layoutOnTabChange: true,
			style: 'margin-top:5px;',
			plain: false,
			closable:false
		});	
	}
	
};}();