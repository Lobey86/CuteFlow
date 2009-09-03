/**
* Class builds all all necessary Textfields for the Loginpage
*
*/


cf.Textfield = function(){return {
	
	theUsernameField   		: false,
	theUserpasswordField	: false,
	theHiddenField			: false,
	thePanel				: false,
	
	/** Functions calls all necessary functions to init the login window **/
	init: function(){
		this.initUsernameField();
		this.initUserpasswordField();
		this.initHiddenField();
		this.initPanel();
	}, 
	
	/** Textfield **/
	initUsernameField: function() {
		this.theUsernameField = new Ext.form.TextField({
			id: 'username',
			fieldLabel: '<?php echo __('Username',null,'login'); ?>',
			allowBlank: false,
			width: 225
		});
	},
	/** Hiddenfield **/
	initHiddenField: function () {
		this.theHiddenField =  new Ext.form.Hidden({
			id: 'hiddenfield',
			allowBlank: true,
			value: '<?php echo $sf_user->getCulture()?>',
			width: 225
		});
	},
	
	/** Textfield **/
	initUserpasswordField: function() {
		this.theUserpasswordField = new Ext.form.TextField({
			id:'userpassword',
			fieldLabel: '<?php echo __('Password',null,'login'); ?>',
			allowBlank: false,
			inputType: 'password',
			width: 225
		});
	},
	
	/** Panel, where all textfields and combo will be added **/
	initPanel: function() {
		this.thePanel = new Ext.Panel({
			plain: true,
			frame: true,
			height: 104,
			buttonAlign: 'center',
			style: 'border:none;',
			border: 'none',
		    layout: 'form'
		});
	}
	
};}();