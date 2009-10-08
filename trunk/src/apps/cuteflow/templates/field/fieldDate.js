cf.fieldDate = function(){return {
	
	theDateFieldset			:false,
	theDDMMYYYY						:'^[0-9]{2}[\-]{1}[0-9]{2}[\-]{1}[0-9]{4}$',
	theMMDDYYYY						:'^[0-9]{2}[\-]{1}[0-9]{2}[\-]{1}[0-9]{4}$',
	theYYYYMMDD						:'^[0-9]{4}[\-]{1}[0-9]{2}[\-]{1}[0-9]{2}$',
	theRegExStore					:'^[0-9]{2}[\-]{1}[0-9]{2}[\-]{1}[0-9]{4}$',

	
	
	
	init: function () {
		this.initFieldset();
	},
	
	
	
	initFieldset: function () {
		this.theDateFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Textfield settings',null,'field'); ?>',
			width: 600,
			height: 130,
			hidden: true,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 170,
			items:[{
				xtype: 'combo',
				mode: 'local',
				editable:false,
				value: 'd-m-Y',
 				valueField:'id',
 				id: 'fieldDate_format_id',
 				hiddenName : 'fieldDate_format',
 				allowBlank:true,
				displayField:'text',
				triggerAction: 'all',
				emptyText:'<?php echo __('Input default value or select one',null,'field'); ?>',
				foreSelection: false,
   				fieldLabel: '<?php echo __('Date format',null,'field'); ?>',
				store: new Ext.data.SimpleStore({
					 fields:['id','text'],
       				 data:[['d-m-Y', 'dd-mm-yyyy'],['m-d-Y', 'mm-dd-yyyy'],['Y-m-d', 'yyyy-mm-dd']]
				}),
   				width:280,
				listeners: {
					select: {
						fn:function(combo, value) {
							cf.fieldDate.buildDate(combo,Ext.getCmp('fieldDate_date'));
							switch (combo.getValue()) {
								case "d-m-Y":
									Ext.getCmp('fieldDate_regularexpression').setValue(cf.fieldDate.theDDMMYYYY);
									cf.fieldDate.theRegExStore = cf.fieldDate.theDDMMYYYY;
									break;
								case "m-d-Y":
									Ext.getCmp('fieldDate_regularexpression').setValue(cf.fieldDate.theMMDDYYYY);
									cf.fieldDate.theRegExStore = cf.fieldDate.theMMDDYYYY;
									break;
								case "Y-m-d":
									Ext.getCmp('fieldDate_regularexpression').setValue(cf.fieldDate.theYYYYMMDD);
									cf.fieldDate.theRegExStore = cf.fieldDate.theYYYYMMDD;
									break;
							}
						}
					}
				}					
			},{
				xtype: 'datefield',
				allowBlank:true,
				id: 'fieldDate_date',
				format:'d-m-Y',
   				fieldLabel: '<?php echo __('Default value',null,'field'); ?>',
   				width:280	
			},{
				xtype: 'textfield',
				allowBlank:true,
				id: 'fieldDate_regularexpression',
   				fieldLabel: '<?php echo __('Regular expression',null,'field'); ?>',
   				value: cf.fieldDate.theDDMMYYYY,
   				width:280	
			}]
		});		
	},
	
	buildDate: function (combo, datefield) {
		var currentDate = datefield.getValue();
		datefield.format = combo.getValue();
		datefield.setValue(currentDate);	
	},
	
	/**checks dat**/
	checkBeforeSubmit: function() {
		var input = Ext.getCmp('fieldDate_date').getRawValue();
		var regex = Ext.getCmp('fieldDate_regularexpression').getValue();
		var regexStore = cf.fieldDate.theRegExStore;
		if(regex == '') {
			if(input == '') {
				return true;
			}
			else {
				var regObject = new RegExp(regexStore,"m");
				if(regObject.test(input) == true) {
					Ext.getCmp('fieldDate_regularexpression').setValue(regexStore);
					return true;
				}
				else {
					Ext.Msg.minWidth = 200;
					Ext.MessageBox.alert('<?php echo __('Error',null,'field'); ?>', '<?php echo __('Input value is no valid date format',null,'field'); ?>');
					return false;
				}
			}
		}
		else {
			if(input == '') {
				return true;
			}
			else {
				var regObject = new RegExp(regex,"m");
				if(regObject.test(input) == true) {
					return true;
				}
				else {
					Ext.Msg.minWidth = 200;
					Ext.MessageBox.alert('<?php echo __('Error',null,'field'); ?>', '<?php echo __('Input value is no valid date format',null,'field'); ?>');
					return false;
				}
			}
			
		}
	}

};}();