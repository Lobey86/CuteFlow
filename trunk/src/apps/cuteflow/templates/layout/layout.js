/**
* Class creates Layout with Region West and Center (2 BorderLayout)
* West = Navigation
* Center = Tabpanel
* When layout is initialized, a welcome tab is shown
*/
cf.Layout = function(){return {
	
	isInitialized              : false,
	theMainLayout              : false, // stores the main layout
	theRegionCenter			   : false, // stores the center Region
	theRegionWest		       : false, // stores the west region
	
	/*********************************/
	
	init: function(){
	if (this.isInitialized == false) {
			this.isInitialized = true;
			this.theMainLayout = new Ext.Viewport({
				id:'MainLayout',
				layout:'border',
				border:false,
				items:[{
					region:'west',
					id: 'westRegion',
					width:240,
					border:true,
					autoScroll:true,
					layout:'fit',
					title:' ',
					bodyStyle:'padding:5px;font-size:11px;background-color:#f4f4f4;',
					collapsible:true,
					split:true,
					collapseMode:'mini'
				},{
					region:'center',
					id: 'centerRegion',
					border:false,
					layout:'fit',							
					bodyStyle:'background-color:#f0f0f0;',
					title:' '
				}]
			});
			cf.Navigation.init();
			cf.TabPanel.init();
			this.theRegionWest =  this.theMainLayout.layout.west.panel;
			this.theRegionCenter =  this.theMainLayout.layout.center.panel;	
			
			this.theRegionWest.add(cf.Navigation.theNavigationPanel);
			cf.TabPanel.theTabPanel.add({title: 'Welcome', closable: 'false', html: 'fewfwe'});
			this.theRegionCenter.add(cf.TabPanel.theTabPanel);
			
			this.theMainLayout.doLayout();
		}
	}
};}();