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

<script type="text/javascript">	
	function refreshparent() { opener.location.reload(true); }
</script>

<body onUnload="refreshparent()">
<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Inventory </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>" >
				<table>
					
			<tr>	
			<td> <label for="cbrand"> Brand (*) </label> </td> <td>:</td>
			<td> <?php $js = 'class="required"'; echo form_dropdown('cbrand', $brand, isset($default['brand']) ? $default['brand'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
			
			<tr>	
			<td> <label for="ccategory"> Category (*) </label> </td> <td>:</td>
			<td> <?php $js = 'class="required"'; echo form_dropdown('ccategory', $category, isset($default['category']) ? $default['category'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
            
            <tr> <td> <label for="ttype"> Type </label></td> <td>:</td> 
			<td> <input type="text" readonly name="ttype" size="10" title="Type"
			value="<?php echo set_value('ttype', isset($default['type']) ? $default['type'] : ''); ?>" /> <br /> </td> </tr>
            
            <tr>	
			<td> <label for="cwarehouse"> Warehouse </label> </td> <td>:</td>
			<td> <?php $js = 'class="required"'; echo form_dropdown('cwarehouse', $warehouse, isset($default['warehouse']) ? $default['warehouse'] : '', $js); ?> &nbsp; <br /> </td>
			</tr> 
						
			<tr> <td> <label for="tname"> Model / Name (*)</label></td> <td>:</td> 
			<td> <input type="text" class="required" name="tname" size="45" title="Product Model"
			value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> <br /> </td> </tr>
			
			<tr>	
			<td> <label for="tname"> Currency </label> </td> <td>:</td>
			<td> <?php $js = 'class="required"'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
			
			<tr> <td> <label for="tqty"> Qty </label></td> <td>:</td> 
			<td> <input type="text" readonly maxlength="5" name="tqty" id="tqtys" size="5" title="Qty" onKeyUp="checkdigit(this.value, 'tqtys')"
			value="<?php echo set_value('tqty', isset($default['qty']) ? $default['qty'] : ''); ?>" /> <br /> </td> </tr>
            
            <tr> <td> <label for="tbuying"> Buying Price </label></td> <td>:</td> 
			<td> <input type="text" maxlength="10" readonly name="tbuying" size="15" title="Buying"
			value="<?php echo set_value('tbuying', isset($default['buying']) ? $default['buying'] : ''); ?>" /> <br /> </td> </tr>
            
            <tr> <td> <label for="tucost"> Unit Costs </label></td> <td>:</td> 
			<td> <input type="text" maxlength="10" readonly name="tucost" id="tucost" size="15" title="Unit" 
			value="<?php echo set_value('tucost', isset($default['ucost']) ? $default['ucost'] : ''); ?>" /> <br /> </td> </tr>
			
			<tr> <td> <label for="tprice"> Price </label></td> <td>:</td> 
			<td> <input type="text" maxlength="10"  name="tprice" id="tprice" size="15" title="Price" onKeyUp="checkdigit(this.value, 'tprice')"
			value="<?php echo set_value('tprice', isset($default['price']) ? $default['price'] : ''); ?>" /> <br /> </td> </tr>
			
			<tr>	
			<td> <label for="cunit"> Unit (*) </label> </td> <td>:</td>
			<td> <?php $js = 'class="required"'; echo form_dropdown('cunit', $unit, isset($default['unit']) ? $default['unit'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
			
			<tr> <td> <label for="tdesc"> Description </label> </td> <td>:</td> 
	<td> <textarea name="tdesc" class="" title="Description" cols="45" rows="3"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> 
	<br /> </td></tr>	
						
						<tr>
							<td> <label for="tname"> Vendor (*) </label></td> <td>:</td> <td> 
					        <input type="text" readonly name="tvendor" id="tcust" size="35" title="Vendor"
							value="<?php echo set_value('tvendor', isset($default['vendor']) ? $default['vendor'] : ''); ?>" /> 
						    <?php echo anchor_popup(site_url("vendor/get_list/"), '[ ... ]', $atts1); ?>
					        </td> 
						</tr> <br />
										   
				</table>
				<p style="margin:15px 0 0 0; float:right;">
					<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
					<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
				</p>	
			</form>			  
	</fieldset>
</div>
</body>
