/**
* Class inits the Searchbar for the usergrid, and handles also the searchrequest if 
* searchbutton is hit
* 
*/

cf.UserSearchbar = function(){return {

	theUserSearchbar			:false,
	theUserRoleStore			:false,
	theUserRoleStoreInitialized :false,
	
	init: function () {
		this.initRoleStore();
		this.theUserSearchbar = new Ext.Panel({
				closable: false,
				plain: false,
				frame: false,
				title: '<?php echo __('Searchbar',null,'user'); ?>',
				style:'margin-top:5px;margin-left:5px;margin-right:10px;',
				collapsible:true,
				collapsed: true,
				tools: [{
					id:'close',
					handler: function(e, target, panel){
						panel.ownerCt.remove(panel, true);
					}
				}],
				items:[{
					layout: 'form',
					items: [{
						xtype:'textfield',
						fieldLabel: '&nbsp;<?php echo __('Firstname',null,'usermanagement'); ?>',
						style:'margin-top:2px;',
						name: 'firstname',
						id: 'firstname',
						width:150
					},{
						xtype:'textfield',
						fieldLabel: '&nbsp;<?php echo __('Lastname',null,'usermanagement'); ?>',
						name: 'lastname',
						id: 'lastname',
						width:150
					},{
						xtype:'textfield',
						fieldLabel: '&nbsp;<?php echo __('Email',null,'usermanagement'); ?>',
						name: 'email',
						id: 'email',
						width:150
					},{
						xtype:'textfield',
						fieldLabel: '&nbsp;<?php echo __('Username',null,'usermanagement'); ?>',
						name: 'username',
						id: 'username',
						width:150
					},{
						xtype: 'combo',
						fieldLabel : '&nbsp;<?php echo __('Userrole',null,'usermanagement'); ?>',
						id: 'userrole',
						valueField: 'id',
						mode: 'remote',
						displayField: 'description',
						store: this.theUserRoleStore,
						editable: false,
						typeAhead: false,
						triggerAction: 'all',
						allowBlank: true,
						width: 150
					},{
				        xtype: 'fieldset',
						autoHeight: true,
				        border: false,
				        defaultType: 'textfield',
				        layout: 'column',
				        items:[{
							xtype: 'button',
							text: '<?php echo __('Search',null,'usermanagement'); ?>',
							name: 'search',
							id: 'search',
							width: 70,
							style:'margin-bottom:5px;margin-left:35px;',
							handler: function (){
								
								var flag = false;
								var append = '';
								
								if(Ext.getCmp('firstname').getValue() != '') {
									append += '/firstname/' + Ext.getCmp('firstname').getValue();
									flag = true;
								}
								if(Ext.getCmp('lastname').getValue() != '') {
									append += '/lastname/' + Ext.getCmp('lastname').getValue();
									flag = true;
								}
								if(Ext.getCmp('email').getValue() != '') {
									append += '/email/' + Ext.getCmp('email').getValue();
									flag = true;
								}
								if(Ext.getCmp('username').getValue() != '') {
									append += '/username/' + Ext.getCmp('username').getValue();
									flag = true;
								}
								if(Ext.getCmp('userrole').getValue() != '') {
									append += '/userrole/' + Ext.getCmp('userrole').getValue();
									flag = true;
								}
	
								if (flag == true) {
									var url = encodeURI('<?php echo url_for('usermanagement/LoadAllUserFilter')?>' + append);
									cf.UserGrid.theUserStore.proxy.setApi(Ext.data.Api.actions.read,url);
									cf.UserGrid.theUserStore.reload();
								}
								else {
									var url = encodeURI('<?php echo url_for('usermanagement/LoadAllUser')?>');
									cf.UserGrid.theUserStore.proxy.setApi(Ext.data.Api.actions.read,url);
								}
							}
						},{
							xtype: 'button',
							text: '<?php echo __('Discard',null,'usermanagement'); ?>',
							name: 'search',
							id: 'discard',
							width: 70,
							style:'margin-bottom:5px;margin-left:25px;',
							handler: function () {
								Ext.getCmp('firstname').setValue();
								Ext.getCmp('lastname').setValue();
								Ext.getCmp('email').setValue();
								Ext.getCmp('username').setValue();
								Ext.getCmp('userrole').setValue();
								var url = encodeURI('<?php echo url_for('usermanagement/LoadAllUser')?>');
								cf.UserGrid.theUserStore.proxy.setApi(Ext.data.Api.actions.read,url);
								cf.UserGrid.theUserStore.reload();
							}
						}]
					}]
				}]
		});
	},
	
	initRoleStore: function () {
		this.theUserRoleStoreInitialized = true;
		this.theUserRoleStore = new Ext.data.JsonStore({
				root: 'result',
				url: '<?php echo url_for('usermanagement/LoadAllRole')?>',
				autoload: true,
				fields: [
					{name: 'id'},
					{name: 'description'}
				]
		});
	}
	
	
};}();