cf.fieldTextarea = function(){return {
	
	theTextarea				:false,
	theHTMLarea				:false,
	theCombo				:false,
	theTextareaFieldset		:false,
	
	
	
	init: function () {
		this.initTextarea();
		this.initHTMLarea();
		this.initCombobox();
		this.initFieldset();
		this.theTextareaFieldset.add(this.theCombo);
		this.theTextareaFieldset.add(this.theTextarea);
		this.theTextareaFieldset.add(this.theHTMLarea);
	},
	
	
	initFieldset: function () {
		this.theTextareaFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('Textarea settings',null,'field'); ?>',
			width: 600,
			height: 220,
			hidden: true,
			style: 'margin-top:20px;margin-left:5px;',
			labelWidth: 170
		});
	},
	
	initTextarea: function () {
		this.theTextarea = new Ext.form.TextArea({
			fieldLabel: '<?php echo __('Input default Text',null,'field'); ?>',
			width: 350,
			height: 150
		});
	},
	
	initHTMLarea: function () {
		this.theHTMLarea = new Ext.form.HtmlEditor({
			fieldLabel: '<?php echo __('Input default HTML-Text',null,'field'); ?>',
			width: 350,
			hidden:true,
			height: 150
		});
	},
	
	
	initCombobox: function () {
		this.theCombo = new Ext.form.ComboBox ({
			fieldLabel: '<?php echo __('Content Type',null,'field'); ?>',
			width: 300,
			editable:false,
			triggerAction: 'all',
			foreSelection: true,
			mode: 'local',
			value: 'plain',
			store: new Ext.data.SimpleStore({
				 fields:['id','text'],
   				 data:[['plain', '<?php echo __('Plain',null,'field'); ?>'],['html', '<?php echo __('HTML',null,'field'); ?>']]
				}),
			valueField:'id',
			displayField:'text',
			width:350,
			listeners: {
	    		select: {
	    			fn:function(combo, value) {
	    				if(combo.getValue() == 'plain'){
	    					var value = cf.fieldTextarea.theHTMLarea.getValue();
	    					cf.fieldTextarea.theHTMLarea.setVisible(false);
	    					cf.fieldTextarea.theTextarea.setVisible(true);
	    					cf.fieldTextarea.theTextarea.setValue(value);
	    				}
	    				else {
	    					var value = cf.fieldTextarea.theTextarea.getValue();
	    					cf.fieldTextarea.theHTMLarea.setVisible(true);
	    					cf.fieldTextarea.theTextarea.setVisible(false);
	    					cf.fieldTextarea.theHTMLarea.setValue(value);
	    				}
	    			}
	    		}
	    	}
		});
	}
	
	
	
	
	
	
};}();