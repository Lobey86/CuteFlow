/**
* this class builds out of an ajax request, dynamically all tabs to add or edit an exisiting role
*
*/

cf.AddRoleTabpanel = function(){return {
	
	theTabpanel								:false,
	theRoleNameText							:false,
	theFormPanel							:false,
	theHiddenField							:false,
	
	
	/** 
	* function calls all neede functions to build the tab 
	*
	* @param int id, id of the record, if edit button was pressed, when new record, id is null
	*
	*/
	init: function (id) {
		this.initTextfield(id);
		this.initTabpanel();
		this.theTabpanel.add(this.theRoleNameText);
		this.initTree(id);
		this.theTabpanel.setActiveTab(this.theRoleNameText);
		this.initFormPanel();
		this.theFormPanel.add(this.theTabpanel);
	},
	
	/**
	* Function loads the tree from server and stores it. When in edit mode, current settings will be loaded
	*
	* @param int id, id of the record, if edit button was pressed, when new record, id is null
	*/
	initTree: function (id) {
		if(id == '') {
			var url = '<?php echo url_for('userrolemanagement/LoadRoleTree')?>';
		}
		else {
			var url = '<?php echo url_for('userrolemanagement/LoadRoleTree')?>/role_id/' + id;
		}
		
		// load tree here
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
	
	/** formpanel to submit all values **/
	initFormPanel: function () {
		this.theFormPanel = new Ext.FormPanel({
			id: 'submitNewUserrole'
		})
	},
	
	/**
	* All tabs, fieldsets and checkboxes are build here
	*
	* @param json_object theJsonTreeData,  tree stored as json object
	*/
	buildTabs: function (theJsonTreeData) {
		for(var a=0;a<theJsonTreeData.result.length;a++) {
			// build tab item here
			var tabItem = new Ext.Panel({
				title: theJsonTreeData.result[a].usermodule.translation,
				id: theJsonTreeData.result[a].usermodule.id,
				height: 529,
				autoScroll:true,
				layout: 'form',
				frame: true
			});
			
			// build fieldsetes here
			for (var b=0;b<theJsonTreeData.result[a].usermodule.usergroup.length;b++) {
				var tabCategory = theJsonTreeData.result[a].usermodule.usergroup[b];
				tabItem.add({
					xtype: 'fieldset',
					title: '<table><tr><td><div class="'+tabCategory.icon+'">&nbsp;</div></td><td><div>' + tabCategory.translation + '</div></td></tr></table>',
					id: tabCategory.id,
					labelWidth: 180, 
					style:'margin-top:5px;margin-left:5px;margin-right:5px;'
				});
				
				var myFieldset = Ext.getCmp(tabCategory.id);
				// build checkboxes here
				for(var c=0;c<theJsonTreeData.result[a].usermodule.usergroup[b].userright.length;c++) {
					var myCheckbox = theJsonTreeData.result[a].usermodule.usergroup[b].userright[c];
					var myFieldset = Ext.getCmp(tabCategory.id);
					if(myCheckbox.parent == 1) { // parent checkbox
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
					else { // child checkbox
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
	
	/** Tabpanel **/
	initTabpanel: function () {
		this.theTabpanel = new Ext.TabPanel({
			frame: false,
			enableTabScroll:true,
			plain: false,
			id: 'tabpanel_id',
			deferredRender:false
		});
	},

	/** init panel with textfield to enter the name of the role **/
	initTextfield: function (id) {
		this.theRoleNameText = new Ext.Panel({
			title: '<?php echo __('Description',null,'userrolemanagementpopup'); ?>',
			frame: true,
			height: 529,
			style: 'border:none;',
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