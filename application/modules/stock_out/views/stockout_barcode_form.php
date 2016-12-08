<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>

<!-- <script type="text/javascript" src="<?php echo base_url();?> js/complete.js"></script> -->
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script>
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";

function refreshparent() { opener.location.reload(true); }

	$(document).ready(function(){

		$('#barcodeform').submit(function() {

		  	var res;
		  	res = document.getElementById("tproduct").value;

				 $.ajax({
					type: 'POST',
					url: $(this).attr('action'),
					data: $(this).serialize(),
					success: function(data) {
						// $('#result').html(data);
						if (data == "true")
						{
							 document.getElementById("tproduct").value = '';
							 document.getElementById("warnmess").innerHTML = res+' | Succesfull Saved...!';
						}
						else
						{
							// alert(data);
							document.getElementById("errorbox").innerHTML = data;
						}

					}
				})
				return false;
		});

	//end document ready
	});

</script>

<body onUnload="refreshparent();">
<div id="webadmin">
<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>

<fieldset class="field"> <legend> Stock Out - Barcode Transaction  </legend>

	<p id="warnmess" style="color:#FFFFFF; font-size:9pt; font-weight:bold; margin:2px 0 5px 0px; padding:2px 0px 2px 10px; background-color:#DE0000;">  </p>
	<form name="modul_form" class="myform" id="barcodeform" method="post" action="<?php echo $form_action_item; ?>">
				<table>

					<tr>
   <td> <label for="tno"> No - BPBG-00 </label> </td> <td>:</td>
   <td> <input type="text" class="required" readonly name="tno" size="4" title="Name" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; </td>
					</tr>

					<tr>
					<td> <label for="tno"> Product </label> </td> <td>:</td>
			    <td> <input type="text" class="required" name="tproduct" id="tproduct" size="35" title="Name" autofocus /> </td>
					</tr>

					<tr>
					<td> <label for="tno"> Qty </label> </td> <td>:</td>
			    <td> <input type="text" name="tqty" id="tqtys" size="2" title="Qty" value="1" onKeyUp="checkdigit(this.value, 'tqtys')" /> &nbsp; </td>
					</tr>

					<tr>
					<td> <label for="tno"> Project - Desc </label> </td> <td>:</td>
			    <td> <?php $js = 'class=""'; echo form_dropdown('cproject', $project, isset($default['project']) ? $default['project'] : '', $js); ?> </td>
					</tr>

					<tr>
					<td> <label for="tno"> Staff - Desc </label> </td> <td>:</td>
			    <td> <input type="text" name="tstaff" size="15" title="Staff" /> </td>
					</tr>

					<tr>
						<td></td>
						<td colspan="2" align="left"> &nbsp; 
                           <input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value="SUBMIT" />  
                        </td>
					</tr>

				</table>
			</form>
	</fieldset> <div class="clear"></div>
<?php echo ! empty($table) ? $table : ''; ?>
</div>

<div class="buttonplace">  </div>
</body>
