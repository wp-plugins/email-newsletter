<?php
$referer = @$_SERVER['HTTP_REFERER'];
//if($referer <> "")
//{
	$rand = @$_GET["rand"];
	$user = @$_GET["user"];
	$reff = @$_GET["reff"];
	
	if (!is_numeric($rand)) 
	{
    	echo "Unexpected error occurred: 1";
		die;
	}
	
	if ($user == "") 
	{
		echo "Unexpected error occurred: 2";
		die;
	}
	
	if ($reff == "") 
	{
		echo "Unexpected error occurred: 3";
		die;
	}
	
	$eemail_abspath = dirname(__FILE__);
	$eemail_abspath_1 = str_replace('wp-content/plugins/email-newsletter/unsubscribe', '', $eemail_abspath);
	$eemail_abspath_1 = str_replace('wp-content\plugins\email-newsletter\unsubscribe', '', $eemail_abspath_1);
	require_once($eemail_abspath_1 .'wp-config.php');
	
	global $wpdb, $wp_version;
	$cSql = "select * from ".WP_eemail_TABLE_SUB." where"; 
	$cSql = $cSql . " eemail_id_sub = ". $rand;
	$cSql = $cSql . " and eemail_email_sub ='" . mysql_real_escape_string(trim($user)). "'";

	$data = $wpdb->get_results($cSql);
	if ( ! empty($data) ) 
	{
		$sql = "delete from ".WP_eemail_TABLE_SUB." where 1=1";
		$sql = $sql . " and eemail_id_sub = ". $rand;
		$sql = $sql . " and eemail_email_sub ='" . mysql_real_escape_string(trim($user)). "'";
		$sql = $sql . " ORDER BY eemail_id_sub LIMIT 1";
		$wpdb->get_results($sql);
		echo "You have been unsubscribed.";
	}
	else
	{
		echo "Your email is not in our newsletter list";
	}
//}
//else
//{
//	echo "Unexpected error occurred: 4";
//}
?>