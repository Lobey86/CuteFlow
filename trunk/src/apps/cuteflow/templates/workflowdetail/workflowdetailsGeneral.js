cf.workflowdetailsGeneral = function(){return {
	
	theFieldset					:false,
	theMailinglistLabel			:false,
	theSenderLabel				:false,
	theNameLabel				:false,
	theHistoryCombo				:false,
	
	init:function(data) {
		this.initFieldset(data.workflow);
		this.initMailinglist(data.mailinglist);
		this.initSender(data.sender);
		this.initHistoryCombo(data.version);
		this.initName(data.workflow);
		
		this.theFieldset.add(this.theMailinglistLabel);
		this.theFieldset.add(this.theSenderLabel);
		this.theFieldset.add(this.theNameLabel);
		this.theFieldset.add(this.theHistoryCombo);
	},
	
	
	initHistoryCombo: function (items) {
		this.theHistoryCombo = new Ext.form.ComboBox({ 	
			fieldLabel: '<img src="/images/icons/clock.png" /> <?php echo __('Revisions',null,'workflowmanagement'); ?>',
			editable:false,
			labelWidth: 200,
			triggerAction: 'all',
			foreSelection: true,
			mode: 'local',
			store: new Ext.data.SimpleStore({
				 fields:['versionid','text']
			}),
			valueField:'versionid',
			displayField:'text',
			width:150
		});
		var defaultId = '';
		for(var a=0;a<items.length;a++) {
			var record = items[a];
			var Rec = Ext.data.Record.create({name: 'versionid'},{name: 'text'});
			this.theHistoryCombo.store.add(new Rec({versionid: record.versionid, text: record.text}));
			if(record.activeversion == 1) {
				defaultId = record.versionid;
			}
		}
		this.theHistoryCombo.setValue(defaultId);
	},
	
	initFieldset: function (workflowname) {
		this.theFieldset = new Ext.form.FieldSet({
			title: '<?php echo __('General informations',null,'workflowmanagement'); ?> : ' + workflowname,
			allowBlank: false,
			style: 'margin-top:5px;margin-left:10px;',
			width: cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 100,
			height: 150
		});
	},
	
	initMailinglist: function (name) {
		this.theMailinglistLabel = new Ext.form.Label({
			html: '<table><tr><td><img src="/images/icons/group.png" /> </td><td width="100"><?php echo __('Mailinglist:',null,'workflowmanagement'); ?></td><td> ' + name + '</td></tr></table>',
			style: 'font-size:12px;'
		});

	}, 
	
	initSender: function (name) {
		this.theSenderLabel = new Ext.form.Label({
			html: '<table><tr><td><img src="/images/icons/user.png" /> </td><td width="100"><?php echo __('Sender:',null,'workflowmanagement'); ?></td><td> ' + name + '</td></tr></table>',
			style: 'font-size:12px;'
		});
		
	},
	
	initName: function (name) {
		this.theNameLabel = new Ext.form.Label({
			html: '<table><tr><td><img src="/images/icons/report.png" /></td><td width="100"><?php echo __('Workflowname:',null,'workflowmanagement'); ?></td><td> ' + name + '</td></tr></table>',
			style: 'font-size:12px;'
		});
	}
	
	
	
	
	
	
	
};}();