<div class='wrap'>
<br>
<table width="100%">
	<tr>
	  <td align="center">
		<input name="btn_plugin_home" lang="btn_plugin_home" class="button-primary" onClick="location.href='options-general.php?page=email-newsletter/email-newsletter.php'" value="Plugin home" type="button" />
		<input name="btn_compose_email_page" lang="btn_compose_email_page" class="button-primary" onClick="location.href='options-general.php?page=email-newsletter/email-compose.php'" value="Compose email" type="button" />
		<input name="btn_send_email_registered_user" lang="btn_send_email_registered_user" class="button-primary" onClick="location.href='options-general.php?page=email-newsletter/email-to-registered-user.php'" value="Send email to registered user" type="button" />
		<input name="btn_send_email_comment_posted_user" lang="btn_send_email_comment_posted_user" class="button-primary" onClick="location.href='options-general.php?page=email-newsletter/email-to-comment-posed-user.php'" value="Send email to comment posed user" type="button" />
	  </td>
	</tr>
</table>
<?php
if($_POST['send']=="true") 
{
	get_currentuserinfo();
	$from_name = $user_identity;
	$from_address = $user_email;
	$mail_format = "html";
	$send_users = $_POST['eemail_checked'];
	
	$eemail_id = $_POST['eemail_subject_drop'];
	$emailrecord = array();
	$emailrecord = eemail_get_email_content($eemail_id);
	$subject = $emailrecord["eemail_subject"];
	$mail_content = $emailrecord["eemail_content"];
	
	$recipients = $send_users;
	$num_sent = eemail_send_mail($recipients, $subject, $mail_content, $mail_format, $from_name, $from_address);
	?>
	<p class="updated">Email has been sent to <?php echo $num_sent; ?> users, but <?php echo count($recipients);?> recipients were originally found.</p>
	<?php
}
?>
<h2>Email newsletter (Send Email)</h2>
<h3>Select the email(s) from registered users list:</h3>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/setting.js"></script>
<form name="form_eemail" method="post" action="options-general.php?page=email-newsletter/email-to-registered-user.php" onsubmit="return send_email_submit()"  >
<input type="hidden" name="send" value="true" />
<input type="button" name="CheckAll" value="Check All" onClick="SetAllCheckBoxes('form_eemail', 'eemail_checked[]', true);">
<input type="button" name="UnCheckAll" value="Uncheck All" onClick="SetAllCheckBoxes('form_eemail', 'eemail_checked[]', false);">
<?php
global $wpdb, $wp_version;
$data = $wpdb->get_results("select user_nicename,user_email from ". $wpdb->prefix . "users ORDER BY user_nicename");
if ( !empty($data) ) 
{
echo "<table border='1' style='padding:4px;'><tr>";
$col=3;
$count = 0;
foreach ( $data as $data )
{
	$to = $data->user_email;
	if($to <> "")
	{
		echo "<td>";
		?>
		<input class="radio" type="checkbox" checked="checked" value='<?php echo $to; ?>' name="eemail_checked[]">&nbsp;<?php echo $to; ?>
		<?php
		if($col > 1) 
		{
			$col=$col-1;
			echo "</td><td>"; 
		}
		elseif($col = 1)
		{
			$col=$col-1;
			echo "</td></tr><tr>";;
			$col=3;
		}
		$count = $count + 1;
	}
}
echo "</tr></table>";
}
?>
<?php
$data = $wpdb->get_results("select eemail_id,eemail_subject  from ".WP_eemail_TABLE." order by eemail_id desc");
if ( !empty($data) ) 
{
foreach ( $data as $data )
{
	if($data->eemail_subject <> "")
	{
		$eemail_subject_drop_val = $eemail_subject_drop_val . '<option value="'.$data->eemail_id.'">' . stripcslashes($data->eemail_subject) . '</option>';
	}
}
}
?>

<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><h3>Select the email subject and send:</h3></td>
  </tr>
  <tr>
    <td>Subject : <select name="eemail_subject_drop" id="eemail_subject_drop">
	<option value="">Select email subject</option>
	<?php echo $eemail_subject_drop_val; ?>
</select><br /><br />
<input type="submit" name="Submit" class="button-primary" value="Send email" /></td>
  </tr>
  <tr>
    <td></td>
  </tr>
</table>

</form>
</div>
