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

</script>

<body onUnload="">
<div id="webadmin">

<fieldset class="field"> <legend> Stock - Barcode  </legend>

	<img style="align:center; margin:0 auto 0 auto;" width="180" height="100" src="<?php echo site_url('product');?>/set_barcode/<?php echo $pid;?>">

</fieldset> <div class="clear"></div>
</div>

</body>
