/**
* Class creates a window, where all textfields and comobox will be rendered. The WIndow also includes
* the login and close button and handles login functionality
* 
*/
cf.Window = function(){return {
	theWindow : false,

	/** Function inits window, with 2 buttons and handles login functionality **/
	init: function () {
		this.theWindow = new Ext.Window({
		width:370,
		height:175,
		frame: true,
		title: '<div style="float:left;"><img src="/images/icons/key.png" /></div><div>&nbsp;&nbsp;CuteFlow - <?php echo __('Login',null,'login') ?></div>',
		closable: false,
		draggable: true,
		border: false,
		buttonAlign: 'center', 
                buttons: [{ 
					text:'<?php echo __('Login',null,'login'); ?>', 
					icon: '/images/icons/lock.png',
					id: 'loginButton',
					handler: function () {
						if(cf.Textfield.theUsernameField.getValue() != '' && cf.Textfield.theUserpasswordField.getValue() != '') {
							cf.Textfield.thePanel.getForm().submit({
								url: '<?php echo build_dynamic_javascript_url('login/DoLogin')?>',
								method: 'POST',
								success: function(form, objServerResponse) {
									if (objServerResponse.result.value == 1) {
										window.location.href = '<?php echo build_dynamic_javascript_url('layout/Index');?>'
									}
									else {
										Ext.MessageBox.alert(objServerResponse.result.title, objServerResponse.result.text);
										cf.Textfield.theUsernameField.setValue();
										cf.Textfield.theUserpasswordField.setValue();
									}
								}
							});
						}
					}
                },{ 
                    text: '<?php echo __('Close',null,'login'); ?>', 
					icon: '/images/icons/cancel.png',
					id: 'cancelButton',
                    handler: function(){ 
                    	cf.Window.theWindow.hide(); 
                    } 
                }] 
		});
	}
};}();