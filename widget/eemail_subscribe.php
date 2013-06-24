<?php
$eemail_abspath = dirname(__FILE__);
$eemail_abspath_1 = str_replace('wp-content/plugins/email-newsletter/widget', '', $eemail_abspath);
$eemail_abspath_1 = str_replace('wp-content\plugins\email-newsletter\widget', '', $eemail_abspath_1);
require_once($eemail_abspath_1 .'wp-config.php');

$Email=@$_POST["txt_email_newsletter"];

global $wpdb, $wp_version;
$cSql = "select * from ".WP_eemail_TABLE_SUB." where eemail_email_sub='" . mysql_real_escape_string(trim($Email)). "'";
$data = $wpdb->get_results($cSql);
if ( empty($data) ) 
{
	$sql = "insert into ".WP_eemail_TABLE_SUB.""
		. " set `eemail_name_sub` = 'NONAME"
		. "', `eemail_email_sub` = '" . mysql_real_escape_string(trim($Email))
		. "', `eemail_status_sub` = 'YES"
		. "', `eemail_date_sub` = CURDATE()";
	
	$wpdb->get_results($sql);
	echo "succ";
}
else
{
	echo "exs";
}
?>