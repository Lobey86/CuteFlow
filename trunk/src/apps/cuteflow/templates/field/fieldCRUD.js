cf.fieldCRUD = function(){return {


	initDelete:function (id) {
		Ext.Ajax.request({  
			url : '<?php echo build_dynamic_javascript_url('field/DeleteField')?>/id/' + id, 
			success: function(objServerResponse){
				cf.fieldPanelGrid.theFieldStore.reload();
				Ext.Msg.minWidth = 200;
				Ext.MessageBox.alert('<?php echo __('OK',null,'field'); ?>', '<?php echo __('Delete successful',null,'field'); ?>');
			}
		});  	
	},
	
	initSave: function (id) {
		if(id != '') {
			var url = '<?php echo build_dynamic_javascript_url('field/UpdateField')?>/id/' + id;
		}
		else {
			var url = '<?php echo build_dynamic_javascript_url('field/SaveField')?>';
		}
		
		cf.createFileWindow.theFormPanel.getForm().submit({
			url: url,
			method: 'POST',
			success: function(objServerResponse){
				
			}
		});
	}


};}();