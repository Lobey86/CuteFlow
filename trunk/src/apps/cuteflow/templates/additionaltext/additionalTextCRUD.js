/** CRUD functions for Additional Text **/
cf.additionalTextCRUD = function(){return {
	
	/**
	* save or update function for an additional text
	*
	* @param int id, id is set, if user is in editmode of an record
	*/
	initSave: function (id) {
		if(id == '') { // new record
			this.createAdditionaltext();
		}
		else { // edit record
			this.updateAdditionaltext(id);
		}
	},
	
	/** create new additional text **/
	createAdditionaltext: function () {
		cf.additionalTextPopUpWindow.theFormPanel.getForm().submit({
			url: '<?php echo build_dynamic_javascript_url('additionaltext/SaveText')?>',
			method: 'POST',
			success: function(objServerResponse){
				cf.additionalTextGrid.theTextStore.reload();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'additionaltext'); ?>', '<?php echo __('Text saved',null,'additionaltext'); ?>');
				cf.additionalTextPopUpWindow.thePopUpWindow.hide();
				cf.additionalTextPopUpWindow.thePopUpWindow.destroy();
			}
		});
	},
	
	/**
	* update an additional text
	*
	*@param int id, id to update
	*
	*/
	updateAdditionaltext: function (id) {
		cf.additionalTextPopUpWindow.theFormPanel.getForm().submit({
			url: '<?php echo build_dynamic_javascript_url('additionaltext/UpdateText')?>/id/' + id,
			method: 'POST',
			success: function(objServerResponse){
				cf.additionalTextGrid.theTextStore.reload();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'additionaltext'); ?>', '<?php echo __('Text updated',null,'additionaltext'); ?>');
				cf.additionalTextPopUpWindow.thePopUpWindow.hide();
				cf.additionalTextPopUpWindow.thePopUpWindow.destroy();
			}
		});
		
	},
	
	/**
	* Delete an record
	* 
	* @param int id, ID of the deleted record
	*/
	initDelete: function (id) {
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('additionaltext/DeleteText')?>/id/' + id, 
			success: function(objServerResponse){  
				Ext.Msg.minWidth = 200;
				cf.additionalTextGrid.theTextStore.reload();
				Ext.MessageBox.alert('<?php echo __('OK',null,'additionaltext'); ?>', '<?php echo __('Delete successful',null,'additionaltext'); ?>');
			}
		});
	},
	
	/**
	* function copies an item 
	*
	* @param int id, id of the record to copy
	*/
	initCopy: function (id) {
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('additionaltext/CopyText')?>/id/' + id, 
			success: function(objServerResponse){  
				Ext.Msg.minWidth = 200;
				cf.additionalTextGrid.theTextStore.reload();
				Ext.MessageBox.alert('<?php echo __('OK',null,'additionaltext'); ?>', '<?php echo __('Copy successful',null,'additionaltext'); ?>');
			}
		});
	
	}
};}();