cf.startTab = function(){return {
	
	thePanel			:false,
	
	init: function () {
		this.initPanel();
		
	},
	
	initPanel: function () {
		this.thePanel = new Ext.Panel({
			modal: false,
			closable: false,
			title: '<?php echo __('Welcome to CuteFlow installer',null,'installer'); ?>',
			layout: 'form',
			width: 750,
			height: 490,
			autoScroll: true,
			shadow: false,
			minimizable: false,
			autoScroll: false,
			draggable: false,
			resizable: false,
			plain: false,
			html: 'INSTALLER UND SO'
		});
		
	}
	
	
};}();