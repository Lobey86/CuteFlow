cf.fieldTextfield = function(){return {
	
	theTextfieldFieldset			:false,
	
	init: function () {
		this.initFieldset();
	},
	
	initFieldset: function () {
		this.theTextfieldFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Textfield settings',null,'field'); ?>',
			width: 600,
			height: 100,
			style: 'margin-top:20px;margin-left:5px;margin-right:5px;',
			labelWidth: 170,
			items:[{
				xtype: 'combo',
				mode: 'local',
				editable:true,
 				valueField:'id',
 				id: 'fieldTextfield_standard_id',
 				hiddenName : 'fieldTextfield_standard',
 				allowBlank:true,
				displayField:'text',
				triggerAction: 'all',
				emptyText:'<?php echo __('Input default value or select one',null,'field'); ?>',
				foreSelection: false,
   				fieldLabel: '<?php echo __('Default value',null,'field'); ?>',
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[['', '{%DATE_SENDING%}'],['', '{%TIME%}'],['', '{%CIRCULATION_TITLE%}'],['', '{%CIRCULATION_ID%}']]
				}),
   				width:230		
			},{
				xtype: 'textfield',
				allowBlank:true,
				id: 'fieldTextfield_regularexpression',
   				fieldLabel: '<?php echo __('Regular expression',null,'field'); ?>',
   				width:230	
			}]
		});
		
	}
	
	
	
	
	
};}();