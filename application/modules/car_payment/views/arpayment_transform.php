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
	function refreshparent() { opener.location.reload(true); }
	
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
		
		$('#bget').click(function() {

			var pid = $("#titem").val();
			
			$.ajax({
			type: 'POST',
			url: site +"/car_payment/get_so",
			data: "po="+ pid,
			success: function(data)
			{
	//		   res = data.split("|");
			   document.getElementById("tamount").value = data;
			   document.getElementById("tdiscount2").value = '0';
			   document.getElementById("tbalance2").value = data;
			}
			})
			return false;
			
		});
		
		$('#tdiscount2,#tcost2').keyup(function() {
			
			var discount = parseFloat($("#tdiscount2").val());
			var nominal = parseFloat($("#tamount").val());
			var cost = parseFloat($("#tcost2").val());
			var res = nominal-discount-cost;
			
			if (res < 0)
			{
			  document.getElementById("tdiscount2").value = '0';	
			  document.getElementById("tbalance2").value = nominal;
			}
			else { document.getElementById("tbalance2").value = res; }
				
		});
		
	/* end document */		
	});	
	
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
	  'height'     => '400',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<body onUnload="refreshparent();">  
<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field" style="float:left;">  <legend> AR - Customer Payment </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
                <tr> 
                    <td> <label for="tvendor"> Customer </label> </td> <td>:</td>
                    <td> <input type="text" class="required" readonly name="tcustomer" id="tcust" size="25" title="Name"
                    value="<?php echo set_value('tcustomer', isset($default['customer']) ? $default['customer'] : ''); ?>" /> &nbsp; 
                    <?php //echo anchor_popup(site_url("vendor/get_list/"), '[ ... ]', $atts1); ?>
                </tr>
					
			   <tr>
			   <td> <label for="tdocno"> Docno </label> </td>  <td>:</td>
			   <td>  <input type="text" class="required" readonly name="tdocno" size="20" title="Doc No"
			         value="<?php echo set_value('tdocno', isset($default['docno']) ? $default['docno'] : ''); ?>" /> &nbsp; <br /> </td>
			   </tr>
					

                <tr>	
                     <td> <label for="tdate"> Date </label> </td> <td>:</td>
                     <td>  
                       <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required"
                       value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
                       <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
                    </td>
                </tr>
					

			<tr> <td> <label for="cacc"> Account </label> </td> <td>:</td> <td>  
		  <select name="cacc" class="required">
	      <option value="bank" <?php echo set_select('cacc', 'bank', isset($default['acc']) && $default['acc'] == 'bank' ? TRUE : FALSE); ?> /> Bank </option>
          <option value="cash" <?php echo set_select('cacc', 'cash', isset($default['acc']) && $default['acc'] == 'cash' ? TRUE : FALSE); ?> /> Cash </option>
	      <option value="pettycash" <?php echo set_select('cacc', 'pettycash', isset($default['acc']) && $default['acc'] == 'pettycash' ? TRUE : FALSE); ?> /> Petty Cash </option>
		  </select>
			<br />  </td> </tr>
					
			<tr>	
			
			<td> <label for="tcurrency"> Currency </label> </td> <td>:</td>
			<td> <input type="text" class="required" readonly name="tcurrency" size="10" title="Currency"
  	             value="<?php echo set_value('tcurrency', isset($default['currency']) ? $default['currency'] : ''); ?>" /> &nbsp; <br /> </td>
			</tr>
					

					<tr>	
						<td> <label for="tuser"> AR - Dept </label> </td> <td>:</td>
						<td> <input type="text" class="required" readonly name="tuser" size="15" title="User"
						value="<?php echo set_value('tuser', isset($default['user']) ? $default['user'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					
				</table>  
	</fieldset>   
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Payment Details </legend>
		
		<table>
			
             <tr>
				<td> <label for="tcost"> Cost </label></td> <td>:</td> 
				<td><input type="text" id="tcost" readonly name="tcost" size="10" title="Cost" 
			    value="<?php echo set_value('tcost', isset($default['cost']) ? $default['cost'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tcost')" /> </td> 
			</tr>
            
            <tr>
				<td> <label for="tlate"> Late Charges (+) </label></td> <td>:</td> 
				<td><input type="text" id="tlate" name="tlate" size="10" title="Late Charges" 
			    value="<?php echo set_value('tlate', isset($default['late']) ? $default['late'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tlate')" /> </td> 
			</tr>
            
            <tr>
				<td> <label for="tdiscount"> Total Discount </label></td> <td>:</td> 
				<td><input type="text" id="tdiscount" name="tdiscount" readonly size="10" title="Total Discount" 
			    value="<?php echo set_value('tdiscount', isset($default['discount']) ? $default['discount'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tdiscount')" /> </td> 
			</tr>
            
			<tr>
				<td> <label for="tbalance"> Balance </label></td> <td>:</td> 
				<td><input type="text" id="tbalance" disabled="disabled" name="tbalance" readonly size="10" title="Balance" 
			    value="<?php echo set_value('tbalance', isset($default['balance']) ? $default['balance'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tbalance')" /> </td> 
			</tr>
			
		</table>
		
	</fieldset>
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Check Details </legend>
		
		<table>
			
            <tr>
				<td> <label for="tcheck"> Post - Dated </label> </td>  <td>:</td>
				<td> <?php echo form_checkbox('cpost', 1, set_value('cpost', isset($default['status']) ? $default['status'] : 'FALSE')); ?> &nbsp; <br /> </td>
			</tr>
            
			<tr>
				<td> <label for="tcheck"> Check - No </label> </td>  <td>:</td>
				<td> <input type="text" name="tcheck" size="15" title="Check No"
				value="<?php echo set_value('tcheck', isset($default['check']) ? $default['check'] : ''); ?>" /> &nbsp; <br /> </td>
			</tr>
			
			<tr>	
			<td> <label for="cbank"> Bank </label> </td> <td>:</td>
			<td> <?php $js = 'class=""'; echo form_dropdown('cbank', $bank, isset($default['bank']) ? $default['bank'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
			
			<tr>
				<td> <label for="tbalancecek"> Balance </label></td> <td>:</td> 
				<td><input type="text" id="tbalancecek" readonly name="tbalancecek" size="10" title="Balance" 
			    value="<?php echo set_value('tbalancecek', isset($default['balancecek']) ? $default['balancecek'] : '0'); ?>" /> <br />  </td> 
			</tr>
			
			<tr>	
				 <td> <label for="tdue"> Due Date </label> </td> <td>:</td>
				 <td>  
				   <input type="Text" name="tdue" id="d3" title="Due date" size="10" class="required"
				   value="<?php echo set_value('tdue', isset($default['due']) ? $default['due'] : ''); ?>" /> 
				   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d3','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
				</td>
			</tr>
			
		</table>
        
        <p style="margin:10px 0 0 80px; float:left;">
		<input type="submit" name="submit" title="" value=" Save " /> 
		<input type="reset" name="reset" title="" value=" Cancel " />
	   </p>
		
	</fieldset>
	
	
	
		
	</form>		
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Item Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform2" method="post" action="<?php echo $form_action_item; ?>">
		<table>
			<tr>
				<td>  
					<label for="titem"> Transaction No : </label> <br />
					<input type="text" class="required" readonly name="titem" id="titem" size="5" title="Transaction Code" />
					<?php echo anchor_popup(site_url("csales/get_list/".$default['currency'].'/'.$venid.'/'), '[ ... ]', $atts1); ?> &nbsp;
                    <input id="bget" type="button" value="GET"> &nbsp;
				</td>
				
                <td>
					<label for="tamount"> Amount : </label> <br />
					<input type="text" name="tamount" id="tamount" size="10" value="0" onKeyUp="checkdigit(this.value, 'tamount')"> &nbsp;
				</td>
                
				<td>
					<label for="tdiscount"> Discount (-) : </label> <br />
					<input type="text" name="tdiscount" id="tdiscount2" size="10" value="0" onKeyUp="checkdigit(this.value, 'tdiscount2')"> &nbsp;
				</td>
                
                <td>
					<label for="tcost"> Cost (-) : </label> <br />
					<input type="text" name="tcost" id="tcost2" size="10" value="0" onKeyUp="checkdigit(this.value, 'tcost2')"> &nbsp;
				</td>
                
                <td>
					<label for="tbalance"> Balance : </label> <br />
					<input type="text" name="tbalance" readonly id="tbalance2" size="10" value="" > &nbsp;
				</td>
				
				<td> <br />
					<input type="submit" name="submit" class="" title="POST" value="POST" /> 
				</td>
			</tr>
		</table>
		
		<div class="clear"></div>
		<?php echo ! empty($table) ? $table : ''; ?>
		
	</form>
	</fieldset>
    
</div>

</body>