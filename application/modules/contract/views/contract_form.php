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

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Order Contract </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>" >
				<table>
					<tr> 
						<td> <label for="tcust"> Customer </label> </td> <td>:</td>
						<td> <input type="text" class="required" readonly="readonly" name="tcust" id="tcust" size="25" title="Name" /> &nbsp; 
						<?php echo anchor_popup(site_url("customer/get_list/"), '[ ... ]', $atts1); ?>
					</tr>
                    
                    <tr>
						<td> <label for="tdocno"> Doc - No </label> </td>  <td>:</td>
						<td> <input type="text" class="required" name="tdocno" size="20" title="DocNo" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						<td> <label for="tno"> No - CO-00 </label> </td> <td>:</td>
	     <td> <input type="text" class="required" name="tno" id="tno" size="4" title="Name" onkeyup="checkdigit(this.value, 'tno')"
		       value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
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
						   <input type="Text" name="tdealdate" id="d3" title="Deal date" size="10" class="required" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d3','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
                    
					<tr>	
						 <td> <label for="tdate"> Period Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					
					<tr>	
						 <td> <label for="tdue"> Order Due </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdue" id="d2" title="Due date" size="10" class="required" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d2','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					
			<tr>	
			<td> <label for="tname"> Currency </label> </td> <td>:</td>
			<td> <?php $js = 'class="required"'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
					
					<tr>
						<td> <label for="tnote"> Note </label> </td>  <td>:</td>
						<td> <input type="text" class="required" name="tnote" size="56" title="Note" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						<td> <label for="tuser"> Marketing Dept </label> </td> <td>:</td>
	     <td> <input type="text" class="required" name="tuser" size="15" title="User" value="<?php echo isset($user) ? $user : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
					   
				</table>
				<p style="margin:15px 0 0 0; float:right;">
					<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
					<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
				</p>	
			</form>			  
	</fieldset>
</div>

