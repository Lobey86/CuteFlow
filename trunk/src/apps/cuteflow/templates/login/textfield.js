cf.Textfield = function(){return {
	
	theUsernameField   		: false,
	theUserpasswordField	: false,
	theHiddenField			: false,
	thePanel				: false,
	
	init: function(){
		this.initUsernameField();
		this.initUserpasswordField();
		this.initHiddenField();
		this.initPanel();
	}, 
	
	initUsernameField: function() {
		this.theUsernameField = new Ext.form.TextField({
			id: 'username',
			fieldLabel: '<?php echo __('Username',null,'login'); ?>',
			allowBlank: false,
			width: 225
		});
	},
	
	initHiddenField: function () {
		this.theHiddenField =  new Ext.form.Hidden({
			id: 'hiddenfield',
			allowBlank: true,
			value: '<?php echo $sf_user->getCulture()?>',
			width: 225
		});
	},
	
	initUserpasswordField: function() {
		this.theUserpasswordField = new Ext.form.TextField({
			id:'userpassword',
			fieldLabel: '<?php echo __('Password',null,'login'); ?>',
			allowBlank: false,
			inputType: 'password',
			width: 225
		});
	},
	
	initPanel: function() {
		this.thePanel = new Ext.Panel({
			plain: true,
			frame: true,
			height: 104,
			buttonAlign: 'center',
			style: 'border:none;',
		    layout: 'form'
		});
	}
	
};}();