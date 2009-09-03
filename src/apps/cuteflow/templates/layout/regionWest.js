/**
* Class creates the Navigation for the west Region.
* Class also Handles the Click Functionality and adds Navigation Clicks to TabPanel
*
*/
cf.Navigation = function(){return {

	
	isInitialized                 : false,
	theAccordion            	  : false, // stores the left Navigationpabel

	
	/*********************************/
	
	init: function () {
		this.initAccordion();
		this.initTree();
		
	},
	
	initTree: function () {
		var url =  '<?php echo url_for('menue/loadMenue')?>';
		Ext.Ajax.request({  
			url : url,
			success: function(objServerResponse){
				theJsonTreeData = Ext.util.JSON.decode(objServerResponse.responseText);
				cf.Navigation.initNavigation(theJsonTreeData);
			}
		});
	},

	initNavigation: function (theJsonTreeData) {
		for(var a=0;a<theJsonTreeData.result.length;a++) {
			var panel = new Ext.Panel({
                title: '<table><tr><td><div id="' + theJsonTreeData.result[a].usermodule.icon + '"></div></td><td style="font-size:15px;">&nbsp;&nbsp;<b>'+theJsonTreeData.result[a].usermodule.translation+'</b></td></tr></table>',
				collapsed: true
            });
            var tree = new Ext.tree.TreePanel({
				frame: false,
				width: 230,
				animate: true,
			    enableDD: true,
				bodyStyle:'padding:5px;',
				rootVisible: false,
				border: false,
				expanded: true,
				id: theJsonTreeData.result[a].usermodule.id
        	});
        	var root = new Ext.tree.TreeNode({
        		text: 'root',
        		expanded: true
        	});


        	for (var b=0;b<theJsonTreeData.result[a].usermodule.usergroup.length;b++) {
        		var myTreeItem = theJsonTreeData.result[a].usermodule.usergroup[b];

        		root.appendChild({
					leaf: true,
					id: myTreeItem.object,
					disabled: myTreeItem.disabled,
					iconCls: myTreeItem.icon,
					//text:  '&nbsp;<span style="font-size:13px;">' + myTreeItem.translation + '</span>',
					text:  '&nbsp;<span style="font-size:13px;">' + myTreeItem.translation + '</span>',
					listeners: {
						click: {
							fn:function(node,value) {
								cf.Navigation.handleClick(node);
							}
						}
					}
        		});
        		
        		/*var item = new Ext.tree.TreeNode({
					leaf: true,
					id: myTreeItem.object,
					disabled: myTreeItem.disabled,
					iconCls: myTreeItem.icon,
					text:  '&nbsp;' + myTreeItem.translation,
					listeners: {
						click: {
							fn:function(node,value) {
								cf.Navigation.handleClick(node);
							}
						}
					}
    			});*/
        		//root.appendChild(item);
        	}
        	tree.setRootNode(root);
            panel.add(tree);
			this.theAccordion.add(panel);
		}
		this.theAccordion.doLayout();
	},
	
	
	
	handleClick: function (node) {
		var c = ('cf.'+node.id);
		var windowObject = eval(c);
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
		
		
	},
	
	initAccordion: function () {
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
	}
	
	
	
	
};}();