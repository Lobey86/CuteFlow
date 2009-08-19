/**
* Class creates Layout with Region West and Center (2 BorderLayout)
* West = Navigation
* Center = Tabpanel
* When layout is initialized, a welcome tab is shown
*/
cf.Layout = function(){return {
	
	/*********************************/
	
	init: function(){
		cf.ComboBox.init();
		cf.Textfield.init();
		cf.Window.init();
		

		cf.Textfield.thePanel.add(cf.Textfield.theUsernameField);
		cf.Textfield.thePanel.add(cf.Textfield.theUserpasswordField);
		cf.Textfield.thePanel.add(cf.ComboBox.theComboBox);
		cf.Window.theWindow.add(cf.Textfield.thePanel);
		cf.Window.theWindow.add(cf.Textfield.theHiddenField);
		cf.Window.theWindow.show();
	}
};}();