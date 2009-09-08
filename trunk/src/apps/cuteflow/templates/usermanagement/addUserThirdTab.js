cf.AddUserThirdTab = function(){return {

	theThirdPanel					:false,
	
	theLeftUserStore				:false,
	theRightUserStore				:false,
	
	theLeftCM						:false,
	theRightCM						:false,
	
	theRightToolbar					:false,
	theLeftToolbar					:false,
	
	theLeftGrid						:false,
	theRightGrid					:false,
	
	theViewPort						:false,
	
	theUniqueId						:0,
	
	init: function (new_flag, id) {
		this.initPanel();
		this.initLeftGridStore();
		
		this.initLeftToolbar();
		this.initRightToolbar();
		
		
		this.initLeftGridCM();
		this.initLeftGrid();
		
		this.initRightGridStore();
		this.initRightGridCM();
		this.initRightGrid();
		
		this.theThirdPanel.add(this.theLeftGrid);
		this.theThirdPanel.add(this.theRightGrid);

	},
	
	

	
	initPanel:function() {
		this.theThirdPanel = new Ext.Panel({
			title: 'Stellvertreter',
			frame: true,
			plain: true,
			layout: 'column',
			labelWidth : 200,
			width: 500,
			height: 530
		})
	},
	
	initLeftGridStore: function () {	
		this.theLeftUserStore = new Ext.data.JsonStore({
				totalProperty: 'total',
				root: 'result',
				url: '<?php echo url_for('usermanagement/LoadRightTree')?>',
				fields: [
					{name: 'database_id'},
					{name: 'text'}
				]
		});	
		cf.AddUserThirdTab.theLeftUserStore.load();
	},
	
	initRightGridStore: function () {
		this.theRightUserStore = new Ext.data.JsonStore({
				totalProperty: 'total',
				root: 'result',
				url: '<?php echo url_for('usermanagement/LoadRightTree')?>',
				fields: [
					{name: 'unique_id'},
				    {name: 'database_id'},
					{name: 'text'}
				]
		});	
		//cf.AddUserThirdTab.theRightUserStore.load();
	},
	
	
	initRightGridCM: function () {
		this.theRightCM	=  new Ext.grid.ColumnModel([
			{header: "Name", width: 150, sortable: true, dataIndex: 'text', css : "text-align : left;font-size:12px;align:center;"},
			{header: "Aktion", width: 60, sortable: true, dataIndex: 'unique_id', css : "text-align : left;font-size:12px;align:center;", renderer:cf.AddUserThirdTab.deleteUseragentButton}
		]);
	
	},
	
	
	initLeftGridCM: function () {
		this.theLeftCM	=  new Ext.grid.ColumnModel([
			{header: "Name", width: 200, sortable: true, dataIndex: 'text', css : "text-align : left;font-size:12px;align:center;height:26px;"}
		]);
	
	},
	
	
	initRightGrid:function () {
		this.theRightGrid = new Ext.grid.GridPanel({
			frame:false,
			id: 'rightusergrid',
			autoScroll: true,
			collapsible:false,
			closable: false,
			ddGroup : 'rightGridDDGroup',
			allowContainerDrop : true,
            ddText: 'drag and drop to change order', 
			title: '<table><tr><td><img src="/images/icons/user_gray.png" /></td><td>Stellvertreter</td></tr></table>',
			height:'false',
			width:250,
			height: 490,
			border: true,
			style: 'border:1px solid #99bbe8;',
			plain: false,
			autoHeight : false,
			stripRows: true,
			enableDragDrop:true,
			store: this.theRightUserStore,
			tbar: this.theRightToolbar,
			cm: this.theRightCM
		});
		
		this.theRightGrid.on('render', function(grid) {
			var secondGridDropTargetEl = grid.getView().scroller.dom;
			var secondGridDropTarget = new Ext.dd.DropTarget(secondGridDropTargetEl, {
					ddGroup    : 'rightGridDDGroup',
					copy:false,
					notifyDrop  : function(ddSource, e, data){
						if (ddSource.grid != grid){
							for(var a=0;a<data.selections.length;a++) {
								var item = data.selections[a].data;
								var Rec = Ext.data.Record.create({name: 'unique_id'},{name: 'database_id'}, {name: 'text'});
								grid.store.add(new Rec({unique_id: cf.AddUserThirdTab.theUniqueId++, database_id: item.database_id,text: item.text}));
							}
						}
						else {
							var sm = grid.getSelectionModel();
							var rows = sm.getSelections();
							var cindex = ddSource.getDragData(e).rowIndex;
							for (i = 0; i < rows.length; i++) {
								rowData = grid.store.getById(rows[i].id);
								if(!this.copy) {
									grid.store.remove(grid.store.getById(rows[i].id));
									if(cindex) {
										grid.store.insert(cindex,rowData);
									}
									else {
										var totalItems = grid.store.data.length;
										grid.store.insert(totalItems,rowData);
									}
									
								}
							}
						}
						return true;
					}
			});
		});
	},
	
	

	initLeftGrid:function () {		
		this.theLeftGrid = new Ext.grid.GridPanel({
			frame:false,
			id: 'leftusergrid',
			autoScroll: true,
			collapsible:false,
			closable: false,
			width: 'auto',
            ddText: 'drag and drop to change order',  
			title: '<table><tr><td><img src="/images/icons/user_suit.png" /></td><td>Benutzer</td></tr></table>',
			height: 490,
			width:250,
			border: true,
			style: 'margin-right:20px;border:1px solid #99bbe8;',
			ddGroup : 'rightGridDDGroup',
			plain: false,
			enableDragDrop:true,
			store: this.theLeftUserStore,
			tbar: this.theLeftToolbar,
			cm: this.theLeftCM
		});
		
	},
	
	deleteUseragentButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {
			
			var id = record.data['unique_id'];
			cf.AddUserThirdTab.createDeleteButton.defer(200,this, [id]);
			return '<center><table><tr><td><div id="remove_useragent'+ id +'"></div></td></tr></table></center>';
	},
	
	createDeleteButton: function (id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'remove_useragent' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/user_delete.png" /></span>',
			tooltip: 'Entferne',
			id: 'remove_' + id,
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							id = c.id;
							id = id.replace('remove_','');
							var item = cf.AddUserThirdTab.theRightGrid.store.findExact('unique_id', id );
							cf.AddUserThirdTab.theRightGrid.store.remove(item);
						},
					scope: c
				});
				}
			}
		});
	},
	
	initLeftToolbar:function () {
		this.theLeftToolbar = new Ext.Toolbar({
			//style:'margin-bottom:5px;',
			items: [{
				xtype: 'textfield',
				id: 'useragentleft_textfield',
				emptyText:'Search for a Useragent...',
				width: 200,
				enableKeyEvents: true,
				listeners: {
					keyup: function(el, type) {
						var grid = cf.AddUserThirdTab.theLeftGrid;
						grid.store.filter('text', el.getValue());
					}
				}
			},'-',{
				icon: '/images/icons/delete.png',
				tooltip: 'clear Field',
				handler: function () {
					Ext.getCmp('useragentleft_textfield').setValue();
					cf.AddUserThirdTab.theLeftGrid.store.filter('text', '');
                }
			}
			]
		});
	},
	
	initRightToolbar: function () {
		//style:'margin-bottom:5px;',
		this.theRightToolbar = new Ext.Toolbar({
			items: [{
				xtype: 'textfield',
				id: 'useragentright_textfield',
				emptyText:'Search for a Useragent...',
				width: 200,
				enableKeyEvents: true,
				listeners: {
					keyup: function(el, type) {
						var grid = cf.AddUserThirdTab.theRightGrid;
						grid.store.filter('text', el.getValue());
					}
				}
		    },'-',{
				icon: '/images/icons/delete.png',
				tooltip: 'clear Field',
				handler: function () {
					Ext.getCmp('useragentright_textfield').setValue();
					f.AddUserThirdTab.theRightGrid.store.filter('text', '');
                }
			}
			]
		});
	}
};}();

Ext.override( Ext.data.Store, {
    findExact: function( fld, val ) {
        var hit = null;
        this.each( function(rec) { if( rec.get(fld) == val ) { hit = rec; return false; }; } );
        return hit;
    }
});