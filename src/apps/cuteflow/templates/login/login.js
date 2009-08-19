Ext.BLANK_IMAGE_URL = '/images/default/s.gif';

Ext.override(Ext.form.Field, {
   setLabel: function(text){
      var r = this.getEl().up('div.x-form-item');
      r.dom.firstChild.firstChild.nodeValue = String.format('{0}', text);
   }
}); 

Ext.onReady(function(){

	var languageStore = '<?php echo $sf_user->getCulture()?>';
	
	var win = new Ext.Window({
		width:370,
		height:175,
		plain: true,
		title: '<div style="float:left;"><img src="/images/icons/key.png" /></div><div>&nbsp;&nbsp;CuteFlow - <?php echo __('Login',null,'login'); ?></div>',
		closable: false,
		 buttonAlign: 'center', 
                buttons: [{ 
                    id: 'loginButton',
					text:'<?php echo __('Login',null,'login'); ?>', 
					icon: '/images/icons/lock.png',
					handler: function () {
						if(username.getValue() != '' && userpassword.getValue() != '') {
							//alert('<?php echo url_for('login/DoLogin')?>/username/' + username.getValue() + '/password/' + userpassword.getValue() + '/language/' + languageStore);
							Ext.Ajax.request({
								url: '<?php echo url_for('login/DoLogin')?>/username/' + username.getValue() + '/password/' + userpassword.getValue() + '/language/' + languageStore,						
								success: function(objServerResponse){  
									if(objServerResponse.responseText == 1) {
										var url = Ext.get('url').dom.value;
										window.location.href = url;
									}
									else {
										var ServerResult = {};
										ServerResult = JSON.parse(objServerResponse.responseText);
										Ext.MessageBox.alert(ServerResult.result.errorTitle, ServerResult.result.errorMessage);
										username.setValue();
										userpassword.setValue();
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
                        win.hide(); 
                    } 
                }] 
	});

	var username = new Ext.form.TextField({
		id: 'username',
		fieldLabel: '<?php echo __('Username',null,'login'); ?>',
		allowBlank: false,
		width: 225
	});
	
	var countrystore = new Ext.data.JsonStore({
		mode: 'local',
		autoload: true,
		url: '<?php echo url_for('login/LoadLanguage')?>',
		root: 'result',
		fields: [
			{name: 'value'},
			{name: 'text'}
		]
	});
	countrystore.load();
	
	var userpassword = new Ext.form.TextField({
		id:'userpassword',
		fieldLabel: '<?php echo __('Password',null,'login'); ?>',
		allowBlank: false,
		inputType: 'password',
		width: 225
	});
	
	
	
	var language = new Ext.form.ComboBox({
		fieldLabel: '<?php echo __('Language',null,'login'); ?>',
		valueField: 'value',
		displayField: 'text',
		editable: false,
		mode: 'local',
		store: countrystore,
		triggerAction: 'all',
		selectOnFocus:true,
		allowBlank: true,
		forceSelection:true,
		id:'language',
		value: '<?php echo Language::buildDefaultLanguage($sf_user->getCulture())?>',
		width: 225,
		listeners: {
		    select: {
				fn:function(combo, value) {
					Ext.Ajax.request({  
						
						url : '<?php echo url_for('login/ChangeLanguage')?>/language/' + combo.getValue(), 
						success: function(objServerResponse){  
							var ServerResult = {};
							ServerResult = JSON.parse(objServerResponse.responseText);
							username.setLabel((ServerResult.result.username) + ':'); 
							userpassword.setLabel((ServerResult.result.password) + ':'); 
							combo.setLabel((ServerResult.result.language) + ':');	
							Ext.getCmp('loginButton').setText(ServerResult.result.login);
							Ext.getCmp('cancelButton').setText(ServerResult.result.close);
							win.setTitle('<div style="float:left;"><img src="/images/icons/key.png" /></div><div>&nbsp;&nbsp;CuteFlow - ' + ServerResult.result.login + '</div>');
							languageStore = combo.getValue()
							countrystore.reload();	
							combo.collapse.defer(500, combo);
							combo.setValue(ServerResult.defaultValue);
						}
					});
				}
			
			}
		}
	});
		
	
	var panel = new Ext.Panel({
		plain: false,
		frame: true,
		height: 100,
		buttonAlign: 'center',
		layout: 'form'
	});
	
	
	
panel.add(username);
panel.add(userpassword);
panel.add(language);
win.add(panel);
win.show();
});