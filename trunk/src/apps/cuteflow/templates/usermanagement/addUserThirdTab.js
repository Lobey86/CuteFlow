/** class for the third tabpanle to add and remove useragents **/
cf.AddUserThirdTab = function(){return {

	isInitialized 					:false,
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
	
	theRightBottomToolbar			:false,
	theLeftBottomToolbar			:false,
	
	theUniqueId						:0,
	
	
	/**
	* Inits only the tab, that not all data needs to be loaded when opening the new/edit user window
	*
	*
	* @param boolean new_flag, 1 if new user, 0 if edit user
	* @param int id, id is set, if in edit mode
	*
	*/
	init: function (new_flag, id) {
		this.initPanel(new_flag, id);
	},
	
	

	/**
	* When Tab is clicked on tabpanel, the data gets loaded and isInitialized flag is set to true,
	* that data is not reloaded when switching window.
	* 
	* @param boolean new_flag, 1 if new user, 0 if edit user
	* @param int id, id is set, if in edit mode
	*/
	
	initPanel:function(new_flag, id) {
		this.theThirdPanel = new Ext.Panel({
			title: '<?php echo __('Useragent',null,'usermanagementpopup'); ?>',
			frame: true,
			plain: true,
			layout: 'column',
			labelWidth : 200,
			width: 500,
			height: 530,
			listeners:{
 			   activate : function(){ 
					if(cf.AddUserThirdTab.isInitialized == false) {
	 			   		cf.AddUserThirdTab.isInitialized = true;
	 			   		cf.AddUserThirdTab.initLeftGridStore();
						cf.AddUserThirdTab.initLeftToolbar();
						cf.AddUserThirdTab.initRightToolbar();
						cf.AddUserThirdTab.initLeftGridCM();
						cf.AddUserThirdTab.initLeftGrid();
						cf.AddUserThirdTab.initRightGridStore(new_flag, id);
						cf.AddUserThirdTab.initRightGridCM();
						cf.AddUserThirdTab.initRightGrid();
						cf.AddUserThirdTab.theThirdPanel.add(cf.AddUserThirdTab .theLeftGrid);
						cf.AddUserThirdTab.theThirdPanel.add(cf.AddUserThirdTab .theRightGrid);
					}
		   		} 
			}
		})
	},
	
	/** init store for left grid, that contains all users **/
	initLeftGridStore: function () {	
		this.theLeftUserStore = new Ext.data.JsonStore({
				root: 'result',
				url: '<?php echo url_for('usermanagement/LoadLeftGrid')?>',
				fields: [
					{name: 'id'},
					{name: 'text'}
				]
		});	
		cf.AddUserThirdTab.theLeftUserStore.load();
		
	},
	
	
	/** 
	*
	* init store for right grid, 
	* that contains all useragent, only loaded on startup in edit mode 
	*
	* @param boolean new_flag, 1 if new user, 0 if edit user
	* @param int id, id is set, if in edit mode
	**/
	
	initRightGridStore: function (new_flag, id) {
		this.theRightUserStore = new Ext.data.JsonStore({
				root: 'result',
				url: '<?php echo url_for('usermanagement/LoadRightGrid')?>/id/'+id,
				fields: [
					{name: 'unique_id'},
				    {name: 'user_id'},
					{name: 'text'}
				]
		});	
		if(new_flag != 1) {
			cf.AddUserThirdTab.theRightUserStore.load();
		}
	},
	
	
	/** ColumnModel for useragent(right) grid **/
	initRightGridCM: function () {
		this.theRightCM	=  new Ext.grid.ColumnModel([
			{header: "<?php echo __('Name',null,'usermanagementpopup'); ?>", width: 200, sortable: true, dataIndex: 'text', css : "text-align : left;font-size:12px;align:center;"},
			{header: "<?php echo __('Action',null,'usermanagementpopup'); ?>", width: 60, sortable: true, dataIndex: 'unique_id', css : "text-align : left;font-size:12px;align:center;", tooltip: '<?php echo __('Remove user',null,'usermanagementpopup'); ?>' ,renderer:cf.AddUserThirdTab.deleteUseragentButton}
		]);
	
	},
	
	/** ColumnModel for user(left) grid **/
	initLeftGridCM: function () {
		this.theLeftCM	=  new Ext.grid.ColumnModel([
			{header: "<?php echo __('Name',null,'usermanagementpopup'); ?>", width: 255, sortable: true, dataIndex: 'text', css : "text-align : left;font-size:12px;align:center;height:26px;"}
		]);
	
	},
	
	/** init useragent (right) grid **/
	initRightGrid:function () {
		this.theRightGrid = new Ext.grid.GridPanel({
			frame:false,
			autoScroll: true,
			collapsible:false,
			closable: false,
			ddGroup : 'rightGridDDGroup',
			allowContainerDrop : true,
            ddText: '<?php echo __('Drag Drop to change order',null,'usermanagementpopup'); ?>', 
			title: '<table><tr><td><img src="/images/icons/user_gray.png" /></td><td><?php echo __('Useragent',null,'usermanagementpopup'); ?></td></tr></table>',
			height:'false',
			width:290,
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
		
		/** render Drag and Drog and Sort functionality to right grid **/
		this.theRightGrid.on('render', function(grid) {
			var secondGridDropTargetEl = grid.getView().scroller.dom;
			var secondGridDropTarget = new Ext.dd.DropTarget(secondGridDropTargetEl, {
					ddGroup    : 'rightGridDDGroup',
					copy:false,
					notifyDrop  : function(ddSource, e, data){ // when droppping a container in the right grid
						if (ddSource.grid != grid){
							for(var a=0;a<data.selections.length;a++) { // if data is from left grid, add it to store. 
								var item = data.selections[a].data;
								var Rec = Ext.data.Record.create({name: 'unique_id'},{name: 'user_id'}, {name: 'text'});
								grid.store.add(new Rec({unique_id: cf.AddUserThirdTab.theUniqueId++, user_id: item.id,text: item.text})); // important to add unique ID's
							}
						}
						else { // if data is coming from right, then reorder is done.
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
									else { // moves items to last position
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
	
	
	/** inits the grid for all users (left) **/
	initLeftGrid:function () {		
		this.theLeftGrid = new Ext.grid.GridPanel({
			frame:false,
			autoScroll: true,
			collapsible:false,
			closable: false,
            ddText: '<?php echo __('Move right please',null,'usermanagementpopup'); ?>',  
			title: '<table><tr><td><img src="/images/icons/user_suit.png" /></td><td><?php echo __('User',null,'usermanagementpopup'); ?></td></tr></table>',
			height: 490,
			width:290,
			border: true,
			style: 'margin-left:8px;margin-right:20px;border:1px solid #99bbe8;',
			ddGroup : 'rightGridDDGroup',
			plain: false,
			enableDragDrop:true,
			expand: true,
			store: this.theLeftUserStore,
			tbar: this.theLeftToolbar,
			cm: this.theLeftCM
		});
		
	},
	
	/** render Delete Button to right grid in each row **/
	deleteUseragentButton: function (data, cell, record, rowIndex, columnIndex, store, grid) {
			cf.AddUserThirdTab.theUniqueId++;
			var id = record.data['unique_id'];
			var btn = cf.AddUserThirdTab.createDeleteButton.defer(1,this, [id]);
			return '<center><table><tr><td><div id="remove_useragent'+ id +'"></div></td></tr></table></center>';
	},
	
	/**
	* build the label and the button for the grid
	*
	*
	*@param int id, id of the record
	*/
	createDeleteButton: function (id) {
		var btn_edit = new Ext.form.Label({
			renderTo: 'remove_useragent' + id,
			html: '<span style="cursor:pointer;"><img src="/images/icons/user_delete.png" /></span>',
			listeners: {
				render: function(c){
					  c.getEl().on({
						click: function(el){
							var item = cf.AddUserThirdTab.theRightGrid.store.findExact('unique_id', id );
							cf.AddUserThirdTab.theRightGrid.store.remove(item);
						},
					scope: c
				});
				}
			}
		});
	},
	
	
	/** init toolbar for left (user) grid, with livesearch and clear button **/
	initLeftToolbar:function () {
		this.theLeftToolbar = new Ext.Toolbar({
			items: [{
				xtype: 'textfield',
				id: 'useragentleft_textfield',
				emptyText:'<?php echo __('Search for User...',null,'usermanagementpopup'); ?>',
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
				tooltip: '<?php echo __('Clear field',null,'usermanagementpopup'); ?>',
				handler: function () {
					Ext.getCmp('useragentleft_textfield').setValue();
					cf.AddUserThirdTab.theLeftGrid.store.filter('text', '');
                }
			}
			]
		});
	},
	
	/** init toolbar for right (useragent) grid, with livesearch and clear button **/
	initRightToolbar: function () {
		this.theRightToolbar = new Ext.Toolbar({
			items: [{
				xtype: 'textfield',
				id: 'useragentright_textfield',
				emptyText:'<?php echo __('Search for Useragent...',null,'usermanagementpopup'); ?>',
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
				tooltip: '<?php echo __('Clear field',null,'usermanagementpopup'); ?>',
				handler: function () {
					Ext.getCmp('useragentright_textfield').setValue();
					cf.AddUserThirdTab.theRightGrid.store.filter('text', '');
                }
			}
			]
		});

	}
};}();

/** override findExact method, to return a dataset **/
Ext.override( Ext.data.Store, {
    findExact: function( fld, val ) {
        var hit = null;
        this.each( function(rec) { if( rec.get(fld) == val ) { hit = rec; return false; }; } );
        return hit;
    }
});