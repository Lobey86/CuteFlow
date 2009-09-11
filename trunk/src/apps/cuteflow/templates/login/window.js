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
							Ext.Ajax.request({
								url: '<?php echo url_for('login/DoLogin')?>/username/' + cf.Textfield.theUsernameField.getValue() + '/password/' + cf.Textfield.theUserpasswordField.getValue() + '/language/' + cf.Textfield.theHiddenField.getValue(),						
								success: function(objServerResponse){  
									if(objServerResponse.responseText == 1) { // login TRUE
										var url = Ext.get('url').dom.value;
										window.location.href = url;
									}
									else { // login FALSE
										var ServerResult = Ext.util.JSON.decode(objServerResponse.responseText);
										Ext.MessageBox.alert(ServerResult.result.errorTitle, ServerResult.result.errorMessage);
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