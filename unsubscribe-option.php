<link rel="stylesheet" type="text/css" href="<?php echo EMAIL_PLUGIN_URL; ?>/inc/admin-css.css" />
<div class='wrap'>
<h2>Unsubscribe option setting</h2>
<div class="emailn-left">
<div class="emailn-left-widgets">
<?php
global $wpdb, $wp_version;

$eemail_un_option = get_option('eemail_un_option');
$eemail_un_text = get_option('eemail_un_text');
$eemail_un_link = get_option('eemail_un_link');

if (@$_POST['eemail_submit']) 
{
	$eemail_un_option = stripslashes($_POST['eemail_un_option']);
	$eemail_un_text = stripslashes($_POST['eemail_un_text']);
	$eemail_un_link = stripslashes($_POST['eemail_un_link']);
	
	update_option('eemail_un_option', $eemail_un_option );
	update_option('eemail_un_text', $eemail_un_text );
	update_option('eemail_un_link', $eemail_un_link );
}
?>
<form name="form_eemail" method="post" action="">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="height:30px;">Unsubscribe Option</td>
  </tr>
  <tr>
    <td style="height:30px;">
	<select name="eemail_un_option" id="eemail_un_option">
      <option value="Yes" <?php if(@$eemail_un_option=='Yes') { echo 'selected' ; } ?>>Yes, Add an unsubscribe link in the email.</option>
      <option value="No" <?php if(@$eemail_un_option=='No') { echo 'selected' ; } ?>>No, Dont want unsubscribe link in the email.</option>
    </select>
	</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td style="height:30px;">Unsubscribe Text </td>
  </tr>
  <tr>
    <td><textarea name="eemail_un_text" cols="80" rows="5"><?php echo $eemail_un_text; ?></textarea></td>
  </tr>
  <tr>
    <td style="height:30px;">Unsubscribe Link </td>
  </tr>
  <tr>
    <td><input name="eemail_un_link" type="text" size="100" value="<?php echo $eemail_un_link; ?>" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input type="submit" id="eemail_submit" name="eemail_submit" lang="publish" class="button-primary" value="Update" ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Note: This unsubscribe option applicable only for "Send mail to subscribed users" newsletters.</td>
  </tr>
</table>
</form>
</div>
  </div>
  <div class="emailn-right">
    <div class="emailn-widgets">
      <h3>About</h3>
      <div class="emailn-widgets-desc"> 
	  Email newsletter plugin have option to send HTML Mails/Newsletters to users. <a href="http://www.gopiplus.com/work/2010/09/25/email-newsletter/" target="_blank">click here</a> to view more information.	  </div>
    </div>
	<div class="emailn-widgets">
      <h3>Options</h3>
	  <div class="emailn-widgets-desc">
	  <ul>
	  	<li>Compose email</li>
		<li>Send mail to registered user</li>
		<li>Send mail to comment posted user</li>
		<li>Send mail to subscribed user</li>
		<li>Send mail to simple contact form emails</li>
		<li>Export CSV file</li>
		<li>Import emails</li>
	  </ul>
	  </div>
    </div>
  </div>
</div>