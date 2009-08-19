/**
* Class creates the Navigation for the west Region.
* Navigation type is a TreeTabpanel.
* Class also Handles the Click Functionality and adds Navigation Clicks to TabPanel
*
*/
cf.Navigation = function(){return {

	
	isInitialized                 : false,
	theNavigationPanel            : false, // stores the left Navigationpabel
	
	/*********************************/
	
	init: function () {
		if (this.isInitialized == false) {
			this.isInitialized = true;
			this.theNavigationPanel = new Ext.tree.TreePanel({
				width: 230,
				frame: false,
				bodyStyle:'padding:5px;font-size:11px;background-color:#f4f4f4;',
				loader: new Ext.tree.TreeLoader(),
				rootVisible: false,
				border: false,
				id: 'NavigationPanel',
				root: new Ext.tree.AsyncTreeNode({
					children: [{
						text: '<b style="font-size:16px;">Uml&auml;ufe</b>',
						expanded: true,
						children: [{
							text: '<span style="font-size:14px;">Dokumentenuml&auml;ufe</span>',
							leaf: true
						}, {
							text: '<span style="font-size:14px;">Umlaufarchiv</span>',
							leaf: true
						},{
							text: '<span style="font-size:14px;">ToDo</span>',
							leaf: true
						}]
					},{
						text: '<b style="font-size:16px;">Management</b>',
						expanded: true,
						children: [{
							text: '<span style="font-size:14px;">Felder</span>',
							href: 'index.php',
							leaf: true
						},{
							text: '<span style="font-size:14px;">Dokumentenvorlage</span>',
							leaf: true
						},{
							text: '<span style="font-size:14px;">Verteiler</span>',
							leaf: true
						},{
							text: '<span style="font-size:14px;">Statisitk</span>',
							leaf: true
						}]
					},{
						text: '<b style="font-size:16px;"><?php echo __('Administration',null,'layout'); ?></b>',
						expanded: true,
						children: [{
							text: '<span style="font-size:14px;"><?php echo __('User management',null,'layout'); ?></span>',
							id: 'nodeUser',
							icon: '/images/icons/group.png',
							disabled: false,
							leaf: true
						},{
							text: '<span style="font-size:14px;"><?php echo __('My Profile',null,'layout'); ?></span>',
							id: 'nodeMySettings',
							icon: '/images/icons/user_edit.png',
							disabled: false,
							leaf: true
						},{
							text: '<span style="font-size:14px;"><?php echo __('System settings',null,'layout'); ?></span>',
							id: 'nodeSystemSettings',
							icon: '/images/icons/wrench.png',
							disabled: false,
							leaf: true
						},{
							text: '<span style="font-size:14px;"><?php echo __('Userrole management',null,'layout'); ?></span>',
							id: 'nodeRoleManagement',
							icon: '/images/icons/cog.png',
							disabled: false,
							leaf: true	
							
						},{
							text: '<span style="font-size:14px;"><?php echo __('Send Message',null,'layout'); ?></span>',
							id: 'nodeMessage',
							icon: '/images/icons/note_go.png',
							disabled: false,
							leaf: true
						}]
					}]
				})
			})
		}
		this.theNavigationPanel.on('click', function(node){
			if(node.leaf == true && node.disabled == false) {
				var windowObject;
				if(node.id == 'nodeUser') {
					windowObject = cf.UserManagement;
				}
				else if (node.id == 'nodeRoleManagement') {
					windowObject = cf.UserRoleManagement;

				}
				
				
				
				if(cf.TabPanel.theTabPanel.items.length > 0) {
					if (windowObject.isInitialized == false) {
						windowObject.init();
						cf.TabPanel.theTabPanel.add(windowObject.getInstance());	
						cf.TabPanel.theTabPanel.setActiveTab(windowObject.getInstance());
					}
					else {
						var windowLabel = windowObject.getInstance();
						var tab = cf.TabPanel.theTabPanel.findById(windowLabel.id);
						if(tab == null) {
							windowObject.setInitialized(false);
							windowObject.init();
							cf.TabPanel.theTabPanel.add(windowObject.getInstance());
							cf.TabPanel.theTabPanel.setActiveTab(windowObject.getInstance());
							cf.TabPanel.theTabPanel.doLayout();
						}
						else {
							cf.TabPanel.theTabPanel.setActiveTab(windowObject.getInstance());
						}
					}
				}
				else {
					cf.Layout.theRegionCenter.remove(cf.TabPanel.theTabPanel);
					cf.TabPanel.setInitialized(false);
					cf.TabPanel.init();
					windowObject.setInitialized(false);
					windowObject.init();
					cf.TabPanel.theTabPanel.add(windowObject.getInstance());
					cf.Layout.theRegionCenter.add(cf.TabPanel.theTabPanel);
					cf.Layout.theRegionCenter.doLayout();
				}
				
			}
			// if user has no rights
			else {
				if (node.disabled == true) {
					Ext.MessageBox.alert('<?php echo __('Error',null,'layout'); ?>','<?php echo __('Insufficient rights',null,'layout');?>');
				}
			}
			
		});
		
		
	}
	
	
	
};}();