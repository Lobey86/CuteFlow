/**
* Class adds the second Tab when editing and creating new user
*
*/
cf.AddUserSecondTab = function(){return {
	
	theSecondPanel					: false,
	
	
	/** calls all necessary functions **/
	init: function() {
		this.initPanel();
	},
	
	/** builds panel and its elements **/
	initPanel: function () {
		this.theSecondPanel = new Ext.Panel({
			title: 'Benutzerdetails',
			frame: true,
			enableTabScroll:true,
			plain: true,
			layout: 'form',
			labelWidth : 200,
			width: 500,
			height: 530,
			items:[{
				xtype: 'fieldset',
				title: 'Anschrift',
				items:[{
					xtype: 'textfield',
					fieldLabel: 'Stra&szlig;e',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'street',
					id: 'street',
					allowBlank: true,
					width: 200
				},{
					xtype: 'panel',
					fieldLabel: 'PLZ / Ort',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					layout: 'column',
					allowBlank: true,
					width: 210,
					items:[{
						xtype: 'textfield',
						labelStyle: 'font-weight:bolder;',
						labelWidth: 150,
						style: 'margin-right:5px;',
						name: 'zip',
						id: 'zip',
						allowBlank: true,
						width: 45
					},{
						xtype: 'textfield',
						labelStyle: 'font-weight:bolder;',
						labelWidth: 150,
						name: 'city',
						id: 'city',
						allowBlank: true,
						width: 162
					}]
				},{
					xtype: 'textfield',
					fieldLabel: 'Land',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'country',
					id: 'country',
					allowBlank: true,
					width: 200
				}]		
			},{
				xtype: 'fieldset',
				title: 'Telefon und Fax',
				items: [{
					xtype: 'textfield',
					fieldLabel: 'Telefon 1',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'phone1',
					id: 'phone1',
					allowBlank: true,
					width: 200	
				},{
					xtype: 'textfield',
					fieldLabel: 'Telefon 2',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'phone2',
					id: 'phone2',
					allowBlank: true,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: 'Mobilnummer',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'mobil',
					id: 'mobil',
					allowBlank: true,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: 'Fax',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'fax',
					id: 'fax',
					allowBlank: true,
					width: 200
				}]
			},{
				xtype: 'fieldset',
				title: 'Organisation, Abteilung und Kostenstelle',
				items:[{
					xtype: 'textfield',
					fieldLabel: 'Organisation',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'organisation',
					id: 'organisation',
					allowBlank: true,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: 'Abteilung',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'department',
					id: 'department',
					allowBlank: true,
					width: 200
				},{
					xtype: 'textfield',
					fieldLabel: 'Kostenstelle',
					labelStyle: 'font-weight:bolder;',
					labelWidth: 150,
					name: 'burdencenter',
					id: 'burdencenter',
					allowBlank: true,
					width: 200
				}]
			},{
				xtype: 'fieldset',
				title: 'Kommentar',
				items:[{
					xtype: 'textarea',
					fieldLabel: 'Kommentar',
					labelStyle: 'font-weight:bolder;',
					id:'comment',
					name: 'comment',
					allowBlank: true,
					width: 200,
					height: 80
				}]
			}]
		});
		
		
	}
	
	
	
};}();