cf.fieldDate = function(){return {
	
	theDateFieldset			:false,

	
	
	
	init: function () {
		this.initFieldset();
	},
	
	
	
	initFieldset: function () {
		this.theDateFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Textfield settings',null,'field'); ?>',
			width: 600,
			height: 130,
			hidden: true,
			style: 'margin-top:20px;margin-left:5px;margin-right:5px;',
			labelWidth: 170,
			items:[{
				xtype: 'combo',
				mode: 'local',
				editable:false,
				value: 'dd-mm-yyyy',
 				valueField:'id',
 				id: 'fieldDate_format_id',
 				hiddenName : 'fieldDate_format',
 				allowBlank:true,
				displayField:'text',
				triggerAction: 'all',
				emptyText:'<?php echo __('Input default value or select one',null,'field'); ?>',
				foreSelection: false,
   				fieldLabel: '<?php echo __('Default value',null,'field'); ?>',
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[['dd/mm/yyyy', 'dd-mm-yyyy'],['mm/dd/yyyy', 'dd-mm-yyyy'],['yyyy/mm/dd', 'yyyy-mm-dd']]
				}),
   				width:230		
			},{
				xtype: 'datefield',
				allowBlank:true,
				id: 'fieldDate_date',
   				fieldLabel: '<?php echo __('Default value',null,'field'); ?>',
   				width:230	
			},{
				xtype: 'textfield',
				allowBlank:true,
				id: 'fieldDate_regularexpression',
   				fieldLabel: '<?php echo __('Regular expression',null,'field'); ?>',
   				width:230	
			}]
		});		
	}
	
	
	
	
	
	
};}();