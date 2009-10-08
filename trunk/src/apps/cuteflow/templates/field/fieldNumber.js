cf.fieldNumber = function(){return {
	
	theNumberFieldset			:false,
	
	
	
	init: function () {
		this.initFieldset();
		
	},
	
	
	initFieldset: function () {
		this.theNumberFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Number settings',null,'field'); ?>',
			width: 600,
			height: 130,
			hidden: true,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 170,
			items:[{
				xtype: 'textfield',
 				id: 'fieldNumber_standard',
 				allowBlank:true,
 				fieldLabel: '<?php echo __('Default value',null,'field'); ?>',
   				width:230		
			},{
				xtype: 'combo',
				mode: 'local',
				editable:false,
 				valueField:'id',
 				id: 'fieldNumber_regularexpressioncombo_id',
 				hiddenName : 'fieldNumber_regularexpressioncombo',
 				allowBlank:true,
				displayField:'text',
				triggerAction: 'all',
				value: 'EMPTY',
				foreSelection: false,
   				fieldLabel: '<?php echo __('Select regular Expression',null,'field'); ?>',
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[['EMPTY','<?php echo __('define own regular expression',null,'field'); ?>'],['NORESTRICTION', '<?php echo __('no restriction',null,'field'); ?>'],['2', '<?php echo __('positive numbers only',null,'field'); ?>'],['3', '<?php echo __('negative numbers only',null,'field'); ?>']]
				}),
   				width:230,
				listeners: {
					select: {
						fn:function(combo, value) {
							if(combo.getValue() == 'EMPTY') {
								Ext.getCmp('fieldNumber_regularexpression').setValue();
								Ext.getCmp('fieldNumber_regularexpression').setDisabled(false);
							}
							else if(combo.getValue() == 'NORESTRICTION') {
								Ext.getCmp('fieldNumber_regularexpression').setValue();
								Ext.getCmp('fieldNumber_regularexpression').setDisabled(true);
							}
							else {
								Ext.getCmp('fieldNumber_regularexpression').setValue(combo.getValue());
								Ext.getCmp('fieldNumber_regularexpression').setDisabled(false);
							}
						}
					}
				}		
			},{
				xtype: 'textfield',
 				id: 'fieldNumber_regularexpression',
 				allowBlank:true,
				disabled: false,
 				fieldLabel: '<?php echo __('Regular expression',null,'field'); ?>',
   				width:230
			}]
		});
		
	},
	/** nothing to check at the moment **/
	checkBeforeSubmit: function() {
		if(Ext.getCmp('fieldNumber_regularexpressioncombo_id').getValue() == 'NORESTRICTION') {
			// check nach größer und kleiner regEx
			// speicher regulären ausdruck der größer und kleiner prüft
		}
		else if (Ext.getCmp('fieldNumber_regularexpressioncombo_id').getValue() == 'EMPTY' && Ext.getCmp('fieldNumber_regularexpression').getValue() == '') {
			// speicher regulären ausdruck der größer und kleiner prüft
		}
		else {
			// prüfe eingabe mit regulärem ausdruck ab
			// speicher den regulären ausdruck aus der combo ab
			
			// zahlen größer > ^\d*$
		}
		
		
		return true;
	}
	
	
	
	
	
};}();