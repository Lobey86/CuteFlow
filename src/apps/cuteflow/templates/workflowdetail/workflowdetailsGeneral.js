cf.workflowdetailsGeneral = function(){return {
	
	theFieldset					:false,
	theMailinglistLabel			:false,
	theSenderLabel				:false,
	theNameLabel				:false,
	theHistoryCombo				:false,
	theContentLabel				:false,
	
	init:function(data,workflowtemplate_id) {
		this.initFieldset(data.workflow);
		this.initLabel(data.workflow, data.mailinglist, data.sender, data.content, data.version,workflowtemplate_id)
		this.theFieldset.add(this.theLabel);
	},
	
	
	initLabel: function (workflow, mailinglist, sender, content, version, workflowtemplate_id) {
		this.theLabel = new Ext.form.Label({
			html: '<table><tr height="25"><td><img src="/images/icons/group.png" /></td><td width="150"><?php echo __('Mailinglist',null,'workflowmanagement'); ?>:</td><td>'+mailinglist+'</td></tr><tr height="25"><td><img src="/images/icons/user.png" /></td><td width="150"><?php echo __('Sender',null,'workflowmanagement'); ?>:</td><td>'+sender+'</td></tr><tr height="25"><td><img src="/images/icons/report.png" /></td><td width="150"><?php echo __('Worklfow name',null,'workflowmanagement'); ?>:</td><td>'+workflow+'</td></tr><tr height="25"><td><img src="/images/icons/script.png" /></td><td width="150"><?php echo __('Description',null,'workflowmanagement'); ?>:</td><td>'+content+'</td></tr><tr height="25"><td><img src="/images/icons/clock.png" /></td><td width="150"><?php echo __('Revision',null,'workflowmanagement'); ?>:</td><td><div id="detailsversion'+workflowtemplate_id+'"></div></td></tr></table>',
			style: 'font-size:12px;'
		});
		this.theLabel.on('afterrender', function(grid) {
			cf.workflowdetailsGeneral.initHistoryCombo(version, workflowtemplate_id);
		});
		
	},
	
	
	
	initHistoryCombo: function (items, workflowtemplate_id) {
		this.theHistoryCombo = new Ext.form.ComboBox({ 	
			editable:false,
			renderTo: 'detailsversion'+workflowtemplate_id,
			triggerAction: 'all',
			foreSelection: true,
			mode: 'local',
			store: new Ext.data.SimpleStore({
				 fields:['versionid','text']
			}),
			valueField:'versionid',
			displayField:'text',
			width:180
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
			autoScroll: true,
			style: 'margin-top:5px;margin-left:10px;',
			width: cf.Layout.theRegionWest.getWidth() +  cf.Layout.theRegionCenter.getWidth() - 100,
			height: 200
		});
	}
	
	
	
	
	
	
	
};}();