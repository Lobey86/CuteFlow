/** class creates a panel to send message to all users **/
cf.administration_sendmessage = function(){return {
	
	theSystemMessageWindow			:false,
	theTxtfield						:false,
	isInitialized					:false,
	theSendMessagePanel				:false,
	theMessageBox					:false,
	theReceiver						:false,
	theButtons						:false,
	
	
	/** init function **/
	init:function () {
		if (this.isInitialized == false) {
			this.isInitialized = true;
			this.initSubject();
			this.initPanel();
			this.initMessagebox();
			this.initReceiver();
			this.initButtons();
			this.initWindow();
			this.theSendMessagePanel.add(this.theTxtfield);
			this.theSendMessagePanel.add(this.theMessageBox);
			this.theSendMessagePanel.add(this.theReceiver);
			this.theSendMessagePanel.add(this.theButtons);
			this.theSystemMessageWindow.add(this.theSendMessagePanel);
		}
		
	},
	
	
	/** set the tab to the window **/
	initWindow: function () {
		this.theSystemMessageWindow =  new Ext.Panel({
			modal: true,
			closable: true,
			modal: true,
			autoScroll: true,
			shadow: false,
			minimizable: false,
			draggable: false,
			resizable: false,
	        plain: false,
	        width: 620,
	        height: 600,
	        title: 'Send Message'
	    });
		
	},
	
	/** init Formpanel **/
	initPanel: function () {
		this.theSendMessagePanel = new Ext.FormPanel({
			closable: false,
			plain: false,
			frame: false,
			layout: 'form',
			width: 620,
			height: 560,
			title: 'Send Message',
			style:'margin-top:5px;margin-left:5px;margin-right:10px;',
			collapsible:false
		});
	},
	
	/** init subject textareat **/
	initSubject: function () {
		this.theTxtfield = new Ext.form.FieldSet({
			title: 'Betreff',
			allowBlank: false,
			style:'margin-top:5px;margin-left:10px;',
			width: 600,
			items:[{
				xtype: 'textfield',
				allowBlank: true,
				fieldLabel: 'Betreff',
				name: 'betreff',
				style:'margin-right:10px;',
				width: 470
			}]
		});
	},
	
	/** init the messagebox and comboxbox **/
	initMessagebox: function () {
		this.theMessageBox = new Ext.form.FieldSet({
			title: 'Format und Nachricht',
			allowBlank: false,
			style:'margin-top:5px;margin-left:10px;',
			width: 600,
			items:[{
				xtype: 'combo',
				mode: 'local',
				value: 'Plain',
				editable:false,
				triggerAction: 'all',
				foreSelection: true,
				fieldLabel: 'Art',
				store: new Ext.data.SimpleStore({
					 fields:['type','text'],
       				 data:[['PLAIN', 'Plain'],['HTML', 'HTML']]
   				}),
 				valueField:'type',
				displayField:'text',
				width:70,
				listeners: {
		    		select: {
		    			fn:function(combo, value) { // change the textarea and htmlarea
		    				if (combo.getValue() == 'PLAIN') {
		    					var checkField = cf.administration_sendmessage.theMessageBox.findById('systemMessageTextarea');
		    					if (!checkField) {
		    					cf.administration_sendmessage.theMessageBox.add({
		    										xtype: 'textarea',
													name: 'description',
													fieldLabel: 'Betreff:',
													id: 'systemMessageTextarea',
													labelSeparator: '',
													height: 250,
													width: 400,
													value: Ext.getCmp('systemMessageHTMLArea').getValue(),
													anchor: '100%'
		    						});
		    						cf.administration_sendmessage.theMessageBox.remove('systemMessageHTMLArea');
		    						cf.administration_sendmessage.theSystemMessageWindow.doLayout();
		    					}
		    				}
		    				else {
		    					var checkField = cf.administration_sendmessage.theMessageBox.findById('systemMessageHTMLArea');
		    					if (!checkField) {
			    					cf.administration_sendmessage.theMessageBox.add({
										xtype: 'htmleditor',
										name: 'description',
										fieldLabel: 'Betreff:',
										id: 'systemMessageHTMLArea',
										labelSeparator: '',
										height: 250,
										width: 400,
										value: Ext.getCmp('systemMessageTextarea').getValue(),
										anchor: '100%'
		    						});	
		    						cf.administration_sendmessage.theMessageBox.remove('systemMessageTextarea');
		    						cf.administration_sendmessage.theSystemMessageWindow.doLayout();
		    					}
		    				}
		    			}
		    		}
		    	}
				
				
			},{
				xtype: 'textarea',
				name: 'description',
				fieldLabel: 'Betreff:',
				id: 'systemMessageTextarea',
				labelSeparator: '',
				height: 250,
				width: 400,
				anchor: '100%'
			}]
		});
		
	},
	
	/** set receiver panel **/
	initReceiver: function () {
		this.theReceiver = new Ext.form.FieldSet({
			title: 'Format und Nachricht',
			allowBlank: false,
			style:'margin-top:5px;margin-left:10px;',
			width: 600,
			items: [{
				xtype: 'combo',
				mode: 'local',
				value: 'Alle',
				editable:false,
				name: 'receiver',
				triggerAction: 'all',
				foreSelection: true,
				fieldLabel: 'Art',
				store: new Ext.data.SimpleStore({
					 fields:['type','text'],
       				 data:[['ALL', 'Alle'],['SENDER', 'nur Sender'],['ONLINE', 'Benutzer die gerade online sind']]
   				}),
 				valueField:'type',
				displayField:'text',
				width:200
			}]
		});
	},
	
	/** add buttons to form **/
	initButtons: function () {
		this.theButtons = new Ext.form.FieldSet({
			style:'margin-top:5px;margin-left:10px;',
			width: 600,
			border: false,
			buttonAlign: 'center',
			buttons:[{
				text:'Abschicken', 
				icon: '/images/icons/accept.png',
				handler: function () {
					alert("moep");
				}
			},{
				text:'Verwerfen', 
				icon: '/images/icons/cancel.png',
				handler: function () {
					cf.TabPanel.theTabPanel.remove(cf.administration_sendmessage.theSystemMessageWindow);
					cf.administration_sendmessage.theSystemMessageWindow.hide();
					cf.administration_sendmessage.theSystemMessageWindow.destroy();
				}
			}]
			
		});
	},
	
	
	/** 
	 * Part of the API
	 * set value if class is already initialized. 
	 * @param boolean value
	 *
	 **/
	setInitialized: function (value) {
		this.isInitialized = value;
	},
	
	
	/**
	* Part of the API
	* This function returns the window, to add it into tabpanel
	*
	*/
	getInstance: function() {
		return this.theSystemMessageWindow;
	}
	
};}();