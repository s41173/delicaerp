<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/development-bundle/ui/ui.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tools.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/complete.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
</script>

<script type="text/javascript">	
	function refreshparent() { opener.location.reload(true); }
	
	function balance()
	{ 
	  var val1 = parseFloat(document.getElementById("tamount").value);
	  var val2 = parseFloat(document.getElementById("ttax").value);
	  var res =  parseFloat(val1+val2); 
      document.getElementById("tbalance").value = res;
	}
	
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
	
	<fieldset class="field" style="float:left;"> <legend> Purchasing </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
						<td> <label for="tcust"> Customer </label> </td> <td>:</td>
						<td> <input type="text" class="required" name="tcust" readonly id="tcust" size="25" title="Name"
						value="<?php echo set_value('tcust', isset($default['customer']) ? $default['customer'] : ''); ?>" /> &nbsp; 
						<?php echo anchor_popup(site_url("customer/get_list/"), '[ ... ]', $atts1); ?>
					</tr>
                    
                    <tr>
						<td> <label for="tdocno"> Doc - No </label> </td>  <td>:</td>
						<td> <input type="text" class="required" name="tdocno" size="20" title="DocNo" value="<?php echo set_value('tdocno', isset($default['docno']) ? $default['docno'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						<td> <label for="tno"> No - CO-00 </label> </td> <td>:</td>
	     <td> <input type="text" class="required" readonly name="tno" size="4" title="Name" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
                    
                     <tr>	
						<td> <label for="ctype"> Contract Type </label> </td> <td>:</td>
	                    <td> <select name="ctype"> 
<option value="tax"<?php echo set_select('ctype', 'tax', isset($default['type']) && $default['type'] == 'tax' ? TRUE : FALSE); ?>> Tax </option>
<option value="non"<?php echo set_select('ctype', 'non', isset($default['type']) && $default['type'] == 'non' ? TRUE : FALSE); ?>> Non </option>
                             </select> &nbsp;
                        </td>
					</tr>
                    
                    <tr>	
						 <td> <label for="tdate"> Deal Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdealdate" id="d3" title="Deal date" size="10" class="required"
						   value="<?php echo set_value('tdealdate', isset($default['dealdate']) ? $default['dealdate'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d3','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					
					<tr>	
						 <td> <label for="tdate"> Period Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required"
						   value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					
					<tr>	
						 <td> <label for="tdue"> Order Due </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdue" id="d2" title="Invoice date" size="10" class="required"
						   value="<?php echo set_value('tdue', isset($default['due']) ? $default['due'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d2','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					
			<tr>	
			<td> <label for="tname"> Currency </label> </td> <td>:</td>
			<td> <?php $js = 'class="required"'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
					
					<tr>
						<td> <label for="tnote"> Note </label> </td>  <td>:</td>
						<td>  <input type="text" class="required" name="tnote" size="56" title="Note"
						value="<?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						<td> <label for="tuser"> Marketing Dept </label> </td> <td>:</td>
						<td> <input type="text" class="required" name="tuser" size="15" title="User"
						value="<?php echo set_value('tuser', isset($default['user']) ? $default['user'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					   
				</table>  
	</fieldset>  
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Payment Details </legend>
		
		<table>
			
            <tr>
				<td> <label for="tamount"> Amount </label></td> <td>:</td> 
				<td><input type="text" id="tamount" name="tamount" size="15" title="Amount" 
			    value="<?php echo set_value('tamount', isset($default['amount']) ? $default['amount'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tamount'); balance();" /> </td> 
			</tr>
            
            <tr>
				<td> <label for="ttax"> Tax </label></td> <td>:</td> 
				<td><input type="text" id="ttax" name="ttax" size="15" title="Tax" 
			    value="<?php echo set_value('ttax', isset($default['tax']) ? $default['tax'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'ttax'); balance();" /> </td> 
			</tr>
            		
			<tr>
				<td> <label for="tbalance"> Balance </label></td> <td>:</td> 
				<td> <input type="text" id="tbalance" readonly name="tbalance" size="15" title="Balance" 
			    value="<?php echo set_value('tbalance', isset($default['balance']) ? $default['balance'] : '0'); ?>" /> </td> 
			</tr>
			
		</table>
		
	</fieldset>
	
	
	
	<p style="margin:10px 0 0 10px; float:left;">
		<input type="submit" name="submit" class="button" value=" Save " /> 
		<input type="reset" name="reset" class="button" value=" Cancel " />
	</p>	
	</form>		
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Phase Transaction </legend>
		<?php echo ! empty($table) ? $table : ''; ?>
	</fieldset>
	
</div>

</body>