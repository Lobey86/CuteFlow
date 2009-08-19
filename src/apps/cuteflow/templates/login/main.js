/**
* Is main Entry Point for Application
* Class sets namesapce and calls Layout Class to initalize the layout.
*/
Ext.namespace('cf');
Ext.BLANK_IMAGE_URL = '/images/default/s.gif';




cf.Main = function(){return {
	/*********************************/
	
	init: function(){
		cf.Layout.init();
	}
};}();

Ext.onReady(cf.Main.init, cf.Main);