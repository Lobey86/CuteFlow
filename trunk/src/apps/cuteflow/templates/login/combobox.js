cf.ComboBox = function(){return {
	theComboBox : false,
	theComboStore: false,
	
	init: function () {
		this.initStore();
		this.initCombobox();
	},

	initCombobox: function () {
		this.theComboBox = new Ext.form.ComboBox({
		fieldLabel: '<?php echo __('Language',null,'login'); ?>',
		valueField: 'value',
		displayField: 'text',
		editable: false,
		mode: 'local',
		store: this.theComboStore,
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
						
							// get JSON Serverresult
							var ServerResult = {};
							ServerResult = JSON.parse(objServerResponse.responseText);
							
							// change Language and set Labels of Ext Components
							cf.Textfield.theUsernameField.setLabel((ServerResult.result.username) + ':'); 
							cf.Textfield.theUserpasswordField.setLabel((ServerResult.result.password) + ':'); 
							combo.setLabel((ServerResult.result.language) + ':');	
							Ext.getCmp('loginButton').setText(ServerResult.result.login);
							Ext.getCmp('cancelButton').setText(ServerResult.result.close);
							cf.Window.theWindow.setTitle('<div style="float:left;"><img src="/images/icons/key.png" /></div><div>&nbsp;&nbsp;CuteFlow - ' + ServerResult.result.login + '</div>');
							// store selected language here
							cf.Textfield.theHiddenField.setValue(combo.getValue());
							// refresh ComboBox here
							var hasFocus = cf.ComboBox.theComboBox.hasFocus;
							cf.ComboBox.theComboBox.hasFocus = null;
							cf.ComboBox.theComboStore.reload({callback: function(){
									if(cf.ComboBox.theComboBox.thasFocus === null){
										cf.ComboBox.theComboBox.hasFocus = hasFocus;
									}
								}
							});
							cf.ComboBox.theComboBox.setValue(ServerResult.defaultValue);
							cf.Textfield.theUsernameField.focus();
						}
					});
				}
			
			}
		}
		});
	
	}, 
	
	initStore: function () {
		this.theComboStore = new Ext.data.JsonStore({
			mode: 'local',
			autoload: true,
			url: '<?php echo url_for('login/LoadLanguage')?>',
			root: 'result',
			fields: [
				{name: 'value'},
				{name: 'text'}
			]
		});

		cf.ComboBox.theComboStore.load();
	}


};}();

Ext.override(Ext.form.Field, {
   setLabel: function(text){
      var r = this.getEl().up('div.x-form-item');
      r.dom.firstChild.firstChild.nodeValue = String.format('{0}', text);
   }
}); 