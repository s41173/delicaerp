<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> PROJECT - PRJ-00<?php echo isset($pono) ? $pono : ''; ?></title>

<style type="text/css" media="all">

	body{ font-size:0.75em; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:21cm; height:11.6cm; border:0pt solid #000;}
	.clear{ clear:both;}
	#tablebox{ width:20cm; border:0pt solid red; float:left; margin:0cm 0 0 0.4cm;}
		
	#logobox{ width:5.5cm; height:1cm; border:0pt solid blue; margin:0.8cm 0 0 0.5cm; float:left;}
	#venbox{ border:0pt solid green; margin:0.0cm 0cm 0.8cm 0.5cm; float:left; width:9.5cm;}
	#venbox2,#venbox3{ border:0pt solid green; margin:0.0cm 0.5cm 0.6cm 0.5cm; float:right; width:8.5cm;}
	#title{ text-align:center; font-size:17pt;}
	h4{ font-size:14pt; margin:0;}
	
	table.product
	{ border-collapse:collapse; width:100%; }
	
	table.product,table.product th
	{	border: 1px solid black; font-size:13px; font-weight:bold; padding:4px 0 4px 0; }
	
	table.product,table.product td
	{	border: 1px solid black; font-size:12px; font-weight:normal; padding:3px 0 3px 0; text-align:center; }
	
	table.product td.left { text-align:left; padding:3px 5px 3px 10px; }
	table.product td.right { text-align:right; padding:3px 10px 3px 5px; }
    table.product td.signature { width:2cm; }
	table.product a{ text-decoration:none;}
	table.product a:hover{ text-decoration:underline; color:#900;}
	
</style>

<link rel="stylesheet" href="<?php echo base_url().'js/jxgrid/' ?>css/jqx.base.css" type="text/css" />
    
	<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxcore.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxdata.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxcheckbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxlistbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxmenu.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.filter.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.columnsresize.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.columnsreorder.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.aggregates.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxdata.export.js"></script>
	<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.export.js"></script>
    
    <script type="text/javascript">
	
        $(document).ready(function () {
          
			var rows = $("#table tbody tr");
                // select columns.
                var columns = $("#table thead th");
                var data = [];
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var datarow = {};
                    for (var j = 0; j < columns.length; j++) {
                        // get column's title.
                        var columnName = $.trim($(columns[j]).text());
                        // select cell.
                        var cell = $(row).find('td:eq(' + j + ')');
                        datarow[columnName] = $.trim(cell.text());
                    }
                    data[data.length] = datarow;
                }
                var source = {
                    localdata: data,
                    datatype: "array",
                    datafields:
                    [
                        { name: "No", type: "string" },
						{ name: "Assembly", type: "string" },
						{ name: "Product", type: "string" },
						{ name: "Qty", type: "number" },
						{ name: "Unit", type: "string" },
						{ name: "Desc", type: "string" }
                    ]
                };
			
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#jqxgrid").jqxGrid(
            {
                width: '100%',
				source: dataAdapter,
				sortable: true,
				filterable: true,
				pageable: true,
				altrows: true,
				enabletooltips: true,
				filtermode: 'excel',
				autoheight: true,
				columnsresize: true,
				columnsreorder: true,
				showstatusbar: true,
				statusbarheight: 30,
				showaggregates: true,
				autoshowfiltericon: false,
                columns: [
                  { text: 'No', dataField: 'No', width: 60 },
				  { text: 'Assembly', dataField: 'Assembly', width : 100, cellsalign: 'center' },
				  { text: 'Product', dataField: 'Product', cellsalign: 'left' },
 				  { text: 'Qty', datafield: 'Qty', width: 70, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Unit', dataField: 'Unit', width : 110, cellsalign: 'center' },
				  { text: 'Desc', dataField: 'Desc', width : 110, cellsalign: 'center' }
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['10', '20', '30', '40', '50', '100', '200', '300', '500']}); 
			
			$("#table").hide();
			
		// end jquery	
        });
    </script>


</head>

<body bgcolor="#FFFFFF"; onload="">

<div id="container">
	
    <center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0;"> <?php echo $address; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
	
    <p style="padding:0; font-weight:bold; font-size:1.3em; text-align:center;"> PROJECT ORDER </p>
    
    <div id="venbox">
	<table width="100%" style="font-size:1em; margin:0; text-align:left; font-weight:bold;">
	  <tr> <td> No </td> <td>:</td> <td> PRJ-00<?php echo isset($pono) ? $pono : ''; ?> </td> </tr>
      <tr> <td> Date </td> <td>:</td> <td> <?php echo tglin($date); ?> </td> </tr>
      <tr> <td> Notes </td> <td>:</td> <td> <?php echo isset($note) ? $note : ''; ?> </td> </tr>
	  <tr> <td> Customer </td> <td>:</td> <td> <?php echo isset($customer) ? $customer : ''; ?> </td> </tr>
      <tr> <td> Location </td> <td>:</td> <td> <?php echo isset($location) ? $location : ''; ?> </td> </tr>
      <tr> <td> User </td> <td>:</td> <td> <?php echo isset($staff) ? $staff : ''; ?> </td> </tr>
	</table>
	</div>
    
    <div id="venbox2">
	<table width="100%" style="font-size:1em; margin:0; text-align:left; font-weight:bold;">
	  <tr> <td> Description </td> <td>:</td> <td> <?php echo isset($desc) ? $desc : ''; ?> </td> </tr>
      <tr> <td> Status </td> <td>:</td> <td> <?php echo isset($status) ? $status : ''; ?> </td> </tr>
	</table>
	</div>
    
	<div id="tablebox">
    
    <div id='jqxWidget'>
    
    <fieldset class="field"> <legend> Assembly </legend>
    
    <table class="product">

     <tr> <th> No </th>  <th> Notes </th> <th> Desc </th> <th> Action </th> </tr>
     
     <!-- <tr> <td> 1 </td> <td class="left"> PO-0021 - Pembelian Alat Kantor &nbsp; GD4523 </td> <td class="right"> 1.000.000 </td> </tr> -->
     
     <?php
        
        if ($items)
        {
            $i=1;
            foreach ($items as $res)
            {
                echo "
                
                 <tr> 
                    <td class=\"center\"> ".$res->no." </td>
					<td class=\"left\"> ".ucfirst($res->notes)." </td>
                    <td class=\"left\"> ".ucfirst($res->desc)." </td>
                    <td class=\"center\"> ".anchor(site_url("project/download/".$res->id), '[ Download ]', 'title="Download Data"')." </td>
                 </tr>
                
                "; $i++;
            }
        }
        
     ?>
        
    </table>
    
    </fieldset> <div class="clear"></div> <br />
    
    <fieldset class="field"> <legend> Product List </legend>
    <div style='margin-top: 10px;' id="jqxgrid"> </div> 
    
		
		<table id="table">
        <thead>
	    <tr> <th> No </th> <th> Assembly </th> <th> Product </th> <th> Qty </th> <th> Unit </th> <th> Desc </th> </tr>	
        </thead>

        <tbody>
        
        <?php
        
          if ($product)
		  {
			$i=1;  
			foreach ($product as $res)
	        {
			     echo "
				 <tr> 
					<td> ".$i." </td>
					<td class=\"left\">".$res->assembly." </td> 
					<td class=\"left\"> ".$res->product." </td> 
					<td class=\"left\"> ".$res->qty." </td> 
					<td class=\"right\"> ".$res->unit." </td>
					<td class=\"left\"> ".$res->desc." </td>   
				 </tr>
				
				"; $i++;
		    }
		  }
        
        ?>
        
        </tbody>

		</table>
	</div>  
    </fieldset>
    
    <div class="clear"></div>
    	
    <div style="width:620px; border:0px solid #000; float:right; margin:15px 0px 0 0;">
    <style>
        .sig{ font-size:11px; width:100%; float:right; text-align:center;}
        .sig td{ width:155px;}
    </style>
        <table border="0" class="sig">
            <tr> <td> Approved By : </td> <td> Review By : </td> <td> Review By : </td> <td> Prepared By : </td> </tr>
        </table> <br> <br> <br> <br> <br> <br />
        
        <table border="0" class="sig">
            <tr> <td> Director </td> <td> Workshop </td> <td> Accounting </td> <td> (<?php echo $staff; ?>) </td> </tr>
        </table>
    </div>
	
	
</div>

</body>
</html>
