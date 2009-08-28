cf.AddRoleTabpanel = function(){return {
	
	theTabpanel								:false,
	theRoleNameText							:false,
	theFormPanel							:false,
	
	init: function () {
		this.initTextfield();
		this.initTabpanel();
		this.theTabpanel.add(this.theRoleNameText);
		this.initTree();
		this.theTabpanel.setActiveTab(this.theRoleNameText);
		this.initFormPanel();
		this.theFormPanel.add(this.theTabpanel);
	},
	
	initTree: function () {
		Ext.Ajax.request({  
			url : '<?php echo url_for('userrolemanagement/LoadRoleTree')?>',
			success: function(objServerResponse){
				theJsonTreeData = Ext.util.JSON.decode(objServerResponse.responseText);
				cf.AddRoleTabpanel.buildTabs(theJsonTreeData);
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
				title: theJsonTreeData.result[a].usermodule.title,
				id: theJsonTreeData.result[a].usermodule.id,
				layout: 'form',
				frame: true
			});
			
			for (var b=0;b<theJsonTreeData.result[a].usermodule.usergroup.length;b++) {
				var tabCategory = theJsonTreeData.result[a].usermodule.usergroup[b];
				tabItem.add({
					xtype: 'fieldset',
					title: '<table><tr><td><div id="'+tabCategory.icon+'">&nbsp;</div></td><td><div>' + tabCategory.title + '</div></td></tr></table>',
					id: tabCategory.id,
					style:'margin-top:5px;margin-left:5px;margin-right:5px;'
				});
				var myFieldset = Ext.getCmp(tabCategory.id);
				for(var c=0;c<theJsonTreeData.result[a].usermodule.usergroup[b].userright.length;c++) {
					var myCheckbox = theJsonTreeData.result[a].usermodule.usergroup[b].userright[c];
					var myFieldset = Ext.getCmp(tabCategory.id);
					if(myCheckbox.parent == 1) {
						myFieldset.add({
							fieldLabel: '<b>'+myCheckbox.title+'</b>',
							xtype: 'checkbox',
							id: myCheckbox.database_id,
							name: myCheckbox.database_id,
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
							fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;' + myCheckbox.title,
							xtype: 'checkbox',
							id: myCheckbox.database_id,
							name: myCheckbox.database_id,
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
	
	initTextfield: function () {
		this.theRoleNameText = new Ext.Panel({
			title: '<?php echo __('Description',null,'userrolemanagement'); ?>',
			frame: true,
			items:[{
				xtype: 'fieldset',
				title: '<?php echo __('Userrole description',null,'userrolemanagement'); ?>',
				style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			    items: [{
	                xtype: 'textfield',
	                name: 'userrole_title_name',
					id: 'userrole_title_id',
					allowBlank: false,
	                fieldLabel: 'Name'
            	}]
			}]
			
		});
	}
	
	
	
};}();