<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['website_name'] = 'BoliviaSoftware';

// Mailgun Configuration
$config['use_mailgun'] = TRUE;
$config['mailgun_smtp_host'] = 'smtp.mailgun.org';
$config['mailgun_smtp_user'] = 'postmaster@sandbox.mailgun.org';
$config['mailgun_smtp_pass'] = 'password';
$config['mailgun_smtp_port'] = '25';

// reset password config
$config['reset_password_token_time'] = '60'; // in minutes

// login config
$config['blocking_time'] = '60'; // in minutes
$config['blocking_tries'] = 3;

// dashboard config
$config['chat_messages_qty'] = 30;
$config['chat_date_format'] = '%d-%m-%Y %H:%M';

// table config
$config['table_template'] = array ('table_open' => '<table id="listado" class="table table-striped table-hover table-condensed">');

$config['datepicker_base_options'] = "{ format: 'yyyy-mm-dd',weekStart: 1,daysOfWeekDisabled: '0', daysOfWeekHighlighted: '0',autoclose: true,todayHighlight: true,ignoreReadonly: true}";

/* End of file config.php */
/* Location: ./application/config/custom_config.php */
