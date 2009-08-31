/**
* Class creates the Navigation for the west Region.
* Navigation type is a TreeTabpanel.
* Class also Handles the Click Functionality and adds Navigation Clicks to TabPanel
*
*/
cf.Navigation = function(){return {

	
	isInitialized                 : false,
	theAccordion            	  : false, // stores the left Navigationpabel
	theFirstItem				  : false,
	theSecondItem				  : false,
	theThirdItem				  : false,
	theFirstItemTree			  : false,
	theSecondItemTree			  : false,
	theThirdItemTree			  : false,
	
	/*********************************/
	
	init: function () {
		this.initAccordionLayout();
		this.initFirstItems();
		this.initSecondItems();
		this.initThirdItems();
		this.theFirstItem.add(this.theFirstItemTree);
		this.theSecondItem.add(this.theSecondItemTree);
		this.theThirdItem.add(this.theThirdItemTree);
		//this.theAccordion.setActiveItem(this.theThirdItem);
	},
	
	initFirstItems: function () {
		this.theFirstItemTree = new Ext.tree.TreePanel({
			width: 230,
			frame: false,
			bodyStyle:'padding:5px;font-size:11px;background-color:#f4f4f4;',
			rootVisible: false,
			loader: new Ext.tree.TreeLoader(),
			border: false,
			id: 'firstItem',
			root: new Ext.tree.AsyncTreeNode({
				children: [{
					icon: '/images/icons/note_go.png',
					leaf: true,
					text: '<span style="font-size:14px;">Dokumentenuml&auml;ufe</span>'
				}]
			})
		});
	},
	
	initSecondItems: function () {
		this.theSecondItemTree = new Ext.tree.TreePanel({
			width: 230,
			frame: false,
			bodyStyle:'padding:5px;font-size:11px;background-color:#f4f4f4;',
			rootVisible: false,
			loader: new Ext.tree.TreeLoader(),
			border: false,
			id: 'secondItem',
			root: new Ext.tree.AsyncTreeNode({
				children: [{
					icon: '/images/icons/note_go.png',
					leaf: true,
					text: '<span style="font-size:14px;">Dokumentenuml&auml;ufe</span>'
				}]
			})
		});
	},
	
	initThirdItems: function () {
		this.theThirdItemTree = new Ext.tree.TreePanel({
			width: 230,
			frame: false,
			bodyStyle:'padding:5px;font-size:11px;background-color:#f4f4f4;',
			rootVisible: false,
			loader: new Ext.tree.TreeLoader(),
			border: false,
			id: 'thirdItem',
			root: new Ext.tree.AsyncTreeNode({
				children: [{
					icon: '/images/icons/group.png',
					leaf: true,
					id: 'nodeUser',
					text: '<span style="font-size:14px;"><?php echo __('User management',null,'layout'); ?></span>'
				},{
					icon: '/images/icons/user_edit.png',
					leaf: true,
					text: '<span style="font-size:14px;"><?php echo __('My Profile',null,'layout'); ?></span>'
				},{
					icon: '/images/icons/wrench.png',
					leaf: true,
					text: '<span style="font-size:14px;"><?php echo __('System settings',null,'layout'); ?></span>'
				},{
					icon: '/images/icons/cog_add.png',
					id: 'nodeRoleManagement',
					leaf: true,
					text: '<span style="font-size:14px;"><?php echo __('Userrole management',null,'layout'); ?></span>'
				},{
					icon: '/images/icons/note_go.png',
					leaf: true,
					text: '<span style="font-size:14px;"><?php echo __('Send Message',null,'layout'); ?></span>'
				}]
			})
		});
		this.theThirdItemTree.on('click', function(node){
			cf.Navigation.handleTreeClick(node);
		});
	},
	
	initAccordionLayout: function () {
		if (this.isInitialized == false) {
			this.isInitialized = true;
			
			this.theFirstItem = new Ext.Panel({
                title: '<table><tr><td><img src="/images/icons/world.png" /></td><td style="font-size:15px;">&nbsp;&nbsp;<b>Uml&auml;ufe</b></td></tr></table>',
				collapsed: true
            });

            this.theSecondItem = new Ext.Panel({
                title: '<table><tr><td><img src="/images/icons/brick.png" /></td><td style="font-size:15px;">&nbsp;&nbsp;<b>Management</b></td></tr></table>',
				collapsed: true
            });

            this.theThirdItem = new Ext.Panel({
                title: '<table><tr><td><img src="/images/icons/tux.png" /></td><td style="font-size:15px;">&nbsp;&nbsp;<b>Verwaltung</b></td></tr></table>'
            });

			this.theAccordion = new Ext.Panel({
                margins:'5 0 5 5',
                split:true,
                width: 240,
				layoutConfig: {
					titleCollapse: true,
					animate: true
				},
				layout:'accordion'
			
            });
			this.theAccordion.add([cf.Navigation.theFirstItem, cf.Navigation.theSecondItem, cf.Navigation.theThirdItem]);
		}
	},
	
	handleTreeClick: function (node) {
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
	}
};}();