cf.fieldFile = function(){return {
	
	theFileFieldset			:false,

	
	
	
	init: function () {
		this.initFieldset();
	},
	
	
	
	initFieldset: function () {
		this.theFileFieldset = new Ext.form.FieldSet({
			title: '<table><tr><td><img src="/images/icons/information.png"  ext:qtip=\"During the Circulation you can choose and upload a file\" ext:qwidth=\"300\"/></td><td>&nbsp;&nbsp;<?php echo __('File settings',null,'file'); ?></td></tr></table>',
			width: 600,
			height: 80,
			hidden: true,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 170,
			items:[{
				xtype: 'textfield',
				allowBlank:true,
				id: 'fieldFile_regularexpression',
   				fieldLabel: '<?php echo __('Regular expression',null,'field'); ?>',
   				width:230		
			}]
		});
	}
	
	
	
	
	
	
	
};}();