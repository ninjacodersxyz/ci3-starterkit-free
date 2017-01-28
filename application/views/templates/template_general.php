<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="<?= base_url() ?>">
	
	<title><?php echo $this->config->item('website_name'); ?> | {title}</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/css/dataTables.bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo asset_url();?>css/jquery.toast.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url();?>css/admin_style.css">


	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/js/jquery.dataTables.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/js/dataTables.bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/multiselect/2.2.9/js/multiselect.min.js"></script>

	<script type="text/javascript" src="<?php echo asset_url();?>js/jquery.toast.min.js"></script>
	<script type="text/javascript" src="<?php echo asset_url();?>js/scripts.min.js"></script>
</head>
<body>

	<div id="header">{header}</div>

	<div class="container-fluid">
		<div id="sidebar" class="pull-left">
			{sidebar}
		</div>
		<div id="content" class="pull-right">
			{content}
		</div>
	</div>

	<div id="footer">
		<div class="container-fluid">
			<div class="col-md-8">Diseñado y desarrollado por <a href="https://www.boliviasoftware.com/" target="_blank">Bolivia Software</a></div>
			<div class="col-md-4 text-right hidden-sm"><a href="https://www.boliviasoftware.com/" target="_blank"><img src="assets/imgs/boliviasoftware.png" alt="Bolivia Software logo" class="boliviasoftware-logo"></a></div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			{scripts}
		});
	</script>

</body>
</html>