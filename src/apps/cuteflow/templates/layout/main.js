/**
* Is main Entry Point for Application
* Class sets namesapce and calls Layout Class to initalize the layout.
*/
Ext.namespace('cf');
Ext.BLANK_IMAGE_URL = '/images/default/s.gif';

cf.Main = function(){return {
	
	theUserRole                : false,
	theLoadingMask			   : false,

	
	/*********************************/
	
	/** call to init main layout **/
	init: function(){
		cf.Layout.init();
	}
};}();

Ext.lib.Event.resolveTextNode = Ext.isGecko ? function(node){
	if(!node){
		return;
	}
	var s = HTMLElement.prototype.toString.call(node);
	if(s == '[xpconnect wrapped native prototype]' || s == '[object XULElement]'){
		return;
	}
	return node.nodeType == 3 ? node.parentNode : node;
} : function(node){
	return node && node.nodeType == 3 ? node.parentNode : node;
};
Ext.QuickTips.init();
Ext.onReady(cf.Main.init, cf.Main);