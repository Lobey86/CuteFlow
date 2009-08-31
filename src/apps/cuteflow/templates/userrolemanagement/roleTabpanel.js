cf.AddRoleTabpanel = function(){return {
	
	theTabpanel								:false,
	theRoleNameText							:false,
	theFormPanel							:false,
	theHiddenField							:false,
	
	init: function (id) {
		this.initTextfield(id);
		this.initTabpanel();
		this.theTabpanel.add(this.theRoleNameText);
		this.initTree(id);
		this.theTabpanel.setActiveTab(this.theRoleNameText);
		this.initFormPanel();
		this.theFormPanel.add(this.theTabpanel);
	},
	
	initTree: function (id) {
		if(id == '') {
			var url = '<?php echo url_for('userrolemanagement/LoadRoleTree')?>';
		}
		else {
			var url = '<?php echo url_for('userrolemanagement/LoadRoleTree')?>/role_id/' + id;
		}
		
		
		Ext.Ajax.request({  
			url : url,
			success: function(objServerResponse){
				theJsonTreeData = Ext.util.JSON.decode(objServerResponse.responseText);
				cf.AddRoleTabpanel.buildTabs(theJsonTreeData);
				Ext.getCmp('userrole_title_id').setValue(theJsonTreeData.name);
				if (Ext.getCmp('userrole_title_id').getValue() != '') {
					Ext.getCmp('userrole_title_id').setDisabled(true);
				}
			}
		});
	},
	
	initFormPanel: function () {
		this.theFormPanel = new Ext.FormPanel({
			id: 'submitNewUserrole'
		})
	},
	
	buildTabs: function (theJsonTreeData) {
		for(var a=0;a<theJsonTreeData.result.length;a++) {
			var tabItem = new Ext.Panel({
				title: theJsonTreeData.result[a].usermodule.translation,
				id: theJsonTreeData.result[a].usermodule.id,
				layout: 'form',
				frame: true
			});
			
			for (var b=0;b<theJsonTreeData.result[a].usermodule.usergroup.length;b++) {
				var tabCategory = theJsonTreeData.result[a].usermodule.usergroup[b];
				tabItem.add({
					xtype: 'fieldset',
					title: '<table><tr><td><div id="'+tabCategory.icon+'">&nbsp;</div></td><td><div>' + tabCategory.translation + '</div></td></tr></table>',
					id: tabCategory.id,
					labelWidth: 180, 
					style:'margin-top:5px;margin-left:5px;margin-right:5px;'
				});
				
				var myFieldset = Ext.getCmp(tabCategory.id);
				for(var c=0;c<theJsonTreeData.result[a].usermodule.usergroup[b].userright.length;c++) {
					var myCheckbox = theJsonTreeData.result[a].usermodule.usergroup[b].userright[c];
					var myFieldset = Ext.getCmp(tabCategory.id);
					if(myCheckbox.parent == 1) {
						myFieldset.add({
							fieldLabel: '<b>'+myCheckbox.translation+'</b>',
							xtype: 'checkbox',
							id: myCheckbox.database_id,
							name: myCheckbox.database_id,
							checked: myCheckbox.checked,
							labelWidth: 180, 
							style:'margin-top:4px;margin-left:120px;',
							handler: function (check) {
								var parentElement = check.ownerCt;
								parentElement.items.each(function(itm){
									itm.setValue(check.checked);									
								});
							 }
						});
					}
					else {
						myFieldset.add({
							fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;' + myCheckbox.translation,
							xtype: 'checkbox',
							id: myCheckbox.database_id,
							name: myCheckbox.database_id,
							checked: myCheckbox.checked,
							labelWidth: 180, 
							style:'margin-top:4px;margin-left:120px;'
						});
					}
				}
			}
			this.theTabpanel.add(tabItem);
		}
	},
	
	initTabpanel: function () {
		this.theTabpanel = new Ext.TabPanel({
			frame: false,
			enableTabScroll:true,
			plain: false,
			id: 'tabpanel_id',
			deferredRender:false
		});
	},

	
	initTextfield: function (id) {
		this.theRoleNameText = new Ext.Panel({
			title: '<?php echo __('Description',null,'userrolemanagementpopup'); ?>',
			frame: true,
			items:[{
				xtype: 'fieldset',
				title: '<?php echo __('Userrole description',null,'userrolemanagementpopup'); ?>',
				style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			    items: [{
	                xtype: 'textfield',
	                name: 'userrole_title_name',
					id: 'userrole_title_id',
					allowBlank: false,
					value: '',
	                fieldLabel: '<?php echo __('Name',null,'userrolemanagementpopup'); ?>'
            	},{
					xtype: 'hidden',
					name: 'hiddenfield',
					id: 'hiddenfield',
					value: id
				}]
			}]
			
		});
	}
};}();