<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>


<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";
</script>

<script type="text/javascript">
	
$(document).ready(function(){
		
	$('#ajaxform,#ajaxform2').submit(function() {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
			// $('#result').html(data);
			if (data == "true"){ location.reload(true);}
			else{ document.getElementById("errorbox").innerHTML = data; }
			}
		})
		return false;
	});	
	
	$('#cstokout').change(function() {
				
		var stockout = $("#tbpbg").val();
		var pro = $("#cstokout").val();
		
		$.ajax({
		type: 'POST',
		url: site +'/return_stock/get_stock_out_qty',
		data: "stockout="+ stockout + "&product=" + pro,
		success: function(data)
		{
		   res = data.split("|");	
		   document.getElementById("tout").value = res[0];
		   document.getElementById("tamountout").value = res[1];
		}
		})
		return false;
	});
	
	
	

/* end document */		
});


	function refreshparent() { opener.location.reload(true); }
	
</script>

<style>
        .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}	
</style>

<?php 
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'add cust',
	  'width'      => '600',
	  'height'     => '500',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 500)/2)+\'',
);

?>

<body onUnload="refreshparent();">  
<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field" style="float:left;">  <legend> Stock - Out </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					
					<tr>	
						<td> <label for="tno"> No - BPB-00 </label> </td> <td>:</td>
	     <td> <input type="text" class="required" readonly name="tno" size="4" title="Name" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						<td> <label for="tbpbg"> BPBG </label> </td> <td>:</td>
	                    <td> <input type="text" readonly id="tbpbg" name="tbpbg" size="5" value="<?php echo isset($stockout) ? $stockout : ''; ?>" /> <br /> </td>
					</tr>
					
					<tr>	
						 <td> <label for="tdate"> Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required"
						   value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					
					
					<tr>
						<td> <label for="tnote"> Note </label> </td>  <td>:</td>
						<td>  <input type="text" class="required" name="tnote" size="56" title="Note"
						value="<?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>
						<td> <label for="tstaff"> Workshop Staff </label> </td>  <td>:</td>
						<td>  <input type="text" class="required" name="tstaff" size="20" title="Staff"
						      value="<?php echo set_value('tstaff', isset($default['staff']) ? $default['staff'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					

					<tr>	
						<td> <label for="tuser"> Warehouse Dept </label> </td> <td>:</td>
	     <td> <input type="text" class="required" readonly name="tuser" size="15" title="User" value="<?php echo isset($user) ? $user : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
					   
				</table>  
	</fieldset>  	
	
	<p style="margin:10px 0 0 10px; float:left;">
		<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
		<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
	</p>	
	</form>		
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Item Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform2" method="post" action="<?php echo $form_action_item; ?>">
		<table>
			<tr>				
				<td> <label for="cproduct"> Product </label>  <br />
				     <?php $js = 'id="cstokout"'; echo form_dropdown('cproduct', $combo, isset($default['product']) ? $default['product'] : '', $js); ?> &nbsp; <br /> </td>
				
				<td>  
					<label for="tout"> Out : </label> <br />
					<input type="text" readonly name="tout" id="tout" size="4" title="Out Qty" onKeyUp="checkdigit(this.value, 'tout')" /> &nbsp;
				</td>
				
				<td>  
					<label for="treturn"> Return : </label> <br />
					<input type="text" name="treturn" id="treturn" size="4" title="Return Qty" onKeyUp="checkdigit(this.value, 'treturn')" /> &nbsp;
				</td>
                
                <td>  
					<label for="treturn"> Amount : </label> <br />
					<input type="text" name="tamount" id="tamountout" size="10" readonly /> &nbsp;
				</td>
				
				<td>  
					<label for="tdesc"> Desc : </label> <br />
					<input type="text" name="tdesc" size="35" title="Desc" /> &nbsp;
				</td>
								
				<td> <br />
					<input type="submit" name="submit" class="button" title="POST" value="POST" /> 
				</td>
			</tr>
		</table>
		
		<div class="clear"></div>
		<?php echo ! empty($table) ? $table : ''; ?>
		
	</form>
	</fieldset>
	
</div>

</body>