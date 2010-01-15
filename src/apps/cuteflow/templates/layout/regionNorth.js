/** init tabpanel for region center **/
cf.cuteFlowLogo = function(){return {
	
	thePanel	 		:false,
	isInitialized		:false,
	
	/** init function **/
	init: function () {
		if(this.isInitialized == false) {
			this.isInitialized = true;
			this.thePanel = new Ext.Panel({
				frame:true,
				border: false,
				autoScroll: true,
				width: 'auto',
				html: 'TESTEN',
			    height: 'auto'
			});
		}
	},
	
	
	
	setInitialized: function (value) {
		this.isInitialized = value;
	},
	
	getInstance: function() {
		return this.theTabPanel;
	}
};}();