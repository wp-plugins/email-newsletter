<div class='wrap'>
<?php
include_once("inc/button.php");
if(@$_POST['send']=="true") 
{
	get_currentuserinfo();
	@$from_name = $user_identity;
	@$from_address = $user_email;
	@$mail_format = "html";
	@$send_users = $_POST['eemail_checked'];
	
	@$eemail_id = $_POST['eemail_subject_drop'];
	@$emailrecord = array();
	@$emailrecord = eemail_get_email_content($eemail_id);
	@$subject = $emailrecord["eemail_subject"];
	@$mail_content = $emailrecord["eemail_content"];
	
	@$recipients = $send_users;
	$num_sent = eemail_send_mail($recipients, $subject, $mail_content, $mail_format, $from_name, $from_address);
	?>
	<p class="updated" style="color:#F00">Email has been sent to <?php echo $num_sent; ?> user(s), and <?php echo count($recipients);?> recipient(s) were originally found.</p>
	<?php
}
?>
<?php
echo "<h2>"; 
echo "Email newsletter (Send email to comment posted users)";
echo "</h2>";
?>
<style type="text/css">
<!--
.content {
	font-family: 'Segoe UI', Verdana, Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #000000;
	text-decoration: underline;
	background-color:#FFFFFF;
}
.subcontent {
	font-family: 'Segoe UI', Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight:bold;
	color: #990000;
	text-decoration: none;
	background-color:#FFFFFF;
}
-->
</style>
<?php
@$search = @$_GET["Search"];
?>
<h3>Select the email(s) from comment posted users list:</h3>
<script language="JavaScript" src="<?php echo emailnews_plugin_url('inc/setting.js'); ?>"></script>
<form name="form_eemail" method="post" action="admin.php?page=add_admin_menu_email_to_comment_posed_user" onsubmit="return send_email_submit()"  >
<input type="hidden" name="send" value="true" />
<input type="button" name="CheckAll" value="Check All" onClick="SetAllCheckBoxes('form_eemail', 'eemail_checked[]', true);">
<input type="button" name="UnCheckAll" value="Uncheck All" onClick="SetAllCheckBoxes('form_eemail', 'eemail_checked[]', false);">
<div style="padding-bottom:14px;padding-top:10px;">
  <a class="<?php if(@$search == "A,B,C") { echo "subcontent"; } else { echo "content"; } ?>" href="admin.php?page=add_admin_menu_email_to_comment_posed_user&Search=A,B,C">A,B,C</a>&nbsp;&nbsp;
  <a class="<?php if(@$search == "D,E,F") { echo "subcontent"; } else { echo "content"; } ?>" href="admin.php?page=add_admin_menu_email_to_comment_posed_user&Search=D,E,F">D,E,F</a>&nbsp;&nbsp;
  <a class="<?php if(@$search == "G,H,I") { echo "subcontent"; } else { echo "content"; } ?>" href="admin.php?page=add_admin_menu_email_to_comment_posed_user&Search=G,H,I">G,H,I</a>&nbsp;&nbsp;
  <a class="<?php if(@$search == "J,K,L") { echo "subcontent"; } else { echo "content"; } ?>" href="admin.php?page=add_admin_menu_email_to_comment_posed_user&Search=J,K,L">J,K,L</a>&nbsp;&nbsp;
  <a class="<?php if(@$search == "M,N,O") { echo "subcontent"; } else { echo "content"; } ?>" href="admin.php?page=add_admin_menu_email_to_comment_posed_user&Search=M,N,O">M,N,O</a>&nbsp;&nbsp;
  <a class="<?php if(@$search == "P,Q,R") { echo "subcontent"; } else { echo "content"; } ?>" href="admin.php?page=add_admin_menu_email_to_comment_posed_user&Search=P,Q,R">P,Q,R</a>&nbsp;&nbsp;
  <a class="<?php if(@$search == "S,T,U") { echo "subcontent"; } else { echo "content"; } ?>" href="admin.php?page=add_admin_menu_email_to_comment_posed_user&Search=S,T,U">S,T,U</a>&nbsp;&nbsp;
  <a class="<?php if(@$search == "V,W,X,Y,Z") { echo "subcontent"; } else { echo "content"; } ?>" href="admin.php?page=add_admin_menu_email_to_comment_posed_user&Search=V,W,X,Y,Z">V,W,X,Y,Z</a>
</div>
<?php
global $wpdb, $wp_version;

$sSql = "SELECT DISTINCT(comment_author_email) as user_email, `comment_author`,`comment_author_email` FROM ". $wpdb->prefix . "comments WHERE comment_author_email <> ''";
if(@$search <> "")
{
	$array = explode(',', @$search);
	$length = count($array);
	for ($i = 0; $i < $length; $i++) 
	{
		if(@$i == 0)
		{
			$sSql = $sSql . " and";
		}
		else
		{
			$sSql = $sSql . " or";
		}
		$sSql = $sSql . " comment_author_email LIKE '" . $array[$i]. "%'";
	}
}
$sSql = $sSql . " ORDER BY comment_author_email";
$data = $wpdb->get_results($sSql);
$count = 0;
if ( !empty($data) ) 
{
echo "<table border='0' style='padding:4px;'><tr>";
$col=3;
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
<div style="padding-top:10px;">Total email(s): <?php echo $count; ?></div>
<?php
$data = $wpdb->get_results("select eemail_id,eemail_subject  from ".WP_eemail_TABLE." where 1=1 and eemail_status='YES' order by eemail_id desc");
if ( !empty($data) ) 
{
foreach ( $data as $data )
{
	if($data->eemail_subject <> "")
	{
		@$eemail_subject_drop_val = @$eemail_subject_drop_val . '<option value="'.$data->eemail_id.'">' . stripcslashes($data->eemail_subject) . '</option>';
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
