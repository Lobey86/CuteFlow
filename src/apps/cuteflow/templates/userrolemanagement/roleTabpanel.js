cf.AddRoleTabpanel = function(){return {
	
	theTabpanel								:false,
	theRoleNameTextfiel						:false,
	
	
	init: function () {
		this.initTextfield();
		this.initTabpanel();
		this.theTabpanel.add(this.theRoleNameText);
		this.initTree();
		this.theTabpanel.setActiveTab(this.theRoleNameText);
	},
	
	initTree: function () {
		Ext.Ajax.request({  
			url : '<?php echo url_for('userrolemanagement/LoadRoleTree')?>',
			success: function(objServerResponse){
				//alert(objServerResponse.responseText);
				theJsonTreeData = {};
				theJsonTreeData = JSON.parse(objServerResponse.responseText);
				cf.AddRoleTabpanel.buildTabs(theJsonTreeData);
			}
		});
	},
	
	buildTabs: function (theJsonTreeData) {
		
		for(var a=0;a<theJsonTreeData.result.length;a++) {
			var tabItem = new Ext.Panel({
				title: theJsonTreeData.result[a].usermodule.title,
				id: theJsonTreeData.result[a].usermodule.id,
				height: 600,
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
							id: myCheckbox.id,
							name: myCheckbox.name,
							style:'margin-top:4px;margin-left:120px;',
							handler: function (check) {
								var name = check.getName();

								var changeFieldSet = Ext.getCmp(name);
								changeFieldSet.items.each(function(itm){
									itm.setValue(check.checked);									
								});
							 }
						});
					}
					else {
						myFieldset.add({
							fieldLabel: '&nbsp;&nbsp;&nbsp;&nbsp;' + myCheckbox.title,
							xtype: 'checkbox',
							id: myCheckbox.id,
							style:'margin-top:4px;margin-left:120px;'
						});
					}
					
					
	
				}
			}
			
			this.theTabpanel.add(tabItem);
			//alert(theJsonTreeData.result[a].tab.title);
		}
	},
	
	initTabpanel: function () {
		this.theTabpanel = new Ext.TabPanel({
			title: 'fwefew',
			frame: false,
			enableTabScroll:true,
			plain: false,
			deferredRender:false
		});
	},
	
	initTextfield: function () {
		this.theRoleNameText = new Ext.Panel({
			title: 'Name',
			frame: true,
			items:[{
				xtype: 'fieldset',
				title: 'Userrole Name',
				style:'margin-top:5px;margin-left:5px;margin-right:5px;',
			    items: [{
	                xtype: 'textfield',
	                name: 'txt-test1',
	                fieldLabel: 'Userrole title'
            	}]
			}]
			
		});
	}
	
	
	
};}();