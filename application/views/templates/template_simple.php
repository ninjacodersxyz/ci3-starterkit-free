<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="<?= base_url() ?>">
	<title><?php echo $this->config->item('website_name'); ?> | {title}</title>
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url();?>css/admin_style.css">

	<script src="https://cdn.jsdelivr.net/g/jquery@2.2.4"></script>
	<script type="text/javascript" src="<?php echo asset_url();?>js/scripts.min.js" charset="utf-8"></script>
</head>
<body>
	{content}	
	<script type="text/javascript">
		$(document).ready(function() {{scripts}});
	</script>
</body>
</html>