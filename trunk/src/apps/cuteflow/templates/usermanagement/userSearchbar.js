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
						fieldLabel: '<?php echo __('Firstname',null,'user'); ?>',
						style:'margin-top:5px;',
						name: 'firstname',
						id: 'firstname',
						width:150
					},{
						xtype:'textfield',
						fieldLabel: '<?php echo __('Lastname',null,'user'); ?>',
						name: 'lastname',
						id: 'lastname',
						width:150
					},{
						xtype:'textfield',
						fieldLabel: '<?php echo __('Email',null,'user'); ?>',
						name: 'email',
						id: 'email',
						width:150
					},{
						xtype:'textfield',
						fieldLabel: '<?php echo __('Username',null,'user'); ?>',
						name: 'username',
						id: 'username',
						width:150
					},{
						xtype: 'combo',
						fieldLabel : '<?php echo __('Userrole',null,'user'); ?>',
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
						xtype: 'button',
						text: '<?php echo __('Search',null,'user'); ?>',
						name: 'search',
						id: 'search',
						style:'margin-bottom:5px;margin-left:3px;',
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
							
						},
						width: 70
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