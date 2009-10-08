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
			style: 'margin-top:20px;margin-left:5px;',
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
       				 data:[['EMPTY', '<font color="#808080"><?php echo __('Input default value',null,'field'); ?></font>'],['{%DATE_SENDING%}', '{%DATE_SENDING%}'],['{%TIME%}', '{%TIME%}'],['{%CIRCULATION_TITLE%}', '{%CIRCULATION_TITLE%}'],['{%CIRCULATION_ID%}', '{%CIRCULATION_ID%}']]
				}),
   				width:230,
				listeners: {
					select: {
						fn:function(combo) {
							if (combo.getValue() == 'EMPTY') {
								combo.setValue();
							}
						}
					}
				}
						
			},{
				xtype: 'textfield',
				allowBlank:true,
				id: 'fieldTextfield_regularexpression',
   				fieldLabel: '<?php echo __('Regular expression',null,'field'); ?>',
   				width:230	
			}]
		});
		
	},
	
	/** nothing to check at the moment **/
	checkBeforeSubmit: function() {
		return true;
	}
	
	
	
	
	
};}();