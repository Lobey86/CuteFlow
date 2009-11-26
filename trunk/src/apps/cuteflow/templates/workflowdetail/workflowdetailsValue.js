cf.workflowdetailsValue = function(){return {
	
	theFieldset					:false,
	
	init:function(data) {
		this.initFieldset();
	},
	
	
	
	initFieldset: function () {
		this.theFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Workflow values',null,'workflowmanagement'); ?>',
			allowBlank: false,
			autoScroll: true,
			style: 'margin-top:5px;margin-left:10px;',
			width: cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 100,
			height: 'auto'
		});
	}
	
	
	
	
	
	
	
};}();