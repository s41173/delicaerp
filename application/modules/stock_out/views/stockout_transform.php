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

  function inputs()
  {
	  var res;
	  res = document.getElementById("ctype").value;
	  if (res == "0")
	  {
		 document.getElementById("tproduct").readOnly = false;
		 document.getElementById("linkbutton").disabled = true;
		 document.getElementById("tqtys").value = '1';
	  }
	  else
	  {
	     document.getElementById("tproduct").readOnly = true;
		 document.getElementById("linkbutton").disabled = false;
	     document.getElementById("tqtys").value = '';
	  }
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
		'id'         => 'linkbutton',
	  'title'      => 'add cust',
	  'width'      => '600',
	  'height'     => '500',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 500)/2)+\'',
);

$atts2 = array(
	  'class'      => 'refresh',
		'id'         => '',
	  'title'      => '',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'no',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<body onUnload="refreshparent();" onload="inputs();">

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>

	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>

	<fieldset class="field" style="float:left;">  <legend> Stock - Out </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>

					<tr>
						<td> <label for="tno"> No - BPBG-00 </label> </td> <td>:</td>
	          <td> <input type="text" class="required" readonly name="tno" size="4" title="Name" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
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
						<td> <label for="tcurrency"> Currency </label> </td>  <td>:</td>
						<td>  <input type="text" readonly name="tcurrency" size="10" title="Currency"
						value="<?php echo set_value('tcurrency', isset($default['currency']) ? $default['currency'] : ''); ?>" /> &nbsp; <br /> </td>
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
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action_item; ?>">
		<table>
			<tr>

				<td> <label for="tproduct"> Product </label>  <br />
				     <input type="text" class="required" name="tproduct" id="tproduct" size="35" title="Name" autofocus /> &nbsp;
				     <?php echo anchor_popup(site_url("product/get_list/".$default['currency'].'/tproduct/'), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>

				<td>
					<label for="tqty"> Qty : </label> <br />
					<input type="text" name="tqty" id="tqtys" size="4" title="Qty" onKeyUp="checkdigit(this.value, 'tqtys')" /> &nbsp;
				</td>

				<td>
					<label for="tdesc"> Desc : </label> <br />
					<?php $js = 'class="required"'; echo form_dropdown('cproject', $project, isset($default['project']) ? $default['project'] : '', $js); ?> &nbsp;
				</td>

			    <td> <label for="tstaff"> Staff Desc </label> <br />
				     <input type="text" name="tstaff" size="15" title="Staff" /> &nbsp;
                </td>

				<td> <br />
					<input type="submit" name="submit" class="button" title="POST" value="POST" />
				</td>
			</tr>
		</table>

		<div class="clear"></div>
		<?php echo ! empty($table) ? $table : ''; ?>

	</form>

	<table align="right" style="margin:10px 0px 0 0; padding:3px; " width="100%" bgcolor="#D9EBF5">
	<tbody>
		<tr>
		   <td align="left">
		   <?php echo anchor_popup(site_url("stock_out/add_barcode/".$code.'/'), '[ BARCODE FORM ]', $atts2); ?>
		   </td>
		</tr>
	</tbody>
	</table>

	</fieldset>

</div>

</body>
