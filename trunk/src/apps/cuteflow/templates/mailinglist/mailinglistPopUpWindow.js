/** init popupwindow to create/update a template **/
cf.mailinglistPopUpWindow = function(){return {
	
	theMailinglistPopUpWindow		:false,
	theLoadingMask					:false,
	theTabPanel						:false,
	theLoadingMaskShowTime			:false,

	/**
	* calls all necessary functions, to create a new form
	*@param int id, id of the record is empty, only set in editmode
	*/
	initNewMailinglist: function (id) {
		/*this.theLoadingMask = new Ext.LoadMask(Ext.getBody(), {msg:'<?php echo __('Loading Data...',null,'usermanagement'); ?>'});					
		this.theLoadingMask.show();
		this.theLoadingMaskShowTime = 2000;*/
		cf.mailinglistFirstTab.init('<?php echo build_dynamic_javascript_url('systemsetting/LoadAuthorization')?>',id);
		this.initTabPanel();
		this.initWindow('','<?php echo __('Create new Mailing list',null,'mailinglist'); ?>');
		this.theTabPanel.add(cf.mailinglistFirstTab.theFormPanel);
		this.theMailinglistPopUpWindow.add(this.theTabPanel);
		this.theMailinglistPopUpWindow.doLayout();
		this.theMailinglistPopUpWindow.show();
		
	},

	
	/**
	* calls all necessary functions, to edit a  form
	*@param int id, id is set
	*/
	initEdit: function (id) {
		cf.mailinglistFirstTab.init('<?php echo build_dynamic_javascript_url('mailinglist/LoadAuthorization')?>/id/'+id,id);
		this.initTabPanel();
		this.initWindow(id,'<?php echo __('Edit existing Mailing list',null,'mailinglist'); ?>');
		this.theTabPanel.add(cf.mailinglistFirstTab.theFormPanel);
		this.theMailinglistPopUpWindow.add(this.theTabPanel);
		this.theMailinglistPopUpWindow.doLayout();
		this.theMailinglistPopUpWindow.show();
		this.addData(id);
	},
	
	/**
	* Load the data when in editmode
	*
	*@param int id, id of the record to edit
	*
	*/
	addData: function (id) {
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('mailinglist/LoadSingleMailinglist')?>/id/' + id, 
			success: function(objServerResponse){
				theJsonTreeData = Ext.util.JSON.decode(objServerResponse.responseText);
				Ext.getCmp('mailinglistFirstTab_documenttemplate_id').setValue(theJsonTreeData.result.formtemplate_id);
				Ext.getCmp('mailinglistFirstTab_nametextfield').setValue(theJsonTreeData.result.name);
				Ext.getCmp('mailinglistFirstTab_documenttemplate_id').setDisabled(true);
				cf.mailinglistSecondTab.init(Ext.getCmp('mailinglistFirstTab_documenttemplate_id').getRawValue(),Ext.getCmp('mailinglistFirstTab_documenttemplate_id').getValue(), true,'<?php echo build_dynamic_javascript_url('mailinglist/LoadFormWithUser')?>', id);
				cf.mailinglistPopUpWindow.theTabPanel.add(cf.mailinglistSecondTab.thePanel);
			}
		});	
	},
	
	/**
	* init the popupwindow
	*
	* @param int id, id is set if in edit mode
	* @param string title, title of window
	*/
	initWindow: function (id, title) {
		this.theMailinglistPopUpWindow = new Ext.Window({
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
				text:'<?php echo __('Store',null,'mailinglist'); ?>', 
				icon: '/images/icons/accept.png',
				handler: function () {
					cf.mailinglistCRUD.initSave(id);
				}
			},{
				text:'<?php echo __('Close',null,'mailinglist'); ?>', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.mailinglistPopUpWindow.theMailinglistPopUpWindow.hide();
					cf.mailinglistPopUpWindow.theMailinglistPopUpWindow.destroy();
				}
			}]
		});
		this.theMailinglistPopUpWindow.on('close', function() {
			cf.mailinglistPopUpWindow.theMailinglistPopUpWindow.hide();
			cf.mailinglistPopUpWindow.theMailinglistPopUpWindow.destroy();
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