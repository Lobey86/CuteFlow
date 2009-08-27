cf.Window = function(){return {
	theWindow : false,


	init: function () {
		this.theWindow = new Ext.Window({
		width:370,
		height:175,
		plain: true,
		title: '<div style="float:left;"><img src="/images/icons/key.png" /></div><div>&nbsp;&nbsp;CuteFlow - <?php echo __('Login',null,'login') ?></div>',
		closable: false,
		draggable: false,
		plain: false,
		buttonAlign: 'center', 
                buttons: [{ 
                    id: 'loginButton',
					text:'<?php echo __('Login',null,'login'); ?>', 
					icon: '/images/icons/lock.png',
					handler: function () {
						if(cf.Textfield.theUsernameField.getValue() != '' && cf.Textfield.theUserpasswordField.getValue() != '') {
							Ext.Ajax.request({
								url: '<?php echo url_for('login/DoLogin')?>/username/' + cf.Textfield.theUsernameField.getValue() + '/password/' + cf.Textfield.theUserpasswordField.getValue() + '/language/' + cf.Textfield.theHiddenField.getValue(),						
								success: function(objServerResponse){  
									if(objServerResponse.responseText == 1) {
										var url = Ext.get('url').dom.value;
										window.location.href = url;
									}
									else {
										var ServerResult = {};
										ServerResult = JSON.parse(objServerResponse.responseText);
										Ext.MessageBox.alert(ServerResult.result.errorTitle, ServerResult.result.errorMessage);
										cf.Textfield.theUsernameField.setValue();
										cf.Textfield.theUserpasswordField.setValue();
									}
								}
							});
						}
					}
                },{ 
					id: 'cancelButton',
                    text: '<?php echo __('Close',null,'login'); ?>', 
					icon: '/images/icons/cancel.png',
                    handler: function(){ 
                        cf.Window.theWindow.hide(); 
                    } 
                }] 
		});
	}
};}();