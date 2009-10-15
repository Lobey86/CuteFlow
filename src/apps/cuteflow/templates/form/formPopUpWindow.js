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
		this.initFirstTabFieldset();
		this.initFirstTab();
		cf.formPopUpWindowSecondTab.init();
		this.theFirstTab.add(this.theFirstTabFieldset);
		this.initTabPanel();
		this.theTabPanel.add(this.theFirstTab);
		this.theTabPanel.add(cf.formPopUpWindowSecondTab.theColumnPanel);
		this.initWindow(id, '<?php echo __('Create new form',null,'form'); ?>');
		this.theFormPopUpWindow.add(this.theTabPanel);
		this.theFormPopUpWindow.show();
		this.theTabPanel.setActiveTab(1);
		cf.formPopUpWindowSecondTab.addGrid('',0,-1);
		
	},
	hideLoadingMask: function (mask) {
		mask.hide();
	},
	
	
	initEditForm: function (id) {
		this.initFirstTabFieldset();
		this.initFirstTab();
		cf.formPopUpWindowSecondTab.init();
		this.theFirstTab.add(this.theFirstTabFieldset);
		this.initTabPanel();
		this.theTabPanel.add(this.theFirstTab);
		this.theTabPanel.add(cf.formPopUpWindowSecondTab.theColumnPanel);
		this.initWindow(id, '<?php echo __('Edit form',null,'form'); ?>');
		this.theFormPopUpWindow.add(this.theTabPanel);
		this.theFormPopUpWindow.show();
		this.theTabPanel.setActiveTab(1);
		this.addData(id);
		
	},
	
	
	addData: function (id) {
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('form/LoadSingleForm')?>/id/' + id, 
			success: function(objServerResponse){
				theJsonTreeData = Ext.util.JSON.decode(objServerResponse.responseText);
				Ext.getCmp('createFileWindow_fieldname').setValue(theJsonTreeData.result.title);
				var data = theJsonTreeData.result;
				for(var a=0;a<data.slot.length;a++) {
					var checked = data.slot[a].receiver == 0 ? 0 : 1;
					cf.formPopUpWindowSecondTab.addGrid(data.slot[a].title,checked, data.slot[a].field);
				}
				//cf.createFormWindow.theLoadingMask.hide();
			}
		});	
	},
	
	
	
	
	
	/** init first tab to enter description of the template **/
	initFirstTab: function () {
		this.theFirstTab = new Ext.FormPanel({
			title: '<?php echo __('Description',null,'field'); ?>',
			frame:true,
			height: cf.Layout.theRegionWest.getHeight() - 148
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
				allowBlank: true,
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
			height: cf.Layout.theRegionWest.getHeight() - 40,
			width: 820,
			autoScroll: false,
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
					cf.formCRUD.initSave(id);
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