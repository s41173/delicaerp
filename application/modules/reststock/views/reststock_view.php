	
<?php 

$atts = array(
		  'class'      => 'refresh',
		  'title'      => 'add po',
		  'width'      => '600',
		  'height'     => '400',
		  'scrollbars' => 'yes',
		  'status'     => 'yes',
		  'resizable'  => 'yes',
		  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
		  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
		);
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'Purchase Invoice',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

$atts2 = array(
	  'class'      => 'refresh',
	  'title'      => 'Purchase Report',
	  'width'      => '550',
	  'height'     => '350',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 550)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 350)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Rest Stock </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
					<td> <label for="cware"> Warehouse : </label> <br />
      <?php $js = 'class="required"'; echo form_dropdown('cware', $warehouse, isset($default['warehouse']) ? $default['warehouse'] : '', $js); ?> 
                    &nbsp;
                    </td>
					
					<td> Name ( Keyword ) : &nbsp; <br /> 
					     <input type="text" name="tsearch" size="30" id="tproductsearch" /> &nbsp;
					</td>	
					
					<td colspan="3" align="right"> <br /> 
					<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value="Search" />  
					</td>
					
					</tr> 
				</table>	
			</form>			  
	</fieldset>
</div>


<div id="webadmin2">
	
	<form name="search_form" class="myform" method="post" action="<?php echo ! empty($form_action_del) ? $form_action_del : ''; ?>">
     <?php echo ! empty($table) ? $table : ''; ?>
	 <div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	</form>	
	
	<table align="right" style="margin:10px 0px 0 0; padding:3px; " width="100%" bgcolor="#D9EBF5">
	<tbody>
		<tr> 
		   <td align="right"> 
		   <?php echo anchor_popup(site_url("reststock/add"), 'CREATE NEW', $atts); ?>
		   <?php echo anchor_popup(site_url("reststock/report"), 'REPORT', $atts2); ?>
		   </td> 
		</tr>
	</tbody>
	</table>

		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

