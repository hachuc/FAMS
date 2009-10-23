<?php echo $this->renderElement('common_js_objects'); ?>

<script type="text/javascript" language="javascript">

/* Asset Category Browser Script*/

var btn_popup_ast_cat = null;
var ast_cat_name = null;
var ast_cat_id = null;
var win_ast_cat;
var asset_cat_data_store = null;

Ext.onReady(function(){

	var categoriesReader = new Ext.data.ArrayReader({}, [
		{name: 'rec_id'},
		{name: 'category_code'},
		{name: 'category_name'},
		{name: 'category_description'}
	]);	
    
	asset_cat_data_store =  new Ext.data.Store({
				reader: categoriesReader
			});

	asset_categories_json = Ext.util.JSON.decode('<?php echo $javascript->object($asset_categories_data); ?>');
	asset_cat_data_store.loadData(asset_categories_json.asset_categories_data);

    grid_asset_cat = new Ext.grid.GridPanel({
						store: asset_cat_data_store,
						columns: [
							{header: 'Category Code', width: 100, sortable: true, dataIndex: 'category_code'},
							{header: 'Category Name', width: 310, sortable: true, dataIndex: 'category_name'}
						],
						height: 350,
						width: 420
					});
					
    grid_asset_cat.on('rowdblclick', function(sm, row_index, r) {
					record = grid_asset_cat.getStore().getAt(row_index);
					cat_name = record.get('category_name');
					ast_cat_id = record.get('rec_id');
					
					ast_cat_name.setValue(cat_name);
					
					win_ast_cat.hide(this);

					btn_popup_ast.setDisabled(false);
				});

    ast_cat_name = new Ext.form.TextField({
				id: 'ast_cat_name',
				validateOnBlur: true,
				invalidText: 'The value in this field is invalid',
				width: 300,
				disabled: true,
				renderTo: 'cnt_asset_cat',
				msgTarget: 'under',
				allowBlank:false
    		});

    btn_popup_ast_cat = new Ext.Button({
							text: '',
							id: 'btn_popup_ast_cat',
							icon: '/img/data_browser_view.png',
							minWidth: 50,
							renderTo: 'cnt_asset_cat_btn'
						});
	
	
	btn_popup_ast_cat.on('click', function() {
        // create the window on the first click and reuse on subsequent clicks
        if(!win_ast_cat){
            win_ast_cat = new Ext.Window({
                applyTo:'cnt_asst_cat_browser',
                layout:'fit',
                width:450,
                height:200,
                closeAction:'hide',
                plain: true,
				title: 'Asset Category Browser',
                items: grid_asset_cat
            });
        }
        win_ast_cat.show(this);
    });


});

</script>

<script type="text/javascript" language="javascript">

/* Asset Browser Script*/

var btn_popup_ast = null;
var ast_name = null;
var ast_id = null;
var win_ast;
var asset_data_store = null;

Ext.onReady(function(){

	var assetsReader = new Ext.data.ArrayReader({}, [
		{name: 'rec_id'},
		{name: 'ast_code'},
		{name: 'ast_name'}
	]);	
    
	asset_data_store =  new Ext.data.Store({
				reader: assetsReader
			});
			
    grid_asset = new Ext.grid.GridPanel({
						store: asset_data_store,
						columns: [
							{header: 'Asset Code', width: 100, sortable: true, dataIndex: 'ast_code'},
							{header: 'Short Name', width: 310, sortable: true, dataIndex: 'ast_name'}
						],
						height: 350,
						width: 420     
					});
					
    grid_asset.on('rowdblclick', function(sm, row_index, r) {
					
					record = grid_asset.getStore().getAt(row_index);
					asset_name = record.get('ast_name');

					ast_name.setValue(asset_name);
					ast_id = record.get('rec_id');
					
					win_ast.hide(this);
					
					get_data_for_browser('D', ast_id, null);
				});

    ast_name = new Ext.form.TextField({
				id: 'ast_name',
				validateOnBlur: true,
				invalidText: 'The value in this field is invalid',
				width: 300,
				disabled: true,
				renderTo: 'cnt_asset',
				msgTarget: 'under',
				allowBlank:false
    		});

    btn_popup_ast = new Ext.Button({
							text: '',
							id: 'btn_popup_ast',
							disabled: true,
							icon: '/img/data_browser_view.png',
							minWidth: 50,
							renderTo: 'cnt_asset_btn'
						});
	
	
	btn_popup_ast.on('click', function() {
	
        // create the window on the first click and reuse on subsequent clicks
        if(!win_ast){
            win_ast = new Ext.Window({
                applyTo:'cnt_asst_browser',
                layout:'fit',
                width:450,
                height:200,
                closeAction:'hide',
                plain: true,
				title: 'Asset Browser',
                items: grid_asset
            });
        }

		// Load data for grid before popup the browser
		get_data_for_browser('A', ast_cat_id, asset_data_store);
        
    });

});

</script>

<script>
function get_data_for_browser(request_type, type_id, grid_data_store) {
	ajaxClass.request({
			   url: '/depreciation/depreciation_report_browsers',
			   params: { 
			   			request_type: request_type,
			   			type_id: type_id
	   			},
			   callback : function(options, success, response) { 
			   				obj = Ext.util.JSON.decode(response.responseText);
			   				
			   							   				// If type is not an asset information request
			   				if(obj.request_type == 'D') {
								document.getElementById('ast_code').innerHTML = obj.grid_data[0].asset_code;
								document.getElementById('ast_desc').innerHTML = obj.grid_data[0].asset_desc;
								document.getElementById('ast_depr_at').innerHTML = obj.grid_data[0].cur_date;
								document.getElementById('ast_com_date').innerHTML = obj.grid_data[0].com_date;
								document.getElementById('ast_org_cost').innerHTML = obj.grid_data[0].org_cost;
								document.getElementById('ast_cur_depr').innerHTML = obj.grid_data[0].cur_tot_depr;
								document.getElementById('ast_nbv').innerHTML = obj.grid_data[0].nbv;
								document.getElementById('ast_anl_depr').innerHTML = obj.grid_data[0].anl_depr;
								document.getElementById('ast_lifespan').innerHTML = obj.grid_data[0].lifespan;
								document.getElementById('ast_sal_val').innerHTML = obj.grid_data[0].sal_val;
			   				} else {
			   					grid_data_store.loadData(obj.grid_data);
			   				}
			   				
			   				// If request is for asset browser
			   				if(obj.request_type == 'A') {
			   					win_ast.show();
			   				}
	   			}
			});
}
</script>
<!-- Containers for popup windows -->
<div id="cnt_asst_cat_browser" class="x-hidden"><!--asset category browser container--></div>
<div id="cnt_asst_browser" class="x-hidden"><!--asset browser container--></div>
<!-- End containers for popup windows -->

<div id="fields_div">
    <div align="center" style="padding: 10px 0px 0px 0px;">
		<table border="0" bgcolor="#ffffff" style="font-weight:bold;color:#15428b;margin-top:5px;border:#8db2e3 2px solid;width:700px;">
			<tr>
				<td align="center">Depreciation Report</td>
			</tr>
		</table>
	</div>
    <div align="center" style="padding: 5px 0px 10px 0px;">
		<table border="0" bgcolor="#ffffff" style="border:#8db2e3 2px solid;width:700px;">
			<tr>
				<td width="50px">&nbsp;</td>
				<td width="150px"> Asset Category	</td>
				<td id="cnt_asset_cat" width="300px"><!--asset category name container--></td>
				<td id="cnt_asset_cat_btn"><!--asset category load button container--></td>
			</tr>
			<tr>
				<td width="50px">&nbsp;</td>
				<td width="150px"> Asset </td>
				<td id="cnt_asset" width="300px"><!--asset name container--></td>
				<td id="cnt_asset_btn"><!--asset load button container--></td>
			</tr>

		</table>
	</div>
</div>
<div id="fields_div" style="margin-top: 20px">
    <div align="center" style="padding: 5px 0px 10px 0px;">
		<table border="0" bgcolor="#ffffff" style="border:#8db2e3 2px solid;width:700px;">
			<tr>
				<td style="height:10px"></td>
			</tr>
			<tr>
				<td width="50px">&nbsp;</td>
				<td width="150px">Asset Code</td>
				<td width="2px">:</td>
				<td width="300px" id="ast_code" style="background-color: #d3e1f1;margin:2px">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width="50px">&nbsp;</td>
				<td width="150px"> Asset </td>
				<td width="2px">:</td>
				<td width="300px" id="ast_desc" style="background-color: #d3e1f1;margin:2px">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width="50px">&nbsp;</td>
				<td width="150px"> Depreciation as At </td>
				<td width="2px">:</td>
				<td width="300px"  id="ast_depr_at" style="background-color: #d3e1f1;margin:2px">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">
					<table width="100%">
						<tr align="center">
							<td>Commencement Date</td>
							<td>Original Cost ($)</td>
							<td>Cur. Deprec. Amount ($)</td>
							<td>Net Book Value ($)</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td align="center" id="ast_com_date" style="background-color: #d3e1f1;margin:2px">&nbsp;</td>
							<td align="center" id="ast_org_cost" style="background-color: #d3e1f1;margin:2px">&nbsp;</td>
							<td align="center" id="ast_cur_depr" style="background-color: #d3e1f1;margin:2px">&nbsp;</td>
							<td align="center" id="ast_nbv" style="background-color: #d3e1f1;margin:2px">&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					<table>
				</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td width="50px">&nbsp;</td>
				<td width="150px">Anual Depreciation</td>
				<td width="2px">:</td>
				<td width="250px" id="ast_anl_depr" style="background-color: #d3e1f1;margin:2px">&nbsp;</td>
				<td>$&nbsp;</td>
			</tr>
			<tr>
				<td width="50px">&nbsp;</td>
				<td width="150px">Lifespan</td>
				<td width="2px">:</td>
				<td width="250px" id="ast_lifespan" style="background-color: #d3e1f1;margin:2px">&nbsp;</td>
				<td>Months&nbsp;</td>
			</tr>
			<tr>
				<td width="50px">&nbsp;</td>
				<td width="150px"> Salvage Value </td>
				<td width="2px">:</td>
				<td width="250px" id="ast_sal_val" style="background-color: #d3e1f1;margin:2px">&nbsp;</td>
				<td>$&nbsp;</td>
			</tr>
		</table>
	</div>
</div>


