cf.fieldNumber = function(){return {
	
	theNumberFieldset			:false,
	
	
	
	init: function () {
		this.initFieldset();
		
	},
	
	
	initFieldset: function () {
		this.theNumberFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Number settings',null,'field'); ?>',
			width: 600,
			height: 100,
			hidden: true,
			style: 'margin-top:20px;margin-left:5px;margin-right:5px;',
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
				editable:true,
 				valueField:'id',
 				id: 'fieldNumber_regularexpression_id',
 				hiddenName : 'fieldNumber_regularexpression',
 				allowBlank:true,
				displayField:'text',
				triggerAction: 'all',
				emptyText:'<?php echo __('Input regular Expression or select one',null,'field'); ?>',
				foreSelection: false,
   				fieldLabel: '<?php echo __('Regular Expression',null,'field'); ?>',
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[['CLEAR','input regular expression'],['CLEAR','-----------'],['', 'no restriction'],['', 'only positive numbers'],['', 'only negative numbers']]
				}),
   				width:230,
				listeners: {
						select: {
							fn:function(combo, value) {
								if(combo.getValue() == 'CLEAR') {
									Ext.getCmp('fieldNumber_regularexpression_id').setValue();
								}
							}
						}
					}		
			}]
		});
		
	}
	
	
	
	
	
};}();